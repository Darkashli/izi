<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function getInvoice($id){
	$ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $id);

    $query = $ci->db->get('Invoice');

    return $query->row();
}

function getInvoiceS($id){
	$ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $id);

    $query = $ci->db->get('InvoiceSupplier');

    return $query->row();
}

function getInvoiceP($InvoiceId, $BusinessId){
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('InvoiceId', $InvoiceId);
    $ci->db->where('BusinessId', $BusinessId);

    $query = $ci->db->get('InvoicePayments');

    return $query->result();
}

function countInvoiceReminders($InvoiceId, $BusinessId){
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('InvoiceId', $InvoiceId);
    $ci->db->where('BusinessId', $BusinessId);
    $ci->db->where('ReminderType', 0);
	
	$ci->db->from('InvoiceReminders');
	
	return $ci->db->count_all_results();
}

function sendReminder($invoiceIds) {
	$ci = & get_instance();
	
	$ci->load->model('business/Business_model');
	$ci->load->helper('Business');

	$businessId = $ci->session->userdata('user')->BusinessId;
	$business = $ci->Business_model->getBusiness($businessId)->row();

	$ci->db->trans_start();

	$invoice = $ci->Customers_invoicesmodel->getInvoice($invoiceIds[0], $businessId)->row();
	
	$customer = $ci->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();
	
	// If customer is a anonymous customer, fill only the necessary data.
	if ($customer == null) {
		$customer = (object) array(
			'Name' => $invoice->CompanyName ?? $invoice->FrontName . ' ' . $invoice->Insertion . ' ' . $invoice->LastName,
			'StreetName' => $invoice->Address,
			'StreetNumber' => $invoice->AddressNumber,
			'StreetAddition' => $invoice->AddressAddition,
			'ZipCode' => $invoice->ZipCode,
			'City' => $invoice->City,
			'EmailFinancial' => $invoice->MailAddress,
			'ToAttention' => $invoice->FrontName . ' ' . $invoice->Insertion . ' ' . $invoice->LastName
		);
	}

	$data['customer'] = $customer;
	$data['invoiceIds'] = $invoiceIds;
	$data['business'] = $business;
	
	$ci->load->library('dompdf_lib');
	$ci->dompdf_lib->load_view('pdf/' . $business->DirectoryPrefix . '/reminder', $data);
	$ci->dompdf_lib->set_option('isHtml5ParserEnabled', true);
	$ci->dompdf_lib->set_option('isCssFloatEnabled', true);
	$ci->dompdf_lib->set_option('isRemoteEnabled', true);
	$ci->dompdf_lib->setPaper('A4', 'portrait');
	$ci->dompdf_lib->render();
	$pdf_content = $ci->dompdf_lib->output();
	
	// Add invoices.
	foreach ($invoiceIds as $invoiceId) {
		$invoice = $ci->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();

		$dataU = array(
			'InvoiceId' => $invoiceId,
			'ReminderDate' => strtolower(date('d-m-Y')),
			'ReminderType' => 0, // Reminder
			'BusinessId' => $businessId
		);

		$ci->Invoice_model->addReminder($dataU);
	}

	$ci->load->helper('mail');

	$subject = "Rekeningoverzicht";
	$filename = "Herinnering_" . date("dmY") . ".pdf";
	$bccName = "";
	$toMail = $customer != null ? $customer->EmailFinancial : $invoice->MailAddress;
	$toAttention = $customer != null ? $customer->ToAttention : ( $invoice->FrontName . ' ' . $invoice->Insertion . ' ' . $invoice->LastName );


	$attachments[0] = array(
		'fileName' => $filename,
		'content' => $pdf_content
	);
	
	// Send mail.
	$result = sendTicket($business->InvoiceEmail, $business->Name, $toMail, $toAttention, $subject, $business->ReminderText, $attachments, $business->InvoiceCopyEmail, $bccName);

	$ci->db->trans_complete();
	return $result;
}

function sendDunning($invoiceIds) {
	$ci = & get_instance();
	
	$ci->load->model('business/Business_model');
	$ci->load->model('customers/Customers_model');
	$ci->load->model('customers/Customers_invoicesmodel');
	$ci->load->helper('Business');
	$ci->load->helper('invoice');

	$businessId = $ci->session->userdata('user')->BusinessId;
	$business = $ci->Business_model->getBusiness($businessId)->row();

	$ci->db->trans_start();

	$invoice = $ci->Customers_invoicesmodel->getInvoice($invoiceIds[0], $businessId)->row();
	$customer = $ci->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();
	
	// If customer is a anonymous customer, fill only the necessary data.
	if ($customer == null) {
		$customer = (object) array(
			'Name' => $invoice->CompanyName ?? $invoice->FrontName . ' ' . $invoice->Insertion . ' ' . $invoice->LastName,
			'StreetName' => $invoice->Address,
			'StreetNumber' => $invoice->AddressNumber,
			'StreetAddition' => $invoice->AddressAddition,
			'ZipCode' => $invoice->ZipCode,
			'City' => $invoice->City,
			'EmailFinancial' => $invoice->MailAddress,
			'ToAttention' => $invoice->FrontName . ' ' . $invoice->Insertion . ' ' . $invoice->LastName
		);
	}

	$data['customer'] = $customer;
	$data['invoiceIds'] = $invoiceIds;
	$data['business'] = $business;

	$ci->load->library('dompdf_lib');
	$ci->dompdf_lib->load_view('pdf/' . $business->DirectoryPrefix . '/dunning', $data);
	$ci->dompdf_lib->set_option('isHtml5ParserEnabled', true);
	$ci->dompdf_lib->set_option('isCssFloatEnabled', true);
	$ci->dompdf_lib->set_option('isRemoteEnabled', true);
	$ci->dompdf_lib->setPaper('A4', 'portrait');
	$ci->dompdf_lib->render();
	$pdf_content = $ci->dompdf_lib->output();

	// Add invoices.
	foreach ($invoiceIds as $invoiceId) {
		$invoice = $ci->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();

		$dataU = array(
			'InvoiceId' => $invoiceId,
			'ReminderDate' => strtolower(date('d-m-Y')),
			'ReminderType' => 1, // Dunning
			'BusinessId' => $businessId
		);

		$ci->Invoice_model->addReminder($dataU);
	}

	$ci->load->helper('mail');

	$subject = "Aanmaning";
	$filename = "Aanmaning_" . date("dmY") . ".pdf";
	$bccName = "";
	$toMail = $customer != null ? $customer->EmailFinancial : $invoice->MailAddress;
	$toAttention = $customer != null ? $customer->ToAttention : ( $invoice->FrontName . ' ' . $invoice->Insertion . ' ' . $invoice->LastName );


	$attachments[0] = array(
		'fileName' => $filename,
		'content' => $pdf_content
	);

	// Send mail.
	$result = sendTicket($business->InvoiceEmail, $business->Name, $toMail, $toAttention, $subject, $business->DunningText, $attachments, $business->InvoiceCopyEmail, $bccName);

	$ci->db->trans_complete();

	return $result;
}
