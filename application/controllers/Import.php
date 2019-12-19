<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('productgroup');
		$this->load->helper('address');
		$this->load->helper('name');
		$this->load->model('import/Import_model', '', TRUE);
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('customers/Customers_invoicesmodel', '', TRUE);
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('salesorders/SalesOrder_model', '', TRUE);
		$this->load->model('sellers/Sellers_model', '', TRUE);
		$this->load->model('transporter/Transporter_model', '', TRUE);
		$this->load->model('paymentcondition/Paymentcondition_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('supplier/Supplier_model', '', TRUE);
	}

	public function index() {
		if (!isLogged()) {
			redirect('login');
		}
		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			if (isset($_FILES['bankfile'])) {
				$valid_formats = array("xml");
				$importResults = array();

				if ($_FILES['bankfile']['error'] != 0) {
					$this->session->set_tempdata('err_message', 'Bestand niet ingelezen FOUTCODE: ' . $_FILES['bankfile']['error'], 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect('import');
				} else {
					
					$name = str_replace(" ", "_", $_FILES['bankfile']['name']);

					if (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
						$this->session->set_tempdata('err_message', "XML is niet succesvol toegevoegd, verkeerde extentie", 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
					} else {
						
						// File is a valid filetype

						$xml = simplexml_load_file($_FILES["bankfile"]["tmp_name"]);

						// echo $xml->BkToCstmrStmt->GrpHdr->MsgId . "</br >";

						foreach ($xml->BkToCstmrStmt->Stmt->Ntry as $entry) {

							$batch = false;
							$debtor = array();
							$customer = null;
							$predictedInvoices = array();
							$paymentDate = strtotime($entry->BookgDt->Dt);
							$remark = null;

							if ($entry->CdtDbtInd == "CRDT") {
								if (!$entry->NtryDtls->Btch) {
									$debtor = $entry->NtryDtls->TxDtls->RltdPties->Dbtr;
								} else {
									$batch = true;
								}
							} else {
								$debtor = $entry->NtryDtls->TxDtls->RltdPties->Cdtr;
							}

							if ($batch == false) {
								$remark = $entry->NtryDtls->TxDtls->RmtInf->Ustrd;
								if (empty($remark)) {
									$remark = $entry->NtryDtls->TxDtls->Refs->EndToEndId;
								}

								// Search customer or supplier invoices that are already linked to recieved remark.
								// echo "Search for customer invoices that are linked to: ".$remark.'.<br>';
								$invoice = $this->Import_model->searchInvoiceByRemark($remark, $businessId)->result();
								if (empty($invoice)) {
									// echo "No invoices found. Search for supplier invoices that are linked to: ".$remark.'.<br>';
									$invoice = $this->Import_model->searchInvoiceSByRemark($remark, $businessId)->result();
									if (!empty($invoice)) {
										// echo "Found (some) invoices.<br>";
										$customer = $this->Import_model->getSupplierByInvoiceId($invoice[0]->Id, $businessId)->row(); // We use the $customer variable also for suppliers.
									} else {
										// echo "No invoices found. Continue searching...<br>";
									}
								} else {
									// echo "Found (some) invoices.<br>";
									$customer = $this->Import_model->getCustomerByInvoiceId($invoice[0]->Id, $businessId)->row();
								}
								if (!empty($invoice)) {
									$predictedInvoices += $invoice;
								}

								// Search customer invoices by detected invoice numbers.
								$detected_invoicenumbers = preg_match_all("/\d{6}/", $remark, $invoiceResults);
								if (empty($invoice)) {
									// echo "Search for invoices by detected invoice numbers...<br>";
									if ($detected_invoicenumbers > 0) {
										// echo "Found invoice numbers inside string.<br>";
										foreach ($invoiceResults[0] as $invoiceResult) {
											$invoice = $this->Import_model->searchInvoice($invoiceResult, $businessId)->row();
											if ($invoice != null) {
												// echo "Invoice found with invoice number: ".$invoiceResult.'.<br>';
												$customer = $this->Import_model->getCustomerByInvoiceId($invoice->Id, $businessId)->row();
												$predictedInvoices[] = $invoice;
											} else {
												// echo "No invoices are found by invoice number ".$invoiceResult.".<br>";
											}
										}
									} else {
										// echo "No invoice numbers found. Continue searching...<br>";
									}
								}

								// Search supplier invoices by (part of) remark.
								if (empty($invoice) || $detected_invoicenumbers == 0) {
									// echo "Search for supplier invoice by the complete remark...<br>";
									$invoice = $this->Import_model->searchInvoiceSupplier($remark[0], $businessId)->result();
									if (empty($invoice)) {
										// echo "No invoices found, search for supplier invoices with the first and last 8 characters of the remark...<br>";
										$invoice = $this->Import_model->searchBetterInvoiceSupplier($remark[0], $businessId)->result();
									}
									if (!empty($invoice)) {
										// echo "Found (some) invoice(s).<br>";
										$customer = $this->Import_model->getSupplierByInvoiceId($invoice[0]->Id, $businessId)->row(); // We use the $customer variable also for suppliers.
										$predictedInvoices += $invoice;
									} else {
										// echo "Nothing found for: ".$remark[0]."<br>".$this->db->last_query().'<br>';
									}
								}
							}

							$importResult = (object) array(
										'PaymentDate' => $paymentDate,
										'ImportUser' => (object) array(
											'Name' => !empty($debtor) ? $debtor->Nm : ''
										),
										'PredictedCustomer' => $customer,
										'PredictedInvoices' => $predictedInvoices,
										'ImportPayment' => (object) array(
											'TotalIn' => $entry->Amt
										),
										'ImportPaymentRemark' => $remark,
										'Batch' => $batch
							);

							$importResults[] = $importResult;
						}
					}

					return $this->resultsBankfile($importResults);
				}
			} elseif (isset($_POST['confirmBank'])) {

				foreach ($_POST['invoiceNumbers'] as $number => $invoiceNumber) {
					if ($_POST['invoiceChecked'][$number] != 1) {
						continue;
					}

					$ruleNumber = $_POST['ruleNumbers'][$number];
					$paymentDate = $_POST['paymentDate'][$ruleNumber];
					$importPaymentRemark = $_POST['ImportPaymentRemark'][$ruleNumber];

					if ($_POST['customer_or_supplier'][$ruleNumber] == 'customer') {
						$invoice = $this->Import_model->getInvoice($_POST['id'][$number], $businessId)->row();
					} elseif ($_POST['customer_or_supplier'][$ruleNumber] == 'supplier') {
						$invoice = $this->Import_model->getInvoiceS($_POST['id'][$number], $businessId)->row();
					}

					if (!empty($invoice)) {
						$data = array();

						if ($invoice->PaymentDate) {
							echo $invoiceNumber . " is al betaald op " . date('d-m-Y', $invoice->PaymentDate);
						} else {
							echo $invoiceNumber . " betaald op " . date('d-m-Y', $paymentDate);
							$data['PaymentDate'] = $paymentDate;
						}

						if ($importPaymentRemark) {
							echo " en is gekoppeld aan " . $importPaymentRemark . "<br>";
							$data['ImportPaymentRemark'] = $importPaymentRemark;
						}

						if (!empty($data)) {
							if ($_POST['customer_or_supplier'][$ruleNumber] == 'customer') {
								$this->Customers_invoicesmodel->updateInvoce($invoice->Id, $data);
							} elseif ($_POST['customer_or_supplier'][$ruleNumber] == 'supplier') {
								$this->Import_model->updateInvoiceSupplier($invoice->Id, $data);
							}
						}
					} else {
						echo $invoiceNumber . " is niet gevonden.<br>";
					}

				}
			} elseif (isset($_FILES['stock'])) {
				$valid_formats = array("csv");
				$importResults = array();
				if ($_FILES['stock']['error'] != 0) {
					$this->session->set_tempdata('err_message', 'Bestand niet ingelezen FOUTCODE: ' . $_FILES['stock']['error'], 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect('import');
				} else {
					$name = str_replace(" ", "_", $_FILES['stock']['name']);

					if (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
						$this->session->set_tempdata('err_message', "CSV is niet succesvol toegevoegd, verkeerde extentie", 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
					} else {
						// File is a valid filetype
						$number = 0;
						$file = fopen($_FILES["stock"]["tmp_name"], 'r');
						$csvKey = array();
						while (($csv = fgetcsv($file, 0, ';')) !== FALSE) {
							if ($number == 0) {
								foreach ($csv as $key => $value) {
									$csvKey[$value] = $key;
								}
							}
							else{
								if (count($csv) > 0) {

									$product = $this->Product_model->getProductByArticleNumber($csv[1], $businessId)->row();

									$dataProduct = array(
										'Active'            => $csv[intval($csvKey["﻿PRODUCT_ACTIEF"])],
										'ArticleNumber'     => $csv[intval($csvKey['ARTIKELNUMMER'])],
										'EanCode'           => $csv[intval($csvKey['EAN_CODE'])],
										'Description'       => $csv[intval($csvKey['OMSCHRIJVING'])],
										'SupplierId'        => $csv[intval($csvKey['LEVERANVIER_ID'])],
										'ProductGroup'      => $csv[intval($csvKey['PRODUCTGROEP_ID'])],
										'PurchasePrice'     => str_replace(',', '.', $csv[intval($csvKey['INKOOPPRIJS_EX_BTW'])]),
										'SalesPrice'        => str_replace(',', '.', $csv[intval($csvKey['VERKOOPPRIJS_EX_BTW'])]),
										'BTW'               => $csv[intval($csvKey['BTW'])],
										'ProductKind'       => $csv[intval($csvKey['IS_VOORRAADPRODUCT'])] == 1 ? 1 : 0,
										'Type'              => $csv[intval($csvKey['SOORT_PRODUCT'])],
										'WarehouseLocation' => $csv[intval($csvKey['MAGAZIJNLOCATIE'])],
										'Warehouse'         => $csv[intval($csvKey['MAGAZIJN_ID'])],
										'Stock'             => $csv[intval($csvKey['TECHNISCHE_VOORRAAD'])],
										'BusinessId'        => $businessId
									);

									if ($product != null) {
										$this->Product_model->updateProduct($product->Id, $dataProduct);
									} else {
										$this->Product_model->createProduct($dataProduct);
									}
								}
							}

							$number ++;
						}
						fclose($file);
					}
				}
				$this->session->set_tempdata('err_message', 'De csv is succesvol geïmporteerd', 300);
				$this->session->set_tempdata('err_messagetype', 'success', 300);
				redirect('import');
			} elseif (isset($_FILES['blokker_salesorder_file'])) {
				$valid_formats = array("csv");
				$importResults = array();
				if ($_FILES['blokker_salesorder_file']['error'] != 0) {
					$this->session->set_tempdata('err_message', 'Bestand niet ingelezen FOUTCODE: ' . $_FILES['blokker_salesorder_file']['error'], 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect('import');
				} else {
					$name = str_replace(" ", "_", $_FILES['blokker_salesorder_file']['name']);

					if (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
						$this->session->set_tempdata('err_message', "CSV is niet succesvol toegevoegd, verkeerde extentie", 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
					} else {
						// File is a valid filetype
						$businessId = $this->session->userdata('user')->BusinessId;
						
						$number = 0;
						$file = fopen($_FILES["blokker_salesorder_file"]["tmp_name"], 'r');
						$csvKey = array();
						while (($csv = fgetcsv($file, 0, ';')) !== FALSE) {
							$sessionData[$number] = $csv;
							$number ++;
						}
						fclose($file);
						$this->session->set_userdata('salesorderdata', $sessionData);
						$this->session->set_userdata('importformat', 'blokker');
						redirect('import/acceptSalesOrder');
					}
				}
			} elseif (isset($_FILES['bolcom_salesorder_file'])) {
				$valid_formats = array("csv");
				$importResults = array();
				if ($_FILES['bolcom_salesorder_file']['error'] != 0) {
					$this->session->set_tempdata('err_message', 'Bestand niet ingelezen FOUTCODE: ' . $_FILES['bolcom_salesorder_file']['error'], 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect('import');
				} else {
					$name = str_replace(" ", "_", $_FILES['bolcom_salesorder_file']['name']);

					if (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
						$this->session->set_tempdata('err_message', "CSV is niet succesvol toegevoegd, verkeerde extentie", 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
					} else {
						// File is a valid filetype
						$businessId = $this->session->userdata('user')->BusinessId;
						
						$number = 0;
						$file = fopen($_FILES["bolcom_salesorder_file"]["tmp_name"], 'r');
						$csvKey = array();
						while (($csv = fgetcsv($file, 0, ';')) !== FALSE) {
							$sessionData[$number] = $csv;
							$number ++;
						}
						fclose($file);
						$this->session->set_userdata('salesorderdata', $sessionData);
						$this->session->set_userdata('importformat', 'bol.com');
						redirect('import/acceptSalesOrder');
					}
				}
			} elseif (isset($_FILES['general_salesorder_file'])) {
				$valid_formats = array("csv");
				$importResults = array();
				if ($_FILES['general_salesorder_file']['error'] != 0) {
					$this->session->set_tempdata('err_message', 'Bestand niet ingelezen FOUTCODE: ' . $_FILES['general_salesorder_file']['error'], 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect('import');
				} else {
					$name = str_replace(" ", "_", $_FILES['general_salesorder_file']['name']);

					if (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
						$this->session->set_tempdata('err_message', "CSV is niet succesvol toegevoegd, verkeerde extentie", 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
					} else {
						// File is a valid filetype
						$businessId = $this->session->userdata('user')->BusinessId;
						
						$number = 0;
						$file = fopen($_FILES["general_salesorder_file"]["tmp_name"], 'r');
						$csvKey = array();
						while (($csv = fgetcsv($file, 0, ';')) !== FALSE) {
							$sessionData[$number] = $csv;
							$number ++;
						}
						fclose($file);
						$this->session->set_userdata('salesorderdata', $sessionData);
						$this->session->set_userdata('importformat', 'general');
						redirect('import/acceptSalesOrder');
					}
				}
			}
		} else {
			$data = array();

			if ($this->session->tempdata('err_message')) {
				$data['tpl_msg'] = $this->session->tempdata('err_message');
				$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
				$this->session->unset_tempdata('err_message');
				$this->session->unset_tempdata('err_messagetype');
			}

			$this->load->view('import/default', $data);
		}
	}

	public function resultsBankfile($importResults = null) {
		if (!isLogged()) {
			redirect('login');
		}

		if ($importResults == null) {
			redirect('import/index');
		}
		
		$businessId = $this->session->userdata('user')->BusinessId;

		$data['importResults'] = $importResults;
		$data['customers'] = $this->Customers_model->getAll($businessId)->result();
		$data['suppliers'] = $this->Supplier_model->getAll($this->session->userdata('user')->BusinessId)->result();

		$this->load->view('import/resultBankfile', $data);
	}

	/**
	 * Function where the user can accept imported salesorders.
	 */
	public function acceptSalesOrder()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->session->userdata('salesorderdata') != NULL && $this->session->userdata('importformat') != NULL) {
			
			$totalEx        = 0;
			$totalIn        = 0;
			$totalTax21     = 0;
			$totalExTax21   = 0;
			$totalTax6      = 0;
			$totalExTax6    = 0;
			$totalExTax0    = 0;
			$totalIn21      = 0;
			$totalIn6       = 0;

			if ($this->input->server('REQUEST_METHOD') == 'POST') {
				
				if ($_POST['action'] == 'abort') {
					// Clear session data and go back to the import screen.
					$this->session->unset_userdata('salesorderdata');
					$this->session->unset_userdata('importformat');
					redirect('import');
				}
				elseif ($_POST['action'] == 'discard') {
					// Do nothing.
				}
				elseif ($_POST['action'] == 'save' || $_POST['action'] == 'save_and_print') {
					$orderNumber    = 'VKO' . $businessId . sprintf('%05d', getBusinessSalesOrderNumber($businessId) + 1);
					$orderdate 		= $_POST['orderdate'];
					$a = array();

					foreach ($_POST['number'] as $value) {
						$articlenumber 		= $_POST['articlenumber' . $value];
						$eanCode 			= $_POST['ean_code' . $value];
						$articledescription = $_POST['articledescription' . $value];
						$amount 			= $_POST['amount' . $value];

						$salesprice 		= $_POST['salesprice' . $value];
						$discount 			= $_POST['discount' . $value];

						$tax 				= $_POST['tax' . $value];
						$total 				= $_POST['total' . $value];

						switch ($tax) {
							case 21:
								$totalTax21 	= $totalTax21 + ($total * (21 / 100));
								$totalEx 		= $totalEx + $total;
								$totalExTax21 	= $totalExTax21 + $total;
								$totalIn 		= $totalIn + ($total * 1.21);
								$totalIn21 		= $totalIn21 + ($total * 1.21);
								break;
							case 9:
								$totalTax6 		= $totalTax6 + ($total * (9 / 100));
								$totalEx 		= $totalEx + $total;
								$totalExTax6 	= $totalExTax6 + $total;
								$totalIn 		= $totalIn + ($total * 1.09);
								$totalIn6 		= $totalIn6 + ($total * 1.09);
								break;
							default :
								$totalEx 		= $totalEx + $total;
								$totalExTax0 	= $totalExTax0 + $total;
								$totalIn 		= $totalIn + $total;
								break;
						}

						$dataRule = array(
							'OrderNumber' 		=> $orderNumber,
							'ArticleC' 			=> $articlenumber,
							'EanCode' 			=> $eanCode,
							'Amount' 			=> $amount,
							'Description' 		=> $articledescription,
							'Price' 			=> $salesprice,
							'Discount' 			=> $discount,
							'Tax' 				=> $tax,
							'Total' 			=> $total,
							'CustomerId' 		=> NULL,
							'BusinessId' 		=> $businessId
						);

						$salesOrderRuleId = $this->SalesOrder_model->insertSalesOrderRule($dataRule);
						$a[] = $salesOrderRuleId;
					}

					$dataSalesOrder = array(
						'OrderNumber' 			=> $orderNumber,
						'TotalEx' 				=> $totalEx,
						'TotalIn' 				=> $totalIn,
						'TotalTax21' 			=> $totalTax21,
						'TotalExTax21' 			=> $totalExTax21,
						'TotalTax6' 			=> $totalTax6,
						'TotalExTax6' 			=> $totalExTax6,
						'TotalExTax0' 			=> $totalExTax0,
						'TotalIn21' 			=> $totalIn21,
						'TotalIn6' 				=> $totalIn6,
						'OrderDate' 			=> date('Y-m-d', strtotime($orderdate)),
						'CustomerId' 			=> !isset($_POST['anonymous_customer']) ? $_POST['customer'] :  NULL,
						'CompanyName' 			=> isset($_POST['anonymous_customer']) ? $_POST['company_name'] : null,
						'FrontName' 			=> isset($_POST['anonymous_customer']) ? $_POST['front_name'] : null,
						'Insertion'				=> isset($_POST['anonymous_customer']) ? $_POST['insertion'] : null,
						'LastName' 				=> isset($_POST['anonymous_customer']) ? $_POST['last_name'] : null,
						'Address' 				=> isset($_POST['anonymous_customer']) ? $_POST['address'] : null,
						'AddressNumber' 		=> isset($_POST['anonymous_customer']) ? $_POST['address_number'] : null,
						'AddressAddition' 		=> isset($_POST['anonymous_customer']) ? $_POST['address_addition'] : null,
						'ZipCode' 				=> isset($_POST['anonymous_customer']) ? $_POST['zip_code'] : null,
						'City' 					=> isset($_POST['anonymous_customer']) ? $_POST['city'] : null,
						'Country' 				=> isset($_POST['anonymous_customer']) ? $_POST['country'] : null,
						'MailAddress' 			=> isset($_POST['anonymous_customer']) ? $_POST['mail_address'] : null,
						'PaymentCondition'		=> isset($_POST['anonymous_customer']) ? $_POST['paymentcondition'] : null,
						'TermOfPayment'			=> isset($_POST['anonymous_customer']) ? $_POST['termofpayment'] : null,
						'Seller_id' 			=> $_POST['seller'],
						'Transport_id' 			=> $_POST['transport'],
						'Reference' 			=> $_POST['reference'],
						'Colli' 				=> $_POST['colli'],
						'BusinessId' 			=> $businessId
					);

					$salesOrderId = $this->SalesOrder_model->insertSalesOrder($dataSalesOrder);

					$dataB = array(
						'SalesOrderNumber' => getBusinessSalesOrderNumber($businessId) + 1
					);
					$this->Business_model->updateBusiness($dataB, $businessId);

					foreach ($a as $salesOrderRuleId) {
						$data = array(
							'SalesOrderId' => $salesOrderId
						);
						$this->SalesOrder_model->updateSalesOrderRule($salesOrderRuleId, $data);
					}

					unset($dataRule);
					unset($dataSalesOrder);
					unset($data);

					$data['tpl_msg'] = 'De order is succesvol aangemaakt';
					$data['tpl_msgtype'] = 'success';
					$data['salesOrderId'] = $salesOrderId;
				}
				
			}
			
			if ($_SESSION['importformat'] == 'blokker') {
				
				// If a post request is performed, remove the row that has already been passed.
				if ($this->input->server('REQUEST_METHOD') == 'POST') {
					// Remove row from Session data.
					unset($_SESSION['salesorderdata'][$_POST['csvRow']]);
				}
				
				$salesorderdata = $this->session->salesorderdata;
				
				$anonymousCustomer = true;
				
				$defaultSeller = $this->Sellers_model->getByImport('blokker', $businessId)->row();
				if ($defaultSeller == NULL) {
					$this->session->set_tempdata('err_message', 'Er is geen verkoopkanaal toegewezen aan het import formaat voor Blokker', 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					$this->session->unset_userdata('salesorderdata');
					$this->session->unset_userdata('importformat');
					redirect('import');
				}
				
				$defaultTransporter = $this->Transporter_model->getImportByName('blokker', $businessId)->row();
				if ($defaultTransporter == NULL) {
					$this->session->set_tempdata('err_message', 'Er is geen vervoerder toegewezen aan het import formaat voor Blokker', 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					$this->session->unset_userdata('salesorderdata');
					$this->session->unset_userdata('importformat');
					redirect('import');
				}
				
				foreach (current($salesorderdata) as $key => $value) {
					$csvKey[$value] = $key;
				}
				
				$row = next($salesorderdata);
				$number = key($salesorderdata);
				
				if ($row === FALSE) {
					// The array is empty which means the loop through the CSV file is completed by the user.
					$this->session->unset_userdata('salesorderdata');
					$this->session->unset_userdata('importformat');
					if (isset($data['tpl_msg']) && isset($data['tpl_msgtype'])) {
						$this->session->set_tempdata('err_message', $data['tpl_msg'], 300);
						$this->session->set_tempdata('err_messagetype', $data['tpl_msgtype'], 300);
					}
					if ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['action'] == 'save_and_print') {
						$this->session->set_flashdata('printCsvSalesorder', $salesOrderId);
					}
					redirect('import');
				}
				
				$addressSplitted = split_street($row[intval($csvKey["Verzendadres: Straat (1)"])]);
				$lastnameSplitted = splitLastname($row[intval($csvKey["Verzendadres: Familienaam"])]);
				
				$eanCode 			= $row[intval($csvKey["Aanbieding SKU"])];
				$amount 			= $row[intval($csvKey["Aantal"])];
				$companyName 		= $row[intval($csvKey["Verzendadres: Bedrijfsnaam"])];
				$frontName 			= $row[intval($csvKey["Verzendadres: Voornaam"])];
				$insertion 			= $lastnameSplitted[0];
				$lastName 			= $lastnameSplitted[1];
				$address 			= $addressSplitted['street'];
				$addressNumber 		= $addressSplitted['number'];
				$addressAddition 	= $addressSplitted['numberAddition'] ?? NULL;
				$zipCode 			= $row[intval($csvKey["Verzendadres: Postcode"])];
				$city 				= $row[intval($csvKey["Verzendadres: Stad"])];
				$country 			= $row[intval($csvKey["Verzendadres: Land"])];
				$mailAddress 		= $row[intval($csvKey["email_customer"])];
				$reference			= $row[intval($csvKey["Bestelnummer"])];
				
				$product = $this->Product_model->getProductsSByEanCode($businessId, $eanCode)->row();
				
				if ($product != NULL) {
					$salesprice 		= $product->SalesPrice;
					$total 				= $salesprice * $amount;
				
					// This csv format provides 1 orderrule per order.
					$dataRule[] = (object) array(
						'ArticleC'      => $product->ArticleNumber,
						'EanCode'       => $product->EanCode,
						'Amount'        => $amount,
						'Description'   => $product->Description,
						'Price'         => $salesprice,
						'Discount'      => 0,
						'Tax'           => $product->BTW,
						'Total'         => $total,
						'CustomerId'    => NULL,
						'BusinessId'    => $businessId
					);
				}
				else{
					$dataRule = NULL;
				}
			} elseif ($_SESSION['importformat'] == 'bol.com') {
				
				// If a post request is performed, remove the row that has already been passed.
				if ($this->input->server('REQUEST_METHOD') == 'POST') {
					// Remove row from Session data.
					unset($_SESSION['salesorderdata'][$_POST['csvRow']]);
				}
				
				$salesorderdata = $this->session->salesorderdata;
				
				$anonymousCustomer = true;
				
				$defaultSeller = $this->Sellers_model->getByImport('bol.com', $businessId)->row();
				if ($defaultSeller == NULL) {
					$this->session->set_tempdata('err_message', 'Er is geen verkoopkanaal toegewezen aan het import formaat voor Bol.com', 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					$this->session->unset_userdata('salesorderdata');
					$this->session->unset_userdata('importformat');
					redirect('import');
				}
				
				$defaultTransporter = $this->Transporter_model->getImportByName('bol.com', $businessId)->row();
				if ($defaultTransporter == NULL) {
					$this->session->set_tempdata('err_message', 'Er is geen vervoerder toegewezen aan het import formaat voor Bol.com', 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					$this->session->unset_userdata('salesorderdata');
					$this->session->unset_userdata('importformat');
					redirect('import');
				}
				
				next($salesorderdata);
				next($salesorderdata);
				foreach (current($salesorderdata) as $key => $value) {
					$csvKey[$value] = $key;
				}
				$row = next($salesorderdata);
				$number = key($salesorderdata);
				
				if ($row === FALSE) {
					# The array is empty which means the loop through the CSV file is completed by the user.
					$this->session->unset_userdata('salesorderdata');
					$this->session->unset_userdata('importformat');
					if (isset($data['tpl_msg']) && isset($data['tpl_msgtype'])) {
						$this->session->set_tempdata('err_message', $data['tpl_msg'], 300);
						$this->session->set_tempdata('err_messagetype', $data['tpl_msgtype'], 300);
					}
					if ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['action'] == 'save_and_print') {
						$this->session->set_flashdata('printCsvSalesorder', $salesOrderId);
					}
					redirect('import');
				}
				
				$lastnameSplitted = splitLastname($row[intval($csvKey["achternaam_verzending"])]);
				
				$eanCode 			= $row[intval($csvKey["EAN"])];
				$amount 			= $row[intval($csvKey["aantal"])];
				$companyName 		= $row[intval($csvKey["bedrijfsnaam_verzending"])];
				$frontName 			= $row[intval($csvKey["voornaam_verzending"])];
				$insertion 			= $lastnameSplitted[0];
				$lastName 			= $lastnameSplitted[1];
				$address 			= $row[intval($csvKey["adres_verz_straat"])];
				$addressNumber 		= $row[intval($csvKey["adres_verz_huisnummer"])];
				$addressAddition 	= $row[intval($csvKey["adres_verz_huisnummer_toevoeging"])];
				$zipCode 			= $row[intval($csvKey["postcode_verzending"])];
				$city 				= $row[intval($csvKey["woonplaats_verzending"])];
				$country 			= $row[intval($csvKey["land_verzending"])];
				$mailAddress 		= $row[intval($csvKey["emailadres"])];
				$reference			= $row[intval($csvKey["bestelnummer"])];
				
				$product = $this->Product_model->getProductsSByEanCode($businessId, $eanCode)->row();
				
				if ($product != NULL) {
					$salesprice 		= $product->SalesPrice;
					$total 				= $salesprice * $amount;
				
					// This csv format provides 1 orderrule per order.
					$dataRule[] = (object) array(
						'ArticleC'      => $product->ArticleNumber,
						'EanCode'       => $product->EanCode,
						'Amount'        => $amount,
						'Description'   => $product->Description,
						'Price'         => $salesprice,
						'Discount'      => 0,
						'Tax'           => $product->BTW,
						'Total'         => $total,
						'CustomerId'    => NULL,
						'BusinessId'    => $businessId
					);
				}
				else{
					$dataRule = NULL;
				}
			}
			elseif ($_SESSION['importformat'] == 'general') {
				
				$salesorderdata = $this->session->salesorderdata;
				
				// We don't use this variable in this csv format but it needs to exist.
				$number = 0;
				
				if ($this->input->server('REQUEST_METHOD') == 'POST') {
					// A post request is perfomred. This means the import is finished because this csv format provides only 1 order.
					$this->session->unset_userdata('salesorderdata');
					$this->session->unset_userdata('importformat');
					if (isset($data['tpl_msg']) && isset($data['tpl_msgtype'])) {
						$this->session->set_tempdata('err_message', $data['tpl_msg'], 300);
						$this->session->set_tempdata('err_messagetype', $data['tpl_msgtype'], 300);
					}
					if ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['action'] == 'save_and_print') {
						$this->session->set_flashdata('printCsvSalesorder', $salesOrderId);
					}
					redirect('import');
				}
				
				$anonymousCustomer = false;
				
				$companyName 		= null;
				$frontName 			= null;
				$insertion 			= null;
				$lastName 			= null;
				$address 			= null;
				$addressNumber 		= null;
				$addressAddition 	= null;
				$zipCode 			= null;
				$city 				= null;
				$country 			= null;
				$mailAddress 		= null;
				$reference 			= null;
				
				if ($salesorderdata !== FALSE) {
					// Create an orderrule for each csv row.
					foreach ($salesorderdata as $salesorderdataKey => $salesorderdataRule) {
						
						// Skip the first row (the row with the headings).
						if ($salesorderdataKey == 0) {
							continue;
						}
						
						$product = $this->Product_model->getProductsSByEanCode($businessId, $salesorderdataRule[1])->row();
						
						if ($product != null) {
							$salesprice = $product->SalesPrice;
							$total = $salesprice * $amount;
						}
						
						$dataRule[] = (object) array(
							'ArticleC'      => $product != null ? $product->ArticleNumber : $salesorderdataRule[1],
							'EanCode'       => $product != null ? $product->EanCode : $salesorderdataRule[0],
							'Amount'        => $salesorderdataRule[3],
							'Description'   => $product != null ? $product->Description : $salesorderdataRule[2],
							'Price'         => $product != null ? $product->SalesPrice : 0,
							'Discount'      => 0,
							'Tax'           => $product != null ? $product->BTW : 21,
							'Total'         => isset($total) ? $total : 0,
							'CustomerId'    => NULL,
							'BusinessId'    => $businessId
						);
					}
				}
				else{
					$dataRule = null;
				}
				
			}

			$orderdate = date('Y-m-d');
			
			switch (utf8_encode($country)) {
				case 'Nederland': 	$country = 'NL'; break;
				case 'België': 		$country = 'BE'; break;
				default: break;
			}

			$dataSalesOrder = (object) array(
				'OrderDate' 		=> $orderdate,
				'CustomerId' 		=> NULL,
				'CompanyName' 		=> $companyName,
				'FrontName' 		=> $frontName,
				'Insertion' 		=> $insertion,
				'LastName' 			=> $lastName,
				'Address' 			=> $address,
				'AddressNumber' 	=> $addressNumber,
				'AddressAddition' 	=> $addressAddition,
				'ZipCode' 			=> $zipCode,
				'City' 				=> $city,
				'Country' 			=> $country,
				'MailAddress' 		=> $mailAddress,
				'PaymentCondition'	=> NULL,
				'TermOfPayment'		=> 14,
				'Seller_id' 		=> isset($defaultSeller) ? $defaultSeller->Seller_id : 0,
				'Transport_id' 		=> isset($defaultTransporter) ? $defaultTransporter->Transporter_id : 0,
				'Reference' 		=> $reference,
				'Colli' 			=> 1,
				'BusinessId' 		=> $businessId
			);

			$data['salesOrder'] = $dataSalesOrder;
			$data['salesOrderRules'] = $dataRule;
			$data['transporters'] = $this->Transporter_model->getAll($this->session->userdata('user')->BusinessId)->result();
			$data['sellers'] = $this->Sellers_model->getAll($this->session->userdata('user')->BusinessId)->result();
			$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();
			$data['customers'] = $this->Customers_model->getAll($businessId)->result();
			$data['ruleNumber'] = $number;
			$data['anonymousCustomer'] = $anonymousCustomer;
			
			$this->load->view('import/acceptSalesorders', $data);
		}
		else{
			redirect('import');
		}

	}

}
