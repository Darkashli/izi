<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SalesOrders extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('Ticket');
		$this->load->helper('cookie');
		$this->load->helper('Salesorder');
		$this->load->helper('Warehouse');
		$this->load->helper('customfield');
		$this->load->library('session');
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('sellers/Sellers_model', '', TRUE);
		$this->load->model('salesorders/SalesOrder_model', '', TRUE);
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('invoices/Invoice_model', '', TRUE);
		$this->load->model('paymentcondition/Paymentcondition_model', '', TRUE);
		$this->load->model('transporter/Transporter_model', '', TRUE);
	}

	public function createorder() {
		if (!isLogged()) {
			redirect('login');
		}

		$customerId 	= $this->uri->segment(3);
		$businessId 	= $this->session->userdata('user')->BusinessId;
		$customer 		= $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$this->load->helper('Business');
			$this->db->trans_start();

			$a = array();

			$orderNumber	= 'VKO' . $businessId . sprintf('%05d', getBusinessSalesOrderNumber($businessId) + 1);
			$orderdate 		= $_POST['orderdate'];
			$totalEx 		= 0;
			$totalIn 		= 0;
			$totalTax21 	= 0;
			$totalExTax21 	= 0;
			$totalTax6 		= 0;
			$totalExTax6 	= 0;
			$totalExTax0 	= 0;
			$totalIn21 		= 0;
			$totalIn6 		= 0;

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
					'CustomerId' 		=> $customerId,
					'BusinessId' 		=> $businessId
				);


				$ticketRuleId 			= $this->SalesOrder_model->insertSalesOrderRule($dataRule);

				$a[] 					= $ticketRuleId;
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
				'Note'		 			=> $_POST['note'],
				'CustomerId' 			=> $customerId,
				'CompanyName' 			=> isset($_POST['company_name']) 		? $_POST['company_name'] 		: null,
				'FrontName' 			=> isset($_POST['front_name']) 			? $_POST['front_name'] 			: null,
				'Insertion'				=> isset($_POST['insertion']) 			? $_POST['insertion'] 			: null,
				'LastName' 				=> isset($_POST['last_name']) 			? $_POST['last_name'] 			: null,
				'Address' 				=> isset($_POST['address']) 			? $_POST['address'] 			: null,
				'AddressNumber' 		=> isset($_POST['address_number']) 		? $_POST['address_number'] 		: null,
				'AddressAddition' 		=> isset($_POST['address_addition']) 	? $_POST['address_addition'] 	: null,
				'ZipCode' 				=> isset($_POST['zip_code']) 			? $_POST['zip_code'] 			: null,
				'City' 					=> isset($_POST['city']) 				? $_POST['city'] 				: null,
				'Country' 				=> isset($_POST['country']) 			? $_POST['country'] 			: null,
				'MailAddress' 			=> isset($_POST['mail_address']) 		? $_POST['mail_address'] 		: null,
				'PaymentCondition'		=> isset($_POST['paymentcondition']) 	? $_POST['paymentcondition'] 	: null,
				'TermOfPayment'			=> isset($_POST['termofpayment']) 		? $_POST['termofpayment'] 		: null,
				'Seller_id' 			=> isset($_POST['seller']) 				? $_POST['seller'] 				: null,
				'Transport_id' 			=> isset($_POST['transport']) 			? $_POST['transport'] 			: null,
				'Reference' 			=> isset($_POST['reference']) 			? $_POST['reference'] 			: null,
				'Colli' 				=> isset($_POST['colli']) 				? $_POST['colli'] 				: null,
				'BusinessId' 			=> $businessId
			);

			$SalesOrderId = $this->SalesOrder_model->insertSalesOrder($dataSalesOrder);

			$dataB = array(
				'SalesOrderNumber' => getBusinessSalesOrderNumber($businessId) + 1
			);
			$this->Business_model->updateBusiness($dataB, $businessId);

			foreach ($a as $SalesOrderRuleId) {
				$data = array(
					'SalesOrderId' => $SalesOrderId
				);

				$this->SalesOrder_model->updateSalesOrderRule($SalesOrderRuleId, $data);
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De order is succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($customer != null) {
				redirect('customers/SalesOrder/'.$customer->Id);
			}
			else{
				redirect('SalesOrders/listAnonymousOrders');
			}
		}
		else {
			$data['customer'] = $customer;
			$data['transporters'] = $this->Transporter_model->getAll($this->session->userdata('user')->BusinessId)->result();
			$data['sellers'] = $this->Sellers_model->getAll($this->session->userdata('user')->BusinessId)->result();
			$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();

			$this->load->view('salesorders/createsalesorder', $data);
		}
	}

	public function editOrder() {
		if (!isLogged()) {
			redirect('login');
		}

		$salesOrderId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$salesOrder = $this->SalesOrder_model->getSalesOrder($salesOrderId, $businessId)->row();

		if ($salesOrder == null) {
			$this->session->set_tempdata('err_message', 'Deze verkooporder bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		$customer = $this->Customers_model->getCustomer($salesOrder->CustomerId, $businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
			$this->load->helper('Business');
			$this->db->trans_start();

			$orderdate = $_POST['orderdate'];
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
					'ArticleC' 		=> $articlenumber,
					'EanCode' 		=> $eanCode,
					'Amount' 		=> $amount,
					'Description' 	=> $articledescription,
					'Price' 		=> $salesprice,
					'Discount' 		=> $discount,
					'Tax' 			=> $tax,
					'Total' 		=> $total
				);

				if (isset($_POST['ruleNumber' . $value])) {
					// Existing SalesOrderRule
					$salesOrderRuleId = $_POST['ruleNumber' . $value];
					$this->SalesOrder_model->updateSalesOrderRule($salesOrderRuleId, $dataRule);
				} else {
					// New SalesOrderRule
					$dataRule['SalesOrderId'] = $salesOrder->Id;
					$dataRule['OrderNumber'] = $salesOrder->OrderNumber;
					$dataRule['CustomerId'] = $salesOrder->CustomerId;
					$dataRule['BusinessId'] = $businessId;
					$salesOrderRuleId = $this->SalesOrder_model->insertSalesOrderRule($dataRule);
				}
				
				$ruleIds[] = $salesOrderRuleId;
			}
			
			// Check if salesorder rules have to be deleted.
			$salesOrderRules = $this->SalesOrder_model->getSalesOrderRules($salesOrderId, $businessId)->result();
			foreach ($salesOrderRules as $rule) {
				if (!in_array($rule->Id, $ruleIds)) {
					$this->SalesOrder_model->deleteSalesOrderRule($rule->Id);
				}
			}

			$dataSalesOrder = array(
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
				'Note'		 			=> $_POST['note'],
				'CompanyName' 			=> isset($_POST['company_name']) 		? $_POST['company_name'] 		: null,
				'FrontName' 			=> isset($_POST['front_name']) 			? $_POST['front_name'] 			: null,
				'Insertion' 			=> isset($_POST['insertion']) 			? $_POST['insertion'] 			: null,
				'LastName' 				=> isset($_POST['last_name']) 			? $_POST['last_name'] 			: null,
				'Address' 				=> isset($_POST['address']) 			? $_POST['address'] 			: null,
				'AddressNumber' 		=> isset($_POST['address_number']) 		? $_POST['address_number'] 		: null,
				'AddressAddition' 		=> isset($_POST['address_addition']) 	? $_POST['address_addition'] 	: null,
				'ZipCode' 				=> isset($_POST['zip_code']) 			? $_POST['zip_code'] 			: null,
				'City' 					=> isset($_POST['city']) 				? $_POST['city'] 				: null,
				'Country' 				=> isset($_POST['country']) 			? $_POST['country'] 			: null,
				'MailAddress' 			=> isset($_POST['mail_address']) 		? $_POST['mail_address'] 		: null,
				'PaymentCondition'		=> isset($_POST['paymentcondition']) 	? $_POST['paymentcondition'] 	: null,
				'TermOfPayment'			=> isset($_POST['termofpayment']) 		? $_POST['termofpayment'] 		: null,
				'Seller_id' 			=> isset($_POST['seller']) 				? $_POST['seller'] 				: null,
				'Transport_id' 			=> isset($_POST['transport']) 			? $_POST['transport'] 			: null,
				'Reference' 			=> isset($_POST['reference']) 			? $_POST['reference'] 			: null,
				'Colli' 				=> isset($_POST['colli']) 				? $_POST['colli'] 				: null
			);

			$this->SalesOrder_model->updateSalesOrder($salesOrderId, $dataSalesOrder);

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De order is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($customer != null) {
				redirect('customers/SalesOrder/'.$customer->Id);
			}
			else{
				redirect('SalesOrders/listAnonymousOrders');
			}
		} else {
			$data['salesOrder'] = $salesOrder;
			$data['salesOrderRules'] = $this->SalesOrder_model->getSalesOrderRules($salesOrderId, $businessId)->result();
			$data['customer'] = $customer;
			$data['transporters'] = $this->Transporter_model->getAll($this->session->userdata('user')->BusinessId)->result();
			$data['sellers'] = $this->Sellers_model->getAll($this->session->userdata('user')->BusinessId)->result();
			$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();
			$data['customFields'] = $this->SalesOrder_model->getCustomFields($salesOrderId, $businessId)->result();
			
			$this->load->view('salesorders/editsalesorder', $data);
		}
	}

	public function listAnonymousOrders(){
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}
		
		$year = $_GET['year'] ?? date('Y');
		$status = $_GET['status'] ?? 'open';

		$data['orders'] = $this->SalesOrder_model->getAnonymousOrders($businessId, $status, $year)->result();

		$this->load->view('salesorders/anonymoussalesorders', $data);
	}

	/**
	 * Convert order to invoice.
	 *
	 */
	public function convertToInvoice()
	{
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$salesOrders = array();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			foreach ($_POST['orders'] as $salesOrderId) {
				$salesOrders[] = $this->SalesOrder_model->getSalesOrder($salesOrderId, $businessId)->row();
			}
		}
		elseif ($this->uri->segment(3)) {
			$salesOrderId = $this->uri->segment(3);
			$salesOrders[] = $this->SalesOrder_model->getSalesOrder($salesOrderId, $businessId)->row();
		}

		if (empty($salesOrders)) {
			$this->session->set_tempdata('err_message', 'Er zijn geen verkooporders geselecteerd', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("dashboard");
		}

		$this->db->trans_start();
		foreach ($salesOrders as $salesOrder) {
			if ($salesOrder->CustomerId != 0) {
				$customer = $this->Customers_model->getCustomer($salesOrder->CustomerId, $businessId)->row();
			}
			else{
				$customer = null;
			}

			$salesOrderRules = $this->SalesOrder_model->getSalesOrderRules($salesOrder->Id, $businessId)->result();

			$TermOfPayment = $customer != null ? $customer->TermOfPayment : $salesOrder->TermOfPayment;
			$paymentCondition = $customer != null ? $customer->PaymentCondition : $salesOrder->PaymentCondition;
			$invoiceNumber = $businessId . sprintf('%05d', getBusinessInvoiceNumber($businessId) + 1);
			$invoiceDate = date('Y-m-d');
			$expirationDate = strtotime("+ " . $TermOfPayment . " day", strtotime($invoiceDate));

			foreach ($salesOrderRules as $salesOrderRule) {
				$dataR = array(
					'InvoiceNumber' => $invoiceNumber,
					'ArticleC' 		=> $salesOrderRule->ArticleC,
					'Amount' 		=> $salesOrderRule->Amount,
					'Description' 	=> $salesOrderRule->Description,
					'Price' 		=> $salesOrderRule->Price,
					'Discount' 		=> $salesOrderRule->Discount,
					'Tax' 			=> $salesOrderRule->Tax,
					'Total' 		=> $salesOrderRule->Total,
					'MetaData'		=> $salesOrderRule->MetaData,
					'CustomerId' 	=> $salesOrderRule->CustomerId,
					'BusinessId' 	=> $businessId,
				);

				$ticketRuleId = $this->Invoice_model->insertInvoiceRule($dataR);

				$a[] = $ticketRuleId;

				// Decrement product stock if the product exists.
				$product = $this->Product_model->getProductByArticleNumber($salesOrderRule->ArticleC, $businessId)->row();
				if ($product != NULL && $product->ProductKind == 1) {
					$dataProduct = array(
						'Stock' => $product->Stock - (int)$salesOrderRule->Amount
					);
					$this->Product_model->updateProduct($product->Id, $dataProduct);
				}
			}

			$dataInvoice = array(
				'InvoiceNumber' 	=> $invoiceNumber,
				'TotalEx' 			=> $salesOrder->TotalEx,
				'TotalIn' 			=> $salesOrder->TotalIn,
				'TotalTax21' 		=> $salesOrder->TotalTax21,
				'TotalExTax21' 		=> $salesOrder->TotalExTax21,
				'TotalTax6' 		=> $salesOrder->TotalTax6,
				'TotalExTax6' 		=> $salesOrder->TotalExTax6,
				'TotalExTax0' 		=> $salesOrder->TotalExTax0,
				'TotalIn21' 		=> $salesOrder->TotalIn21,
				'TotalIn6' 			=> $salesOrder->TotalIn6,
				'InvoiceDate' 		=> strtotime($invoiceDate),
				'Description'		=> $salesOrder->Note,
				'ExpirationDate' 	=> $expirationDate,
				'PaymentCondition' 	=> $paymentCondition,
				'TermOfPayment' 	=> $TermOfPayment,
				'CustomerId' 		=> $salesOrder->CustomerId,
				'CompanyName'		=> $customer != null ? $customer->Name 				: $salesOrder->CompanyName,
				'FrontName'			=> $customer != null ? null 						: $salesOrder->FrontName,
				'Insertion'			=> $customer != null ? null 						: $salesOrder->Insertion,
				'LastName'			=> $customer != null ? null 						: $salesOrder->LastName,
				'Address'			=> $customer != null ? $customer->StreetName 		: $salesOrder->Address,
				'AddressNumber'		=> $customer != null ? $customer->StreetNumber 		: $salesOrder->AddressNumber,
				'AddressAddition'	=> $customer != null ? $customer->StreetAddition 	: $salesOrder->AddressAddition,
				'ZipCode'			=> $customer != null ? $customer->ZipCode 			: $salesOrder->ZipCode,
				'City'				=> $customer != null ? $customer->City 				: $salesOrder->City,
				'Country'			=> $customer != null ? $customer->Country 			: $salesOrder->Country,
				'MailAddress'		=> $customer != null ? $customer->Email 			: $salesOrder->MailAddress,
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
			
			// Add custom fields to the invoice.
			$customFields = $this->SalesOrder_model->getCustomFields($salesOrder->Id, $businessId)->result();
			foreach ($customFields as $customField) {
				$dataC = array(
					'InvoiceId' => $invoiceId,
					'Key' => $customField->Key,
					'Value' => $customField->Value,
					'BusinessId' => $businessId
				);
				$this->Invoice_model->createCustomField($dataC);
			}
			
			$dataSalesOrder = array(
				'invoiced' => 1
			);
			$this->SalesOrder_model->updateSalesOrder($salesOrder->Id, $dataSalesOrder);

		}
		$this->db->trans_complete();

		$this->session->set_tempdata('err_message', 'De verkooporder(s) is/zijn succesvol omgezet naar een verkoopfactuur', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		if ($customer != null) {
			redirect('customers/invoices/'.$customer->Id);
		}
		else{
			redirect('invoices/anonymous');
		}

	}

	public function salesOrderPDF() {
		if (!isLogged()) {
			redirect('login');
		}

		$salesOrderId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		$salesOrder = $this->SalesOrder_model->getSalesOrder($salesOrderId, $businessId)->row();

		if ($salesOrder == null) {
			$this->session->set_tempdata('err_message', 'Deze verkooporder bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('customers/index/salesorder/');
		}

		if ($salesOrder->CustomerId != 0) {
			$customer = $this->Customers_model->getCustomer($salesOrder->CustomerId, $businessId)->row();
		}
		else{
			$customer = null;
		}

		$concerns = '';

		if (!file_exists(APPPATH . "views/pdf/".$business->DirectoryPrefix."/salesorder.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen pakbon layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('customers/index/salesorder/');
		}
		
		// Give the order the status "printed".
		$dataSalesOrder = array(
			'Printed' => 1
		);
		$this->SalesOrder_model->updateSalesOrder($salesOrder->Id, $dataSalesOrder);

		if ($salesOrder->CustomerId != 0 && getHeadCustomerId($salesOrder->CustomerId) != 0) {
			$concerns = 'Betreft: ' . $customer->Name;
			$customer = $this->Customers_model->getCustomer(getHeadCustomerId($salesOrder->CustomerId), $businessId)->row();
		}

		$salesOrderRules = $this->SalesOrder_model->getSalesOrderRules($salesOrder->Id, $businessId)->result();

		foreach ($salesOrderRules as  $key => $salesOrderRule) {
			$product = getProductbyArticleNumber($salesOrderRule->ArticleC);
			$salesOrderRules[$key]->WarehouseLocation  = $product != null ? $product->WarehouseLocation            : null;
			$salesOrderRules[$key]->WarehouseName      = $product != null ? getWarehouseName($product->Warehouse)  : null;
		}

		// Sort array by warhouselocation.
		usort($salesOrderRules, 'sortByWarehouseLocation');

		
		$data['business'] = $this->Business_model->getBusiness($businessId)->row();
		$data['salesOrder'] = $salesOrder;
		$data['salesOrderRules'] = $salesOrderRules;
		$data['customer'] = $customer;
		$data['concerns'] = $concerns;
		
		// $this->load->view('pdf/'.$business->DirectoryPrefix.'/salesorder', $data);

		unset($this->dompdf_lib);
		
		$this->load->library('Dompdf_lib');
		
		$this->dompdf_lib->load_view('pdf/'.$business->DirectoryPrefix.'/salesorder', $data);
		$this->dompdf_lib->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib->set_option('isRemoteEnabled', true);
		$this->dompdf_lib->setPaper('A4', 'portrait');
		$this->dompdf_lib->render();
		
		$this->dompdf_lib->stream('Pakbon_' . $salesOrder->OrderNumber);
	}

	public function salesOrderCSV(){
		if (!isLogged()) {
			redirect('login');
		}

		$salesOrderId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$salesOrder = $this->SalesOrder_model->getSalesOrder($salesOrderId, $businessId)->row();

		if ($salesOrder == null) {
			$this->session->set_tempdata('err_message', 'Deze verkooporder bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('customers/index/salesorder/');
		}

		if ($salesOrder->CustomerId != 0) {
			$customer = $this->Customers_model->getCustomer($salesOrder->CustomerId, $businessId)->row();

			$clientId = $customer->Id;
			$clientName = $customer->Name;
			$clientAddressStreet = $customer->StreetName.' '.$customer->StreetNumber.$customer->StreetAddition;
			$clientAddressCode = $customer->ZipCode;
			$clientAddressCity = $customer->City;
			$clientCountry = $customer->Country;
		}
		else{
			$clientId = null;
			$clientName = $salesOrder->CompanyName != null ? $salesOrder->CompanyName : $salesOrder->FrontName.' '.$salesOrder->Insertion.' '.$salesOrder->LastName;
			$clientAddressStreet = $salesOrder->Address.' '.$salesOrder->AddressNumber.$salesOrder->AddressAddition;
			$clientAddressCode = $salesOrder->ZipCode;
			$clientAddressCity = $salesOrder->City;
			$clientCountry = $salesOrder->Country;
		}

		$dhlNumber = '6004667'; //static
		$dhlName = 'Ecobility Benelux BV'; //static
		$empty = ''; //static
		$addressStreet = 'Industrieweg 4'; //static
		$addressCity = 'Waalwijk'; //static
		$addressCode = '5145 PV'; //static
		$phone = '0416-672550'; //static
		$country = 'NL'; //static
		$alwaysA = 'A'; //static
		$colli = $salesOrder->Colli;
		$weight = '8';
		$reference = $salesOrder->Reference;

		$fileName = $salesOrder->OrderNumber.'.csv';

		//Set the Content-Type and Content-Disposition headers.
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment; filename="' . $fileName . '"');

		//A multi-dimensional array containing our CSV data.
		$data = array(
			//Our data
			array(
				$dhlNumber,
				$dhlName,
				$empty,
				$addressStreet,
				$addressCity,
				$addressCode,
				$phone,
				$dhlNumber,
				$country,
				$clientId,
				$clientName,
				$clientAddressStreet,
				$clientAddressCode,
				$clientAddressCity,
				$empty,
				$clientCountry,
				$alwaysA,
				$colli,
				$weight,
				$reference
			)
		);

		//Open up a PHP output stream using the function fopen.
		$fp = fopen('php://output', 'w');

		//Loop through the array containing our CSV data.
		foreach ($data as $row) {
			//fputcsv formats the array into a CSV format.
			//It then writes the result to our output stream.
			fputcsv($fp, $row, ';');
		}

		//Close the file handle.
		fclose($fp);
	}
}
