<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseOrders extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('Ticket');
		$this->load->helper('cookie');
		$this->load->helper('account');
		$this->load->helper('salesorder');
		$this->load->helper('Warehouse');
		$this->load->library('session');
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('supplier/Supplier_model', '', TRUE);
		$this->load->model('purchaseorders/PurchaseOrder_model', '', TRUE);
		$this->load->model('invoices/Invoice_model', '', TRUE);
		$this->load->model('paymentcondition/Paymentcondition_model', '', TRUE);
	}

	public function createorder() {
		if (!isLogged()) {
			redirect('login');
		}

		$supplierId 	= $this->uri->segment(3);
		$businessId 	= $this->session->userdata('user')->BusinessId;
		$supplier 		= $this->Supplier_model->getSupplier($supplierId, $businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$this->load->helper('Business');
			$this->db->trans_start();

			$a = array();

			$orderNumber	= 'IKO' . $businessId . sprintf('%05d', getBusinessPurchaseOrderNumber($businessId) + 1);
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
				
				$purchaseprice 		= $_POST['purchaseprice' . $value];
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
					'Price' 			=> $purchaseprice,
					'Discount' 			=> $discount,
					'Tax' 				=> $tax,
					'Total' 			=> $total,
					'SupplierId' 		=> $supplierId,
					'BusinessId' 		=> $businessId
				);


				$PurchaseOrderRuleId 	= $this->PurchaseOrder_model->insertPurchaseOrderRule($dataRule);

				$a[] 					= $PurchaseOrderRuleId;
			}

			$dataPurchaseOrder = array(
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
				'SupplierId' 			=> $supplierId,
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
				'Reference' 			=> isset($_POST['reference']) 			? $_POST['reference'] 			: null,
				'BusinessId' 			=> $businessId
			);

			$PurchaseOrderId = $this->PurchaseOrder_model->insertPurchaseOrder($dataPurchaseOrder);

			$dataB = array(
				'PurchaseOrderNumber' => getBusinessPurchaseOrderNumber($businessId) + 1
			);

			$this->Business_model->updateBusiness($dataB, $businessId);

			foreach ($a as $PurchaseOrderRuleId) {
				$data = array(
					'PurchaseOrderId' => $PurchaseOrderId
				);

				$this->PurchaseOrder_model->updatePurchaseOrderRule($PurchaseOrderRuleId, $data);
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De order is succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($supplierId != null) {
				redirect('supplier/purchaseorders/'.$supplierId);
			}
			else{
				redirect('PurchaseOrders/listAnonymousOrders');
			}
		}
		else {
			
			$data['supplier'] = $supplier;

			$this->load->view('purchaseorders/createpurchaseorder', $data);
		}
	}

	public function editOrder() {
		if (!isLogged()) {
			redirect('login');
		}

		$purchaseOrderId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$purchaseOrder = $this->PurchaseOrder_model->getPurchaseOrder($purchaseOrderId, $businessId)->row();

		if ($purchaseOrder == null) {
			$this->session->set_tempdata('err_message', 'Deze inkooporder bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("dashboard");
		}

		$supplier = $this->Supplier_model->getSupplier($purchaseOrder->SupplierId, $businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$this->load->helper('Business');
			$this->db->trans_start();

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
			
			$ruleIds = array();
			
			foreach ($_POST['number'] as $value) {
				$articlenumber 		= $_POST['articlenumber' . $value];
				$eanCode 			= $_POST['ean_code' . $value];
				$articledescription = $_POST['articledescription' . $value];
				$amount 			= $_POST['amount' . $value];

				$purchaseprice 		= $_POST['purchaseprice' . $value];
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
					'Price' 		=> $purchaseprice,
					'Discount' 		=> $discount,
					'Tax' 			=> $tax,
					'Total' 		=> $total
				);

				if (isset($_POST['ruleNumber' . $value])) {
					// Existing OrderRule
					$purchaseOrderRuleId = $_POST['ruleNumber' . $value];
					$this->PurchaseOrder_model->updatePurchaseOrderRule($purchaseOrderRuleId, $dataRule);
				} else {
					// New OrderRule
					$dataRule['PurchaseOrderId'] 	= $purchaseOrder->Id;
					$dataRule['OrderNumber'] 		= $purchaseOrder->OrderNumber;
					$dataRule['SupplierId'] 		= $purchaseOrder->SupplierId;
					$dataRule['BusinessId'] 		= $businessId;
					$purchaseOrderRuleId 			= $this->PurchaseOrder_model->insertPurchaseOrderRule($dataRule);
				}
				
				$ruleIds[] = $purchaseOrderRuleId;
			}
			
			// Check if purchaseorder rules have to be deleted.
			$purchaseOrderRules = $this->PurchaseOrder_model->getPurchaseOrderRules($purchaseOrderId, $businessId)->result();
			foreach ($purchaseOrderRules as $rule) {
				if (!in_array($rule->Id, $ruleIds)) {
					$this->PurchaseOrder_model->deletePurchaseOrderRule($rule->Id);
				}
			}
			
			$dataPurchaseOrder = array(
				'TotalEx' 			=> $totalEx,
				'TotalIn' 			=> $totalIn,
				'TotalTax21' 		=> $totalTax21,
				'TotalExTax21' 		=> $totalExTax21,
				'TotalTax6' 		=> $totalTax6,
				'TotalExTax6' 		=> $totalExTax6,
				'TotalExTax0' 		=> $totalExTax0,
				'TotalIn21' 		=> $totalIn21,
				'TotalIn6' 			=> $totalIn6,
				'OrderDate' 		=> date('Y-m-d', strtotime($orderdate)),
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
				'Reference' 		=> isset($_POST['reference']) 			? $_POST['reference'] 			: null,
			);

			$this->PurchaseOrder_model->updatePurchaseOrder($purchaseOrderId, $dataPurchaseOrder);

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De order is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($supplier != null) {
				redirect('supplier/purchaseorders/'.$supplier->Id);
			}
			else{
				redirect('PurchaseOrders/listanonymousorders');
			}
		} else {
			$data['purchaseOrder'] = $purchaseOrder;
			$data['purchaseOrderRules'] = $this->PurchaseOrder_model->getPurchaseOrderRules($purchaseOrderId, $businessId)->result();
			$data['supplier'] = $supplier;

			$this->load->view('purchaseorders/editpurchaseorder', $data);
		}
	}

	public function listAnonymousOrders(){
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		
		$year = $_GET['year'] ?? date('Y');
		$status = $_GET['status'] ?? 'open';

		$data['orders'] = $this->PurchaseOrder_model->getAnonymousOrders($businessId)->result();

		$this->load->view('purchaseorders/anonymousorders', $data);
	}

	public function promotepurchaseorder()
	{
		if (!isLogged()) {
			redirect('login');
		}

		$purchaseOrderId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = getBusiness($businessId);

		$purchaseOrder = $this->PurchaseOrder_model->getPurchaseOrder($purchaseOrderId, $businessId)->row();

		if ($purchaseOrder == null) {
			$this->session->set_tempdata('err_message', 'Deze inkooporder bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("supplier");
		}

		if ($purchaseOrder->SupplierId != 0) {
			$supplier = $this->Supplier_model->getSupplier($purchaseOrder->SupplierId, $businessId)->row();
		}
		else{
			$supplier = null;
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['invoice_number'])) {
			$purchaseOrderRules = $this->PurchaseOrder_model->getPurchaseOrderRules($purchaseOrderId, $businessId)->result();

			$this->db->trans_start();

			$invoiceNumber = str_replace(" ", "_", $_POST['invoice_number']);
			$purchaseNumber = "IN" . $businessId . sprintf('%05d', $business->PurchaseNumber + 1);
			$invoiceDate = date('Y-m-d');
			$TermOfPayment = $supplier != null ? $supplier->TermOfPayment : $_POST['termofpayment'];
			// $paymentCondition = $supplier != null ? $supplier->PaymentCondition : $this->session->flashdata('paymentcondition');
			$expirationDate = strtotime("+ " . $TermOfPayment . " day", strtotime($invoiceDate));

			$a = array();

			foreach ($purchaseOrderRules as $purchaseOrderRule) {
				$dataR = array(
					'InvoiceNumber' => $invoiceNumber,
					'ArticleC' 		=> $purchaseOrderRule->ArticleC,
					'Amount' 		=> $purchaseOrderRule->Amount,
					'Description' 	=> $purchaseOrderRule->Description,
					'Price' 		=> $purchaseOrderRule->Price,
					'Discount' 		=> $purchaseOrderRule->Discount,
					'Tax' 			=> $purchaseOrderRule->Tax,
					'Total' 		=> $purchaseOrderRule->Total,
					'SupplierId' 	=> $purchaseOrderRule->SupplierId,
					'BusinessId' 	=> $businessId,
				);

				$ticketRuleId = $this->Invoice_model->insertInvoiceSupplierRule($dataR);

				$a[] = $ticketRuleId;

				// Increment product stock.
				$product = $this->Product_model->getProductByArticleNumber($purchaseOrderRule->ArticleC, $businessId)->row();
				if ($product->ProductKind == 1) {
					$dataProduct = array(
						'Stock' => $product->Stock + (int)$purchaseOrderRule->Amount
					);
					$this->Product_model->updateProduct($product->Id, $dataProduct);
				}
			}

			$dataInvoice = array(
				'InvoiceNumber' 	=> $invoiceNumber,
				'PurchaseNumber' 	=> $purchaseNumber,
				'TotalEx' 			=> $purchaseOrder->TotalEx,
				'TotalIn' 			=> $purchaseOrder->TotalIn,
				'TotalTax21' 		=> $purchaseOrder->TotalTax21,
				'TotalExTax21' 		=> $purchaseOrder->TotalExTax21,
				'TotalTax6' 		=> $purchaseOrder->TotalTax6,
				'TotalExTax6' 		=> $purchaseOrder->TotalExTax6,
				'TotalExTax0' 		=> $purchaseOrder->TotalExTax0,
				'TotalIn21' 		=> $purchaseOrder->TotalIn21,
				'TotalIn6' 			=> $purchaseOrder->TotalIn6,
				'InvoiceDate' 		=> strtotime($invoiceDate),
				'ExpirationDate' 	=> $expirationDate,
				'SupplierId' 		=> $purchaseOrder->SupplierId,
				'CompanyName'		=> $supplier != null ? $supplier->Name 				: $purchaseOrder->CompanyName,
				'FrontName'			=> $supplier != null ? null 						: $purchaseOrder->FrontName,
				'Insertion'			=> $supplier != null ? null 						: $purchaseOrder->Insertion,
				'LastName'			=> $supplier != null ? null 						: $purchaseOrder->LastName,
				'Address'			=> $supplier != null ? $supplier->StreetName 		: $purchaseOrder->Address,
				'AddressNumber'		=> $supplier != null ? $supplier->StreetNumber 		: $purchaseOrder->AddressNumber,
				'AddressAddition'	=> $supplier != null ? $supplier->StreetAddition 	: $purchaseOrder->AddressAddition,
				'ZipCode'			=> $supplier != null ? $supplier->ZipCode 			: $purchaseOrder->ZipCode,
				'City'				=> $supplier != null ? $supplier->City 				: $purchaseOrder->City,
				'Country'			=> $supplier != null ? $supplier->Country 			: $purchaseOrder->Country,
				'MailAddress'		=> $supplier != null ? $supplier->MailAddress 		: $purchaseOrder->MailAddress,
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

			$dataPurchaseOrder = array(
				'invoiced' => 1
			);
			$this->PurchaseOrder_model->updatePurchaseOrder($purchaseOrderId, $dataPurchaseOrder);

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De inkooporder is succesvol omgezet naar een inkoopfactuur', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($purchaseOrder->SupplierId != null) {
				redirect('supplier/invoices/'.$purchaseOrder->SupplierId);
			}
			else{
				redirect('PurchaseOrders/listAnonymousOrders');
			}
		}
		else{
			$data['purchaseOrder'] = $purchaseOrder;
			$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();
			$this->load->view('purchaseorders/promotepurchaseorder', $data);
		}
	}

	public function purchaseOrderPDF() {
		if (!isLogged()) {
			redirect('login');
		}

		$purchaseOrderId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		$purchaseOrder = $this->PurchaseOrder_model->getPurchaseOrder($purchaseOrderId, $businessId)->row();

		if ($purchaseOrder == null) {
			$this->session->set_tempdata('err_message', 'Deze inkooporder bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('Supplier/index/purchaseorder/');
		}

		if ($purchaseOrder->SupplierId != 0) {
			$supplier = $this->Supplier_model->getSupplier($purchaseOrder->SupplierId, $businessId)->row();
		}
		else{
			$supplier = null;
		}

		$concerns = '';

		if (!file_exists(APPPATH . "views/pdf/".$business->DirectoryPrefix."/purchaseorder.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen pakbon layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('Supplier/index/purchaseorder/');
		}

		$purchaseOrderRules = $this->PurchaseOrder_model->getPurchaseOrderRules($purchaseOrder->Id, $businessId)->result();

		foreach ($purchaseOrderRules as $key => $purchaseOrderRule) {
			$product = getProductbyArticleNumber($purchaseOrderRule->ArticleC);
			$purchaseOrderRules[$key]->WarehouseLocation  = $product != null ? $product->WarehouseLocation            : null;
			$purchaseOrderRules[$key]->WarehouseName      = $product != null ? getWarehouseName($product->Warehouse)  : null;
		}
		
		// Sort array by warhouselocation.
		usort($purchaseOrderRules, 'sortByWarehouseLocation');

		$data['business'] = $this->Business_model->getBusiness($businessId)->row();
		$data['purchaseOrder'] = $purchaseOrder;
		$data['purchaseOrderRules'] = $purchaseOrderRules;
		$data['supplier'] = $supplier;
		$data['concerns'] = $concerns;
		
		unset($this->dompdf_lib);

		$this->load->library('Dompdf_lib');

		$this->dompdf_lib->load_view('pdf/'.$business->DirectoryPrefix.'/purchaseorder', $data);
		$this->dompdf_lib->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib->set_option('isRemoteEnabled', true);
		$this->dompdf_lib->setPaper('A4', 'portrait');
		$this->dompdf_lib->render();

		// return $this->dompdf_lib->output(array('compress' => 0));

		$this->dompdf_lib->stream('Pakbon_' . $purchaseOrder->OrderNumber);
	}
}
