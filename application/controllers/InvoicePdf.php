<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class InvoicePdf extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->library('session');
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('customers/Customers_invoicesmodel', '', TRUE);
		$this->load->model('customers/Customers_contactsmodel', '', TRUE);
		$this->load->model('supplier/Supplier_model', '', TRUE);
		$this->load->model('tickets/Tickets_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('invoices/Invoice_model', '', TRUE);
		$this->load->helper('Ticket');
		$this->load->helper('Business');
		
		setlocale(LC_ALL, 'nl_NL');
	}

	private function invoice($invoice) {

		$businessId = $this->session->userdata('user')->BusinessId;
		$customer = $this->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();
		$business = $this->Business_model->getBusiness($businessId)->row();
		$concerns = '';

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/invoice.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen factuur layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		if ($customer != null && getHeadCustomerId($invoice->CustomerId) != 0) {
			$concerns = 'Uitgevoerd voor ' . $customer->Name;
			$customer = $this->Customers_model->getCustomer(getHeadCustomerId($invoice->CustomerId), $businessId)->row();
		}

		if ($invoice->PaymentCondition != '') {
			$paymentCondition = $invoice->PaymentCondition;
			if ($invoice->TermOfPayment != 0) {
				$paymentCondition .= ' in '.$invoice->TermOfPayment.' dagen';
			}
		} else {
			$paymentCondition = $customer->PaymentCondition;
			if ($customer->TermOfPayment != 0) {
				$paymentCondition .= ' in '.$customer->TermOfPayment.' dagen';
			}
		}
		
		$data['business'] = $this->Business_model->getBusiness($businessId)->row();
		$data['invoice'] = $invoice;
		$data['invoiceRules'] = $this->Invoice_model->getInvoiceRuleById($invoice->Id, $businessId)->result();
		$data['customer'] = $customer;
		$data['concerns'] = $concerns;
		$data['paymentCondition'] = $paymentCondition;

		// $this->load->view('pdf/' . $business->DirectoryPrefix . '/invoice', $data);

		unset($this->dompdf_lib);

		$this->load->library('dompdf_lib');

		$this->dompdf_lib->load_view('pdf/' . $business->DirectoryPrefix . '/invoice', $data);
		$this->dompdf_lib->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib->set_option('isRemoteEnabled', true);
		$this->dompdf_lib->setPaper('A4', 'portrait');
		$this->dompdf_lib->render();

		return $this->dompdf_lib->output(array('compress' => 0));
	}

	public function invoicePDF() {
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

		$this->invoice($invoice);

		$this->dompdf_lib->stream('Factuur_' . $invoice->InvoiceNumber);
	}

	private function _invoiceBought($invoice) {
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/invoiceBought.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen factuur layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("supplier");
		}

		$data['invoice'] = $invoice;

		unset($this->dompdf_lib);

		$this->load->library('dompdf_lib');

		$this->dompdf_lib->load_view('pdf/' . $business->DirectoryPrefix . '/invoiceBought', $data);
		$this->dompdf_lib->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib->set_option('isRemoteEnabled', true);
		$this->dompdf_lib->setPaper('A4', 'portrait');
		$this->dompdf_lib->render();

		return $this->dompdf_lib->output(array('compress' => 0));
	}

	public function invoiceBoughtPDF() {
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

		$this->_invoiceBought($invoice);

		$this->dompdf_lib->stream('Factuur_' . $invoice->InvoiceNumber);
	}

	private function work($invoice) {
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/work.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen werkbon layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		$data['business'] = $business;
		$data['customer'] = $this->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();
		$data['invoice'] = $invoice;

		$this->load->library('dompdf_lib', '', 'dompdf_lib2');

		$this->dompdf_lib2->load_view('pdf/' . $business->DirectoryPrefix . '/work', $data);
		$this->dompdf_lib2->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib2->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib2->set_option('isRemoteEnabled', true);
		$this->dompdf_lib2->setPaper('A4', 'portrait');
		$this->dompdf_lib2->render();

		return $this->dompdf_lib2->output(array('compress' => 0));
	}

	public function workPDF() {
		if (!isLogged()) {
			redirect('login');
		}
		$invoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();
		$business = $this->Business_model->getBusiness($businessId)->row();

		if ($invoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		$pdfContent = $this->work($invoice);

		$this->dompdf_lib2->stream('werkbon_' . $invoice->InvoiceNumber);

		//file_put_contents('werkbon_' . $invoice->InvoiceNumber . ".pdf", $pdfContent);
	}

	public function invoiceMail() {
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

		$business = $this->Business_model->getBusiness($businessId)->row();
		$customer = $this->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();

		if (getHeadCustomerId($invoice->CustomerId) != 0) {
			$customer = $this->Customers_model->getCustomer(getHeadCustomerId($invoice->CustomerId), $businessId)->row();
		}

		$pdf_content = $this->invoice($invoice);


		$subject = "Uw factuur: " . $invoice->InvoiceNumber;
		$filename = "Factuur_" . $invoice->InvoiceNumber . ".pdf";
		$bccName = "";
		$emailFinancial = $customer != null ? $customer->EmailFinancial : $invoice->MailAddress;
		$toAttention = $customer != null ? $customer->ToAttention : $invoice->CompanyName != null ? $invoice->CompanyName : $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName;

		$this->load->helper('mail');

		$attachments[0] = array(
			'fileName' => $filename,
			'content' => $pdf_content
		);

		if ($invoice->WorkOrder) {
			$attachments[1] = array(
				'fileName' => 'Werkbon_' . $invoice->InvoiceNumber . '.pdf',
				'content' => $this->work($invoice)
			);
		}

		$result = sendTicket($business->InvoiceEmail, $business->Name, $emailFinancial, $toAttention, $subject, $business->InvoiceText, $attachments, $business->InvoiceCopyEmail, $bccName);

		if ($result) {
			
			$data = array(
				'SentPerMail' => 1
			);
			$this->Invoice_model->updateInvoice($invoiceId, $data);
			
			$this->session->set_tempdata('err_message', 'Factuur succesvol verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
		} else {
			$this->session->set_tempdata('err_message', 'Factuur is niet verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
		}
		if ($customer != null) {
			redirect('customers/invoices/' . $customer->Id);
		}
		else{
			redirect('invoices/anonymous');
		}
	}
	
	/**
	 * Just send an invoice to vdsadmin.mvl15637@mailtobasecone.com
	 */
	public function invoiceMailVdsadmin() {
		if (!isLogged()) {
			redirect('login');
		}

		$invoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();

		if ($invoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}

		$business = $this->Business_model->getBusiness($businessId)->row();
		
		$pdf_content = $this->invoice($invoice);

		$subject = "Uw factuur: " . $invoice->InvoiceNumber;
		$filename = "Factuur_" . $invoice->InvoiceNumber . ".pdf";
		$bccName = "";
		$emailFinancial = 'vdsadmin.mvl15637@mailtobasecone.com';
		$toAttention = NULL;

		$this->load->helper('mail');

		$attachments[0] = array(
			'fileName' => $filename,
			'content' => $pdf_content
		);

		if ($invoice->WorkOrder) {
			$attachments[1] = array(
				'fileName' => 'Werkbon_' . $invoice->InvoiceNumber . '.pdf',
				'content' => $this->work($invoice)
			);
		}

		$result = sendTicket($business->InvoiceEmail, $business->Name, $emailFinancial, $toAttention, $subject, $business->InvoiceText, $attachments, NULL, $bccName);

		if ($result) {
			$this->session->set_tempdata('err_message', 'Factuur succesvol verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
		} else {
			$this->session->set_tempdata('err_message', 'Factuur is niet verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
		}
		redirect('overviews');
	}

	public function invoiceCopyMail() {
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

		$business = $this->Business_model->getBusiness($businessId)->row();
		$customer = $this->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();

		if ($customer != null && getHeadCustomerId($invoice->CustomerId) != 0) {
			$customer = $this->Customers_model->getCustomer(getHeadCustomerId($invoice->CustomerId), $businessId)->row();
		}

		$pdf_content = $this->invoice($invoice);


		$subject = "Uw kopie factuur: " . $invoice->InvoiceNumber;
		$filename = "Factuur_" . $invoice->InvoiceNumber . ".pdf";
		$bccName = "";
		$emailFinancial = $customer != null ? $customer->EmailFinancial : $invoice->MailAddress;
		$toAttention = $customer != null ? $customer->ToAttention : $invoice->CompanyName != null ? $invoice->CompanyName : $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName;

		$this->load->helper('mail');

		$attachments[0] = array(
			'fileName' => $filename,
			'content' => $pdf_content
		);

		if ($invoice->WorkOrder) {
			$attachments[1] = array(
				'fileName' => 'Werkbon_' . $invoice->InvoiceNumber . '.pdf',
				'content' => $this->work($invoice)
			);
		}

		$result = sendTicket($business->InvoiceEmail, $business->Name, $emailFinancial, $toAttention, $subject, $business->InvoiceCopyText, $attachments, $business->InvoiceCopyEmail, $bccName);

		if ($result) {
			$this->session->set_tempdata('err_message', 'Factuur kopie succesvol verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
		} else {
			$this->session->set_tempdata('err_message', 'Factuur kopie is niet verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
		}
		if ($customer != null) {
			redirect('customers/invoices/' . $customer->Id);
		}
		else{
			redirect('invoices/anonymous');
		}
	}

	public function overviewBought() {
		if ($this->uri->total_rsegments() != 6) {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}
		ini_set('max_execution_time', 0);

		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);
		$supplierId = $this->uri->segment(5);
		$type = $this->uri->segment(6);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/overviewBought.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen overzicht layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("dashboard");
		}

		$data['from'] = $from;
		$data['to'] = $to;
		$data['supplierId'] = $supplierId;

		$data['invoices'] = $this->Invoice_model->getBetweenDateS($from, $to, $supplierId, $businessId)->result();

		if ($type == 'pdf') {
			//$this->load->view('pdf/' . $businessId . '/overviewBought', $data);
			
			$this->load->library('dompdf_lib');
			
			$this->dompdf_lib->load_view('pdf/' . $business->DirectoryPrefix . '/overviewBought', $data);
			$this->dompdf_lib->set_option('isHtml5ParserEnabled', true);
			$this->dompdf_lib->set_option('isCssFloatEnabled', true);
			$this->dompdf_lib->set_option('isRemoteEnabled', true);
			$this->dompdf_lib->setPaper('A4', 'portrait');
			$this->dompdf_lib->render();
			
			$this->dompdf_lib->stream('factuur_overzicht');
		}
		elseif ($type == 'csv') {
			header('Content-Type: application/excel');
			header('Content-Disposition: attachment; filename="factuur_overzicht.csv"');
			
			$fp = fopen('php://output', 'w');
			$row = array(
				'Leverancier',
				'Leverancier straatnaam',
				'Leverancier postcode',
				'Leverancier plaats',
				'BTW',
				'IBAN',
				'KVK',
				'Factuurdatum',
				'Betaaldatum',
				'Factuurnummer',
				'Inkoopnummer',
				'Aantal',
				'Omschrijving',
				'Prijs',
				'Totaal prijs',
				'Totaal BTW 21%',
				'Totaal excl. BTW',
				'Totaal BTW 9%',
				'Totaal incl. BTW'
			);
			fputcsv($fp, $row);
			
			foreach ($data['invoices'] as $invoice) {
				$supplier = $this->Supplier_model->getSupplier($invoice->SupplierId, $businessId)->row();
				$invoiceRules = $this->Invoice_model->getInvoiceSupplierRule($invoice->Id, $businessId)->result();
				foreach ($invoiceRules as $invoiceRule) {
					$row = array(
						$invoice->SupplierId != 0 ? $supplier->Name : $invoice->CompanyName ?? $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName,
						$invoice->SupplierId != 0 ? $supplier->StreetName : $invoice->Address,
						$invoice->SupplierId != 0 ? $supplier->ZipCode : $invoice->ZipCode,
						$invoice->SupplierId != 0 ? $supplier->City : $invoice->City,
						$invoice->SupplierId != 0 ? $supplier->BTW : NULL,
						$invoice->SupplierId != 0 ? $supplier->IBAN : NULL,
						$invoice->SupplierId != 0 ? $supplier->KVK : NULL,
						date('d-m-Y', $invoice->InvoiceDate),
						$invoice->PaymentDate,
						$invoice->InvoiceNumber,
						$invoice->PurchaseNumber,
						$invoiceRule->Amount,
						$invoiceRule->Description,
						$invoiceRule->Price,
						$invoiceRule->Total,
						$invoice->TotalTax21,
						$invoice->TotalEx,
						$invoice->TotalTax6,
						$invoice->TotalIn
					);
					fputcsv($fp, $row);
				}
			}
			
			fclose($fp);
		}
	}

	public function overviewSold() {
		if ($this->uri->total_rsegments() != 6) {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');

		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);
		$customerId = $this->uri->segment(5);
		$type = $this->uri->segment(6);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/overviewSold.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen overzicht layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("dashboard");
		}

		$data['from'] = $from;
		$data['to'] = $to;

		$data['customerId'] = $customerId;

		$data['invoices'] = $this->Invoice_model->getBetweenDateC($from, $to, $customerId, $businessId)->result();
		
		if ($type == 'pdf') {
			// $this->load->view('pdf/' . $business->DirectoryPrefix . '/overviewSold', $data);
			
			$this->load->library('dompdf_lib');
			
			$this->dompdf_lib->load_view('pdf/' . $business->DirectoryPrefix . '/overviewSold', $data);
			$this->dompdf_lib->set_option('isHtml5ParserEnabled', true);
			$this->dompdf_lib->set_option('isCssFloatEnabled', true);
			$this->dompdf_lib->set_option('isRemoteEnabled', true);
			$this->dompdf_lib->setPaper('A4', 'portrait');
			$this->dompdf_lib->render();
			
			$this->dompdf_lib->stream('factuur_verkoopoverzicht_' . date('d-m-Y'));
		}
		elseif ($type == 'csv') {
			header('Content-Type: application/excel');
			header('Content-Disposition: attachment; filename="factuur_verkoopoverzicht_'.date('d-m-Y').'.csv"');
			
			$fp = fopen('php://output', 'w');
			$row = array(
				'Leverancier',
				'Leverancier straatnaam',
				'Leverancier postcode',
				'Leverancier plaats',
				'BTW',
				'IBAN',
				'KVK',
				'Factuurdatum',
				'Factuurnummer',
				'Aantal',
				'Omschrijving',
				'Prijs',
				'Totaal Prijs',
				'Totaal BTW 21%',
				'Totaal excl. BTW',
				'Totaal BTW 9%',
				'Totaal incl. BTW'
			);
			fputcsv($fp, $row);
			
			foreach ($data['invoices'] as $invoice) {
				$customer = $this->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();
				$invoiceRules = $this->Invoice_model->getInvoiceRule($invoice->InvoiceNumber, $businessId)->result();
				foreach ($invoiceRules as $invoiceRule) {
					$row = array(
						$invoice->CustomerId != 0 ? $customer->Name : $invoice->CompanyName ?? $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName,
						$invoice->CustomerId != 0 ? $customer->StreetName : $invoice->Address,
						$invoice->CustomerId != 0 ? $customer->ZipCode : $invoice->ZipCode,
						$invoice->CustomerId != 0 ? $customer->City : $invoice->City,
						$invoice->CustomerId != 0 ? $customer->BTW : NULL,
						$invoice->CustomerId != 0 ? $customer->IBAN : NULL,
						$invoice->CustomerId != 0 ? $customer->KVK : NULL,
						date('d-m-Y', $invoice->InvoiceDate),
						$invoice->InvoiceNumber,
						$invoiceRule->Amount,
						$invoiceRule->Description,
						$invoiceRule->Price,
						$invoiceRule->Total,
						$invoice->TotalTax21,
						$invoice->TotalEx,
						$invoice->TotalTax6,
						$invoice->TotalIn
					);
					fputcsv($fp, $row);
				}
			}
			
			fclose($fp);
		}
		
	}

	public function overviewWork() {
		if ($this->uri->total_rsegments() != 5) {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');

		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);
		$userId = $this->uri->segment(5);
		$businessId = $this->session->userdata('user')->BusinessId;

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/overviewWork.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen overzicht layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}

		$data['from'] = $from;
		$data['to'] = $to;
		$data['userId'] = $userId;
		$data['activities'] = $this->Tickets_model->getActivityBetweetDate(strtotime($from), strtotime($to), $userId, $businessId)->result();

		//$this->load->view('pdf/' . $businessId . '/overviewWork', $data);

		$this->load->library('dompdf_lib');

		$this->dompdf_lib->load_view('pdf/' . $business->DirectoryPrefix . '/overviewWork', $data);
		$this->dompdf_lib->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib->set_option('isRemoteEnabled', true);
		$this->dompdf_lib->setPaper('A4', 'landscape');
		$this->dompdf_lib->render();

		$this->dompdf_lib->stream('overzichActiviteiten_' . date('d-m-Y'));
	}
	
	public function overviewBtw() {
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->uri->total_rsegments() != 5) {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/overviewBtw.php")) {
			$this->session->set_tempdata('err_message', 'Er is geen overzicht layout aanwezig', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("dashboard");
		}

		$year = $this->uri->segment(3);
		$periodType = $this->uri->segment(4);
		$period = $this->uri->segment(5);
		
		if ($periodType == 'month') {
			$from = "1-$period-$year";
			$to = date('t-m-Y', strtotime($from));
		}
		elseif ($periodType == 'quartal') {
			switch ($period) {
				case 1:
					$from = "1-1-$year";
					$to = date('t-m-Y', strtotime("1-3-$year"));
					break;
				case 2:
					$from = "1-4-$year";
					$to = date('t-m-Y', strtotime("1-6-$year"));
					break;
				case 3:
					$from = "1-7-$year";
					$to = date('t-m-Y', strtotime("1-9-$year"));
					break;
				case 4:
					$from = "1-10-$year";
					$to = date('t-m-Y', strtotime("1-12-$year"));
					break;
				default:
					$from = "1-1-$year";
					$to = date('t-m-Y', strtotime("1-3-$year"));
					break;
			}
		}
		
		$invoices = $this->Invoice_model->getBetweenDateC($from, $to, 0, $businessId)->result();
		$invoicesSupplier = $this->Invoice_model->getBetweenDateS($from, $to, 0, $businessId)->result();
		
		$btw = array(
			'highBtwRules' => array(
				'exSalesTax' => 0,
				'salesTax' => 0
			),
			'lowBtwRules' => array(
				'exSalesTax' => 0,
				'salesTax' => 0
			),
			'noBtwRules' => array(
				'exSalesTax' => 0
			),
			'preload' => 0,
			'total' => 0
		);
		
		foreach ($invoices as $invoice) {
			$btw['highBtwRules']['exSalesTax'] += $invoice->TotalTax21 != 0 ? $invoice->TotalEx : 0;
			$btw['highBtwRules']['salesTax'] += $invoice->TotalTax21 != 0 ? $invoice->TotalTax21 : 0;
			$btw['lowBtwRules']['exSalesTax'] += $invoice->TotalTax6 != 0 ? $invoice->TotalEx : 0;
			$btw['lowBtwRules']['salesTax'] += $invoice->TotalTax6 != 0 ? $invoice->TotalTax6 : 0;
			$btw['noBtwRules']['exSalesTax'] += ($invoice->TotalTax21 == 0 && $invoice->TotalTax6 == 0) ? $invoice->TotalEx : 0;
		}
		
		foreach ($invoicesSupplier as $invoiceSupplier) {
			$btw['preload'] += ($invoiceSupplier->TotalTax21 + $invoiceSupplier->TotalTax6);
		}
		
		$btw['highBtwRules']['exSalesTax'] 	= floor($btw['highBtwRules']['exSalesTax']);
		$btw['highBtwRules']['salesTax'] 	= floor($btw['highBtwRules']['salesTax']);
		$btw['lowBtwRules']['exSalesTax'] 	= floor($btw['lowBtwRules']['exSalesTax']);
		$btw['lowBtwRules']['salesTax'] 	= floor($btw['lowBtwRules']['salesTax']);
		$btw['noBtwRules']['exSalesTax'] 	= floor($btw['noBtwRules']['exSalesTax']);
		$btw['preload'] 					= ceil($btw['preload']);
		
		$btw['total'] = ($btw['highBtwRules']['salesTax'] + $btw['lowBtwRules']['salesTax'] - $btw['preload']);
		
		$data['btw'] = $btw;
		$data['from'] = $from;
		$data['to'] = $to;

		// $this->load->view('pdf/' . $business->DirectoryPrefix . '/overviewBtw', $data);
		
		$this->load->library('dompdf_lib');
		
		$this->dompdf_lib->load_view('pdf/' . $business->DirectoryPrefix . '/overviewBtw', $data);
		$this->dompdf_lib->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib->set_option('isRemoteEnabled', true);
		$this->dompdf_lib->setPaper('A4', 'portrait');
		$this->dompdf_lib->render();
		
		$this->dompdf_lib->stream('btw_overzicht_' . date('d-m-Y'));
		
	}

}
