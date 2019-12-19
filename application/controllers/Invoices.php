<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('Ticket');
		$this->load->helper('cookie');
		$this->load->helper('invoice');
		$this->load->helper('customfield');
		$this->load->library('session');
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('customers/Customers_invoicesmodel', '', TRUE);
		$this->load->model('customers/Customers_contactsmodel', '', TRUE);
		$this->load->model('supplier/Supplier_model', '', TRUE);
		$this->load->model('invoices/Invoice_model', '', TRUE);
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('paymentcondition/Paymentcondition_model', '', TRUE);
		$this->load->model('salesorders/SalesOrder_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
	}

	public function createinvoice() {
		if (!isLogged()) {
			redirect('login');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();
		
		$paymentConditions = $this->Paymentcondition_model->getAll($businessId)->result();
		if ($customer == NULL && $paymentConditions == NULL) {
			$this->session->set_tempdata('err_message', 'Er zijn geen betaalcondities toegevoegd. Voeg eerst een betaalconditie toe in de instellingen.', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$this->load->helper('Business');
			$this->db->trans_start();

			$a = array();

			$invoiceNumber = $businessId . sprintf('%05d', getBusinessInvoiceNumber($businessId) + 1);
			$invoiceDate = $_POST['invoicedate'];
			$paymentCondition = $customer != null ? $customer->PaymentCondition : $_POST['paymentcondition'];
			$termOfPayment = $_POST['termofpayment'];
			$expirationDate = strtotime("+ " . $termOfPayment . " day", strtotime($invoiceDate));
			$totalEx = 0;
			$totalIn = 0;
			$totalTax21 = 0;
			$totalExTax21 = 0;
			$totalTax6 = 0;
			$totalExTax6 = 0;
			$totalExTax0 = 0;
			$totalIn21 = 0;
			$totalIn6 = 0;

			foreach ($_POST['number'] as $value) {
				$articlenumber = $_POST['articlenumber' . $value];
				$articledescription = $_POST['articledescription' . $value];
				$amount = $_POST['amount' . $value];
				if ($_POST['calculatePurchase'] == 1) {
					$salesprice = $_POST['salesprice' . $value] / $amount;
					$discount = 0;
				} else {
					$salesprice = $_POST['salesprice' . $value];
					$discount = $_POST['discount' . $value];
				}

				$tax = $_POST['tax' . $value];
				$total = $_POST['total' . $value];

				switch ($tax) {
					case 21:
						$totalTax21 = $totalTax21 + ($total * (21 / 100));
						$totalEx = $totalEx + $total;
						$totalExTax21 = $totalExTax21 + $total;
						$totalIn = $totalIn + ($total * 1.21);
						$totalIn21 = $totalIn21 + ($total * 1.21);
						break;
					case 9:
						$totalTax6 = $totalTax6 + ($total * (9 / 100));
						$totalEx = $totalEx + $total;
						$totalExTax6 = $totalExTax6 + $total;
						$totalIn = $totalIn + ($total * 1.09);
						$totalIn6 = $totalIn6 + ($total * 1.09);
						break;
					default :
						$totalEx = $totalEx + $total;
						$totalExTax0 = $totalExTax0 + $total;
						$totalIn = $totalIn + $total;
						break;
				}

				$dataRule = array(
					'InvoiceNumber' => $invoiceNumber,
					'ArticleC' => $articlenumber,
					'Amount' => $amount,
					'Description' => $articledescription,
					'Price' => $salesprice,
					'Discount' => $discount,
					'Tax' => $tax,
					'Total' => $total,
					'CustomerId' => $customerId,
					'BusinessId' => $businessId
				);


				$ticketRuleId = $this->Invoice_model->insertInvoiceRule($dataRule);

				$a[] = $ticketRuleId;

				// Decrement product stock.
				$product = $this->Product_model->getProductByArticleNumber($articlenumber, $businessId)->row();
				if ($product->ProductKind == 1) { // Product is stock
					$dataProduct = array(
						'Stock' => $product->Stock - $amount
					);
					$this->Product_model->updateProduct($product->Id, $dataProduct);
				}
			}

			$dataInvoice = array(
				'InvoiceNumber' 	=> $invoiceNumber,
				'TotalEx' 			=> $totalEx,
				'TotalIn' 			=> $totalIn,
				'TotalTax21' 		=> $totalTax21,
				'TotalExTax21' 		=> $totalExTax21,
				'TotalTax6' 		=> $totalTax6,
				'TotalExTax6' 		=> $totalExTax6,
				'TotalExTax0' 		=> $totalExTax0,
				'TotalIn21' 		=> $totalIn21,
				'TotalIn6' 			=> $totalIn6,
				'InvoiceDate' 		=> strtotime($invoiceDate),
				'ExpirationDate' 	=> $expirationDate,
				'ShortDescription' 	=> $_POST['short_description'],
				'Description' 		=> $_POST['description'],
				'PaymentCondition' 	=> $paymentCondition,
				'TermOfPayment' 	=> $termOfPayment,
				'contact' 			=> $_POST['contact'] 					? $_POST['contact'] 			: null,
				'CustomerId' 		=> $customerId,
				'CompanyName' 		=> isset($_POST['company_name']) 		? $_POST['company_name'] 		: null,
				'FrontName' 		=> isset($_POST['front_name']) 			? $_POST['front_name'] 			: null,
				'Insertion'			=> isset($_POST['insertion']) 			? $_POST['insertion'] 			: null,
				'LastName' 			=> isset($_POST['last_name']) 			? $_POST['last_name'] 			: null,
				'Address' 			=> isset($_POST['address']) 			? $_POST['address'] 			: null,
				'AddressNumber' 	=> isset($_POST['address_number']) 		? $_POST['address_number'] 		: null,
				'AddressAddition' 	=> isset($_POST['address_addition']) 	? $_POST['address_addition'] 	: null,
				'ZipCode' 			=> isset($_POST['zip_code']) 			? $_POST['zip_code'] 			: null,
				'City' 				=> isset($_POST['city']) 				? $_POST['city'] 				: null,
				'Country' 			=> isset($_POST['country']) 			? $_POST['country'] 			: null,
				'MailAddress' 		=> isset($_POST['mail_address']) 		? $_POST['mail_address']		: null,
				'SentPerMail'		=> 0,		
				'BusinessId' 		=> $businessId
			);

			$invoiceId = $this->Invoice_model->insertInvoice($dataInvoice);

			$dataB = array(
				'InvoiceNumber' => getBusinessInvoiceNumber($businessId) + 1
			);

			$this->Invoice_model->updateInvoiceNumber($businessId, $dataB);

			foreach ($a as $ruleId) {
				$data = array(
					'InvoiceId' => $invoiceId
				);

				$this->Invoice_model->updateInvoiceRule($ruleId, $data);
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De factuur is succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($customer != null) {
				redirect('customers/invoices/' . $customerId);
			}
			else{
				redirect('invoices/anonymous');
			}
		} else {

			$contacts[0] = '';
			if ($customer != null) {
				$contacts = $contacts + getContactDropdown($customer->Id, $businessId)[0];
			}

			if ($this->session->userdata('user')->CustomerManagement != 1) {
				$data['readonly'] = 'readonly';
			} else {
				$data['readonly'] = '';
			}

			$data['customer'] = $customer;
			$data['contact'] = form_dropdown('contact', $contacts, '', CLASSDROPDOWN);
			$data['paymentConditions'] = $paymentConditions;

			$this->load->view('invoices/createinvoice', $data);
		}
	}

	public function createinvoiceS() {
		if (!isLogged()) {
			redirect('login');
		}

		$supplierId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = getBusiness($businessId);

		$supplier = $this->Supplier_model->getSupplier($supplierId, $businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->load->helper('Business');
			$this->db->trans_start();

			$invoiceNumber = str_replace(" ", "_", $_POST['invoicenumber']);
			$purchaseNumber = "IN" . $businessId . sprintf('%05d', $business->PurchaseNumber + 1);
			$invoiceDate = $_POST['invoicedate'];
			$paymentCondition = $supplier != null ? $supplier->PaymentCondition : $_POST['payment_condition'];
			$termOfPayment = $supplier != null ? $supplier->TermOfPayment : $_POST['term_of_payment'];
			$expirationDate = strtotime("+ " . $termOfPayment . " day", strtotime($invoiceDate));
			$totalEx = 0;
			$totalIn = 0;
			$totalTax21 = 0;
			$totalExTax21 = 0;
			$totalTax6 = 0;
			$totalExTax6 = 0;
			$totalExTax0 = 0;
			$totalIn21 = 0;
			$totalIn6 = 0;

			$a = array();

			foreach ($_POST['number'] as $value) {
				$articlenumber = $_POST['articlenumber' . $value];
				$articledescription = $_POST['articledescription' . $value];
				$amount = $_POST['amount' . $value];
				$purchaseprice = $_POST['purchaseprice' . $value];
				$discount = $_POST['discount' . $value];
				$tax = $_POST['tax' . $value];
				$total = $_POST['total' . $value];

				switch ($tax) {
					case 21:
						$totalTax21 = $totalTax21 + ($total * (21 / 100));
						$totalEx = $totalEx + $total;
						$totalExTax21 = $totalExTax21 + $total;
						$totalIn = $totalIn + ($total * 1.21);
						$totalIn21 = $totalIn21 + ($total * 1.21);
						break;
					case 9:
						$totalTax6 = $totalTax6 + ($total * (9 / 100));
						$totalEx = $totalEx + $total;
						$totalExTax6 = $totalExTax6 + $total;
						$totalIn = $totalIn + ($total * 1.09);
						$totalIn6 = $totalIn6 + ($total * 1.09);
						break;
					default :
						$totalEx = $totalEx + $total;
						$totalExTax0 = $totalExTax0 + $total;
						$totalIn = $totalIn + $total;
						break;
				}

				$dataRule = array(
					'InvoiceNumber' => $invoiceNumber,
					'ArticleC' => $articlenumber,
					'Amount' => $amount,
					'Description' => $articledescription,
					'Price' => $purchaseprice,
					'Discount' => $discount,
					'Tax' => $tax,
					'Total' => $total,
					'SupplierId' => $supplierId,
					'BusinessId' => $businessId
				);


				$ticketRuleId = $this->Invoice_model->insertInvoiceSupplierRule($dataRule);

				$a[] = $ticketRuleId;

				// Increment product stock.
				$product = $this->Product_model->getProductByArticleNumber($articlenumber, $businessId)->row();
				if ($product->ProductKind == 1) {
					$dataProduct = array(
						'Stock' => $product->Stock + $amount
					);
					$this->Product_model->updateProduct($product->Id, $dataProduct);
				}
			}

			$dataInvoice = array(
				'InvoiceNumber' 	=> $invoiceNumber,
				'PurchaseNumber' 	=> $purchaseNumber,
				'TotalEx' 			=> $totalEx,
				'TotalIn' 			=> $totalIn,
				'TotalTax21' 		=> $totalTax21,
				'TotalExTax21' 		=> $totalExTax21,
				'TotalTax6'			=> $totalTax6,
				'TotalExTax6' 		=> $totalExTax6,
				'TotalExTax0' 		=> $totalExTax0,
				'TotalIn21' 		=> $totalIn21,
				'TotalIn6' 			=> $totalIn6,
				'InvoiceDate' 		=> strtotime($invoiceDate),
				'ExpirationDate' 	=> $expirationDate,
				'Description' 		=> $_POST['description'],
				'PeriodDateFrom' 	=> strtotime($_POST['period_date_from']),
				'PeriodDateTo' 		=> strtotime($_POST['period_date_to']),
				'PaymentCondition' 	=> $paymentCondition,
				'TermOfPayment' 	=> $termOfPayment,
				'SupplierId' 		=> $supplierId,
				'CompanyName' 		=> isset($_POST['company_name']) 		? $_POST['company_name'] 		: null,
				'FrontName' 		=> isset($_POST['front_name']) 			? $_POST['front_name'] 			: null,
				'Insertion'			=> isset($_POST['insertion']) 			? $_POST['insertion'] 			: null,
				'LastName' 			=> isset($_POST['last_name']) 			? $_POST['last_name'] 			: null,
				'Address' 			=> isset($_POST['address']) 			? $_POST['address'] 			: null,
				'AddressNumber' 	=> isset($_POST['address_number']) 		? $_POST['address_number'] 		: null,
				'AddressAddition' 	=> isset($_POST['address_addition']) 	? $_POST['address_addition'] 	: null,
				'ZipCode' 			=> isset($_POST['zip_code']) 			? $_POST['zip_code'] 			: null,
				'City' 				=> isset($_POST['city']) 				? $_POST['city'] 				: null,
				'Country' 			=> isset($_POST['country']) 			? $_POST['country'] 			: null,
				'MailAddress' 		=> isset($_POST['mail_address']) 		? $_POST['mail_address'] 		: null,
				'BusinessId' 		=> $businessId
			);

			$invoiceId = $this->Invoice_model->insertInvoiceSupplier($dataInvoice);

			$dataB = array(
				'PurchaseNumber' => $business->PurchaseNumber + 1
			);

			$this->Invoice_model->updateInvoiceNumber($businessId, $dataB);

			foreach ($a as $ruleId) {
				$data = array(
					'InvoiceId' => $invoiceId
				);

				$this->Invoice_model->updateInvoiceSupplierRule($ruleId, $data);
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De factuur is succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($supplier != null) {
				redirect('supplier/invoices/' . $supplierId);
			}
			else{
				redirect('invoices/anonymousS');
			}
		} else {
			$contacts[0] = '';
			if ($supplier != null) {
				$contacts = $contacts + getContactSupplierDropdown($supplier->Id, $businessId);
			}

			$data['supplier'] = $supplier;
			$data['contact'] = form_dropdown('contact', $contacts, '', CLASSDROPDOWN);
			$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();

			$this->load->view('invoices/createinvoices', $data);
		}
	}

	public function editInvoice() {
		if (!isLogged()) {
			redirect('login');
		}

		$invoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();

		if ($invoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		$customer = $this->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();
		
		$paymentConditions = $this->Paymentcondition_model->getAll($businessId)->result();
		if ($customer == NULL && $paymentConditions == NULL) {
			$this->session->set_tempdata('err_message', 'Er zijn geen betaalcondities toegevoegd. Voeg eerst een betaalconditie toe in de instellingen.', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}

		if ($invoice->PaymentDate) {
			$this->session->set_tempdata('err_message', 'Deze factuur is al betaald', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/invoices/" . $invoice->CustomerId);
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$this->load->helper('Business');
			$this->db->trans_start();

			$invoiceDate = $_POST['invoicedate'];
			$paymentCondition = $customer != null ? $customer->PaymentCondition : $_POST['payment_condition'];
			$termOfPayment = $customer != null ? $customer->TermOfPayment : $_POST['term_of_payment'];
			$expirationDate = strtotime("+ " . $termOfPayment . " day", strtotime($invoiceDate));
			$totalEx = 0;
			$totalIn = 0;
			$totalTax21 = 0;
			$totalExTax21 = 0;
			$totalTax6 = 0;
			$totalExTax6 = 0;
			$totalExTax0 = 0;
			$totalIn21 = 0;
			$totalIn6 = 0;
			
			$ruleIds = array();

			foreach ($_POST['number'] as $value) {
				$articlenumber = $_POST['articlenumber' . $value];
				$articledescription = $_POST['articledescription' . $value];
				$amount = $_POST['amount' . $value];
				if ($_POST['calculatePurchase'] == 1) {
					$salesprice = $_POST['salesprice' . $value] / $amount;
					$discount = 0;
				} else {
					$salesprice = $_POST['salesprice' . $value];
					$discount = $_POST['discount' . $value];
				}

				$tax = $_POST['tax' . $value];
				$total = $_POST['total' . $value];

				switch ($tax) {
					case 21:
						$totalTax21 = $totalTax21 + ($total * (21 / 100));
						$totalEx = $totalEx + $total;
						$totalExTax21 = $totalExTax21 + $total;
						$totalIn = $totalIn + ($total * 1.21);
						$totalIn21 = $totalIn21 + ($total * 1.21);
						break;
					case 9:
						$totalTax6 = $totalTax6 + ($total * (9 / 100));
						$totalEx = $totalEx + $total;
						$totalExTax6 = $totalExTax6 + $total;
						$totalIn = $totalIn + ($total * 1.09);
						$totalIn6 = $totalIn6 + ($total * 1.09);
						break;
					default :
						$totalEx = $totalEx + $total;
						$totalExTax0 = $totalExTax0 + $total;
						$totalIn = $totalIn + $total;
						break;
				}

				$dataRule = array(
					'InvoiceId' => $invoiceId,
					'ArticleC' => $articlenumber,
					'Amount' => $amount,
					'Description' => $articledescription,
					'Price' => $salesprice,
					'Discount' => $discount,
					'Tax' => $tax,
					'Total' => $total
				);

				if (isset($_POST['ruleNumber' . $value])) {
					// Existing InvoiceRule
					$invoiceRuleId = $_POST['ruleNumber' . $value];
					$this->Invoice_model->updateInvoiceRule($invoiceRuleId, $dataRule);
				} else {
					// New InvoiceRule
					$dataRule['InvoiceNumber'] = $invoice->InvoiceNumber;
					$dataRule['CustomerId'] = $invoice->CustomerId;
					$dataRule['BusinessId'] = $businessId;
					$invoiceRuleId = $this->Invoice_model->insertInvoiceRule($dataRule);
				}
				
				$ruleIds[] = $invoiceRuleId;
			}
			
			// Check if invoice rules have to be deleted.
			$invoiceRules = $this->Invoice_model->getInvoiceRuleById($invoiceId, $businessId)->result();
			foreach ($invoiceRules as $rule) {
				if (!in_array($rule->Id, $ruleIds)) {
					$this->Invoice_model->deleteInvoiceRule($rule->Id);
				}
			}
			
			$dataInvoice = array(
				'TotalEx' 			=> $totalEx,
				'TotalIn' 			=> $totalIn,
				'TotalTax21' 		=> $totalTax21,
				'TotalExTax21' 		=> $totalExTax21,
				'TotalTax6' 		=> $totalTax6,
				'TotalExTax6' 		=> $totalExTax6,
				'TotalExTax0' 		=> $totalExTax0,
				'TotalIn21' 		=> $totalIn21,
				'TotalIn6' 			=> $totalIn6,
				'InvoiceDate' 		=> strtotime($invoiceDate),
				'ExpirationDate'	=> $expirationDate,
				'ShortDescription' 	=> $_POST['short_description'],
				'Description' 		=> $_POST['description'],
				'PaymentCondition'	=> $paymentCondition,
				'TermOfPayment'		=> $termOfPayment,
				'contact' 			=> isset($_POST['contact']) 			? $_POST['contact'] 			: null,
				'CompanyName' 		=> isset($_POST['company_name']) 		? $_POST['company_name'] 		: null,
				'FrontName' 		=> isset($_POST['front_name']) 			? $_POST['front_name'] 			: null,
				'Insertion'			=> isset($_POST['insertion']) 			? $_POST['insertion'] 			: null,
				'LastName' 			=> isset($_POST['last_name']) 			? $_POST['last_name'] 			: null,
				'Address' 			=> isset($_POST['address']) 			? $_POST['address'] 			: null,
				'AddressNumber' 	=> isset($_POST['address_number']) 		? $_POST['address_number'] 		: null,
				'AddressAddition' 	=> isset($_POST['address_addition']) 	? $_POST['address_addition'] 	: null,
				'ZipCode' 			=> isset($_POST['zip_code']) 			? $_POST['zip_code'] 			: null,
				'City' 				=> isset($_POST['city']) 				? $_POST['city'] 				: null,
				'Country' 			=> isset($_POST['country']) 			? $_POST['country'] 			: null,
				'MailAddress' 		=> isset($_POST['mail_address']) 		? $_POST['mail_address'] 		: null
			);

			$this->Invoice_model->updateInvoice($invoiceId, $dataInvoice);

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'Factuur is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($customer != null) {
				redirect('customers/invoices/' . $customer->Id);
			}
			else{
				redirect('invoices/anonymous');
			}
		} else {
			$invoiceRules = $this->Customers_invoicesmodel->getInvoiceRules($invoiceId, $businessId)->result();

			$contacts[0] = '';
			if ($customer != null) {
				$contacts = $contacts + getContactDropdown($customer->Id, $businessId)[0];
			}

			if ($this->session->userdata('user')->CustomerManagement != 1) {
				$data['readonly'] = 'readonly';
			} else {
				$data['readonly'] = '';
			}

			$data['invoice'] = $invoice;
			$data['invoiceRules'] = $invoiceRules;
			$data['customer'] = $customer;
			$data['contact'] = form_dropdown('contact', $contacts, $invoice->contact, CLASSDROPDOWN);
			$data['paymentConditions'] = $paymentConditions;
			$data['customFields'] = $this->Invoice_model->getCustomFields($invoiceId, $businessId)->result();
			
			$this->load->view('invoices/editinvoice', $data);
		}
	}
	
	public function editInvoiceS() {
		if (!isLogged()) {
			redirect('login');
		}

		$invoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$invoice = $this->Supplier_model->getInvoice($invoiceId, $businessId)->row();

		if ($invoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("supplier");
		}

		$supplier = $this->Supplier_model->getSupplier($invoice->SupplierId, $businessId)->row();

		if ($invoice->PaymentDate) {
			$this->session->set_tempdata('err_message', 'Deze factuur is al betaald', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/invoices/" . $invoice->CustomerId);
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$this->load->helper('Business');
			$this->db->trans_start();

			$invoiceNumber = str_replace(" ", "_", $_POST['invoicenumber']);
			$invoice->InvoiceNumber = $invoiceNumber;
			$invoiceDate = $_POST['invoicedate'];
			$paymentCondition = $supplier != null ? $supplier->PaymentCondition : $_POST['payment_condition'];
			$termOfPayment = $supplier != null ? $supplier->TermOfPayment : $_POST['term_of_payment'];
			$expirationDate = strtotime("+ " . $termOfPayment . " day", strtotime($invoiceDate));
			$totalEx = 0;
			$totalIn = 0;
			$totalTax21 = 0;
			$totalExTax21 = 0;
			$totalTax6 = 0;
			$totalExTax6 = 0;
			$totalExTax0 = 0;
			$totalIn21 = 0;
			$totalIn6 = 0;
			
			$ruleIds = array();
			
			foreach ($_POST['number'] as $value) {
				$articlenumber = $_POST['articlenumber' . $value];
				$articledescription = $_POST['articledescription' . $value];
				$amount = $_POST['amount' . $value];
				if ($_POST['calculatePurchase'] == 1) {
					$purchaseprice = $_POST['purchaseprice' . $value] / $amount;
					$discount = 0;
				} else {
					$purchaseprice = $_POST['purchaseprice' . $value];
					$discount = $_POST['discount' . $value];
				}

				$tax = $_POST['tax' . $value];
				$total = $_POST['total' . $value];

				switch ($tax) {
					case 21:
						$totalTax21 = $totalTax21 + ($total * (21 / 100));
						$totalEx = $totalEx + $total;
						$totalExTax21 = $totalExTax21 + $total;
						$totalIn = $totalIn + ($total * 1.21);
						$totalIn21 = $totalIn21 + ($total * 1.21);
						break;
					case 9:
						$totalTax6 = $totalTax6 + ($total * (9 / 100));
						$totalEx = $totalEx + $total;
						$totalExTax6 = $totalExTax6 + $total;
						$totalIn = $totalIn + ($total * 1.09);
						$totalIn6 = $totalIn6 + ($total * 1.09);
						break;
					default :
						$totalEx = $totalEx + $total;
						$totalExTax0 = $totalExTax0 + $total;
						$totalIn = $totalIn + $total;
						break;
				}

				$dataRule = array(
					'InvoiceId' => $invoiceId,
					'ArticleC' => $articlenumber,
					'Amount' => $amount,
					'Description' => $articledescription,
					'Price' => $purchaseprice,
					'Discount' => $discount,
					'Tax' => $tax,
					'Total' => $total
				);

				if (isset($_POST['ruleNumber' . $value])) {
					// Existing InvoiceRule
					$invoiceRuleId = $_POST['ruleNumber' . $value];
					$this->Invoice_model->updateInvoiceSupplierRule($invoiceRuleId, $dataRule);
				} else {
					// New InvoiceRule
					$dataRule['InvoiceNumber'] = $invoice->InvoiceNumber;
					$dataRule['SupplierId'] = $invoice->SupplierId;
					$dataRule['BusinessId'] = $businessId;
					$invoiceRuleId = $this->Invoice_model->insertInvoiceSupplierRule($dataRule);
				}
				
				$ruleIds[] = $invoiceRuleId;
			}
			
			// Check if invoice rules have to be deleted.
			$invoiceRules = $this->Invoice_model->getInvoiceSupplierRule($invoiceId, $businessId)->result();
			foreach ($invoiceRules as $rule) {
				if (!in_array($rule->Id, $ruleIds)) {
					$this->Invoice_model->deleteInvoiceSupplierRule($rule->Id);
				}
			}

			$dataInvoice = array(
				'InvoiceNumber' 	=> $invoiceNumber,
				'TotalEx' 			=> $totalEx,
				'TotalIn' 			=> $totalIn,
				'TotalTax21' 		=> $totalTax21,
				'TotalExTax21' 		=> $totalExTax21,
				'TotalTax6' 		=> $totalTax6,
				'TotalExTax6' 		=> $totalExTax6,
				'TotalExTax0' 		=> $totalExTax0,
				'TotalIn21' 		=> $totalIn21,
				'TotalIn6' 			=> $totalIn6,
				'InvoiceDate' 		=> strtotime($invoiceDate),
				'ExpirationDate' 	=> $expirationDate,
				'Description' 		=> $_POST['description'],
				'PeriodDateFrom' 	=> strtotime($_POST['period_date_from']),
				'PeriodDateTo' 		=> strtotime($_POST['period_date_to']),
				'PaymentCondition' 	=> $paymentCondition,
				'TermOfPayment' 	=> $termOfPayment,
				'CompanyName' 		=> isset($_POST['company_name']) 		? $_POST['company_name'] 		: null,
				'FrontName' 		=> isset($_POST['front_name']) 			? $_POST['front_name'] 			: null,
				'Insertion'			=> isset($_POST['insertion']) 			? $_POST['insertion'] 			: null,
				'LastName' 			=> isset($_POST['last_name']) 			? $_POST['last_name'] 			: null,
				'Address' 			=> isset($_POST['address']) 			? $_POST['address'] 			: null,
				'AddressNumber' 	=> isset($_POST['address_number']) 		? $_POST['address_number'] 		: null,
				'AddressAddition' 	=> isset($_POST['address_addition']) 	? $_POST['address_addition'] 	: null,
				'ZipCode' 			=> isset($_POST['zip_code']) 			? $_POST['zip_code'] 			: null,
				'City' 				=> isset($_POST['city']) 				? $_POST['city'] 				: null,
				'Country' 			=> isset($_POST['country']) 			? $_POST['country'] 			: null,
				'MailAddress' 		=> isset($_POST['mail_address']) 		? $_POST['mail_address'] 		: null
			);

			$this->Invoice_model->updateInvoiceSupplier($invoiceId, $dataInvoice);

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'Factuur is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($supplier != null) {
				redirect('supplier/invoices/' . $supplier->Id);
			}
			else{
				redirect('invoices/anonymousS');
			}
		} else {
			$invoiceRules = $this->Invoice_model->getInvoiceSupplierRule($invoiceId, $businessId)->result();

			$contacts[0] = '';
			if ($supplier != null) {
				$contacts = $contacts + getContactDropdown($supplier->Id, $businessId)[0];
			}

			$data['invoice'] = $invoice;
			$data['invoiceRules'] = $invoiceRules;
			$data['supplier'] = $supplier;
			$data['contact'] = form_dropdown('contact', $contacts, $invoice->ContactId, CLASSDROPDOWN);
			$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();

			$this->load->view('invoices/editinvoices', $data);
		}
	}

	public function anonymous()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->session->userdata('user')->Tab_CInvoice != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();
		
		if (isset($_GET['invoice_status']) && $_GET['invoice_status'] == 'closed') {
			$invoices = $this->Invoice_model->getClosedAnonymousInvoice($businessId)->result();
		}
		else{
			$invoices = $this->Invoice_model->getOpenAnonymousInvoice($businessId)->result();
		}
		
		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$data['invoices'] = $invoices;
		$this->load->view('invoices/anonymousinvoice', $data);
	}

	public function anonymousS()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->session->userdata('user')->Tab_CInvoice != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['invoiceFilter'] == 'closed') {
			$invoices = $this->Invoice_model->getClosedAnonymousInvoiceS($businessId)->result();
		}
		else{
			$invoices = $this->Invoice_model->getOpenAnonymousInvoiceS($businessId)->result();
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$data['invoices'] = $invoices;
		$this->load->view('invoices/anonymousinvoices', $data);
	}
	
	/**
	 * Convert anonymous customers for an invoice to a regular customer.
	 *
	 */
	public function setToRegular()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		$invoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();
		
		if ($invoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		if (!empty($invoice->CustomerId)) {
			$this->session->set_tempdata('err_message', 'Deze eenmalige klant is al omgezet naar een vaste klant', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		$paymentConditions = $this->Paymentcondition_model->getAll($businessId)->result();
		if ($paymentConditions == NULL) {
			$this->session->set_tempdata('err_message', 'Er zijn geen betaalcondities toegevoegd. Voeg eerst een betaalconditie toe in de instellingen.', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->db->trans_start();
			
			// Save customer
			$data = array(
				'Name' => $_POST['name'],
				'StreetName' => $_POST['streetname'],
				'StreetNumber' => $_POST['streetnumber'],
				'StreetAddition' => $_POST['streetaddition'],
				'ZipCode' => $_POST['zipcode'],
				'City' => $_POST['city'],
				'Country' => $_POST['country'],
				'IBAN' => $_POST['iban'],
				'BTW' => $_POST['btw'],
				'PhoneNumber' => $_POST['phonenumber'],
				'Fax' => $_POST['fax'],
				'Email' => $_POST['email'],
				'Website' => $_POST['website'],
				'TwitterProfile' => $_POST['twitterProfile'],
				'FacebookPage' => $_POST['facebookPage'],
				'VisitStreetName' => $_POST['visitstreetname'],
				'VisitStreetNumber' => 	$_POST['visitstreetnumber'],
				'VisitStreetAddition' => $_POST['visitstreetaddition'],
				'VisitZipCode' => $_POST['visitzipcode'],
				'VisitCity' => $_POST['visitcity'],
				'VisitCountry' => $_POST['visitcountry'],
				'PaymentCondition' => $_POST['paymentcondition'],
				'TermOfPayment' => $_POST['termofpayment'],
				'ToAttention' => $_POST['toattention'],
				'PhonenumberFinancial' => $_POST['phonenumberfinancial'],
				'EmailFinancial' => $_POST['emailfinancial'],
				'HeadCustomerId' => $_POST['headcustomerid'],
				'BusinessId' => $businessId
			);
			$customerId = $this->Customers_model->createCustomer($data);
			
			// Save contact
			$dataC = array(
				'FirstName' => $_POST['firstname'],
				'Insertion' => $_POST['insertion'],
				'LastName' => $_POST['lastname'],
				'Sex' => $_POST['sex'],
				'Salutation' => $_POST['salutation'],
				'Email' => $_POST['email'],
				'PhoneNumber' => $_POST['phonenumber'],
				'PhoneMobile' => $_POST['phonemobile'],
				'Function' => $_POST['function'],
				'Employed' => $_POST['employed'],
				'CustomerId' => $customerId,
				'BusinessId' => $businessId
			);
			$contactId = $this->Customers_contactsmodel->createContact($dataC);
			
			// Update invoice, Set the customer Id and empty anonymous customer values
			$dataI = array(
				'contact' => $contactId,
				'CustomerId' => $customerId,
				'CompanyName' => null,
				'FrontName' => null,
				'Insertion' => null,
				'LastName' => null,
				'Address' => null,
				'AddressNumber' => null,
				'AddressAddition' => null,
				'ZipCode' => null,
				'City' => null,
				'Country' => null,
				'MailAddress' => null
			);
			$this->Invoice_model->updateInvoice($invoiceId, $dataI);
			
			$this->db->trans_complete();
			
			$this->session->set_tempdata('err_message', 'De eenmalige klant is succesvol omgezet naar een vaste klant', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("customers/edit/$customerId");
		}
		
		// Predefined values from the invoice.
		$data['customer'] = (object) array(
			'Name' => !empty($invoice->CompanyName) ? $invoice->CompanyName : "$invoice->FrontName $invoice->Insertion $invoice->LastName",
			'StreetName' => $invoice->Address,
			'StreetNumber' => $invoice->AddressNumber,
			'StreetAddition' => $invoice->AddressAddition,
			'ZipCode' => $invoice->ZipCode,
			'City' => $invoice->City,
			'Country' => $invoice->Country,
			'PhoneNumber' => null,
			'Fax' => null,
			'Email' => $invoice->MailAddress,
			'Website' => null,
			'FacebookPage' => null,
			'TwitterProfile' => null,
			'VisitStreetName' => null,
			'VisitStreetNumber' => null,
			'VisitStreetAddition' => null,
			'VisitZipCode' => null,
			'VisitCity' => null,
			'VisitCountry' => null,
			'PaymentCondition' => $invoice->PaymentCondition,
			'TermOfPayment' => $invoice->TermOfPayment,
			'IBAN' => null,
			'BTW' => null,
			'ToAttention' => null,
			'EmailFinancial' => $invoice->MailAddress,
			'PhonenumberFinancial' => null,
			'Note' => null
		);
		$data['contact'] = (object) array(
			'FirstName' => $invoice->FrontName,
			'Insertion' => $invoice->Insertion,
			'LastName' => $invoice->LastName,
			'Sex' => null,
			'Salutation' => 'combined',
			'Email' => $invoice->MailAddress,
			'PhoneNumber' => null,
			'PhoneMobile' => null,
			'Function' => null,
			'Employed' => 1,
		);
		$data['paymentConditions'] = $paymentConditions;
		$data['customers'] = $this->Customers_model->getAll($businessId)->result();
		
		$this->load->view('invoices/editRegularCustomer', $data);
	}

	public function DeleteInvoiceS()
	{
		if (!isLogged()) {
			redirect('login');
		}

		$invoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$invoice = $this->Supplier_model->getInvoice($invoiceId, $businessId)->row();

		if ($invoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("supplier");
		}

		$supplier = $this->Supplier_model->getSupplier($invoice->SupplierId, $businessId)->row();
		$invoiceRules = $this->Invoice_model->getInvoiceSupplierRule($invoice->Id, $businessId)->result();

		// Delete all invoice rules from invoice.
		foreach ($invoiceRules as $invoiceRule) {
			$this->Invoice_model->deleteInvoiceSupplierRule($invoiceRule->Id);
		}

		// Delete invoice.
		$this->Invoice_model->deleteInvoiceSupplier($invoice->Id);

		$this->session->set_tempdata('err_message', 'De factuur met bijbehorende factuurregels is verwijderd', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		if ($supplier != null) {
			redirect('supplier/invoices/' . $supplier->Id);
		}
		else{
			redirect('invoices/anonymousS');
		}
	}
	
	public function sendReminder($invoiceId)
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->session->userdata('user')->Tab_CInvoice != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		
		// Check if the invoice exists.
		$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();
		if ($invoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		$business = $this->Business_model->getBusiness($businessId)->row();
		
		// Check if reminder text is not empty.
		if (!$business->ReminderText) {
			$this->session->set_tempdata('err_message', 'Er is geen tekst ingevuld vor de herinnering e-mail', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		// Check if a reminder pfd layout exists.
		if (!file_exists(APPPATH . 'views/pdf/' . $business->DirectoryPrefix . '/reminder.php')) {
			$this->session->set_tempdata('err_message', 'Er is geen herinnering layout gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		if ( sendReminder( array($invoiceId) ) ) {
			$this->session->set_tempdata('err_message', 'De herinnering is succesvol verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
		}
		else {
			$this->session->set_tempdata('err_message', 'De herinnering is niet verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
		}
		redirect("invoices/anonymous");
	}
	
	public function sendDunning($invoiceId)
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->session->userdata('user')->Tab_CInvoice != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		
		// Check if the invoice exists.
		$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();
		if ($invoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		$business = $this->Business_model->getBusiness($businessId)->row();
		
		// Check if dunning text is not empty.
		if (!$business->DunningText) {
			$this->session->set_tempdata('err_message', 'Er is geen tekst ingevuld vor de aanmaning e-mail', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		// Check if a reminder pfd layout exists.
		if (!file_exists(APPPATH . 'views/pdf/' . $business->DirectoryPrefix . '/dunning.php')) {
			$this->session->set_tempdata('err_message', 'Er is geen aanmaning layout gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		// Check if reminders are sent for this invoice.
		if (countInvoiceReminders($invoiceId, $businessId) == 0) {
			$this->session->set_tempdata('err_message', 'Er is geen aanmaning verstuurd omdat er voor de opgegeven factuur nog geen herinnering is verstuurd', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("invoices/anonymous");
		}
		
		if ( sendDunning( array($invoiceId) ) ) {
			$this->session->set_tempdata('err_message', 'De aanmaning is succesvol verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
		}
		else {
			$this->session->set_tempdata('err_message', 'De aanmaning is niet verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
		}
		redirect("invoices/anonymous");
	}

	public function search() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$data = array();

		if ($this->uri->segment(4) == 'invoice') {
			$data['invoices'] = $this->Invoice_model->getInvoiceByCustomerID($this->uri->segment(7), $businessId)->result();
		}
		elseif ($this->uri->segment(4) == 'invoices') {
			$data['invoices'] = $this->Invoice_model->getInvoiceSBySuplierID($this->uri->segment(7), $businessId)->result();
		}

		$this->load->view('invoices/search', $data);
	}

}
