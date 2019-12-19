<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('supplier/Supplier_model', '', TRUE);
        $this->load->model('purchaseorders/PurchaseOrder_model', '', TRUE);
        $this->load->model('paymentcondition/Paymentcondition_model', '', TRUE);
        $this->load->model('invoices/Invoice_model', '', TRUE);
    }

    public function index() {
        if (!isLogged()) {
            redirect('login');
        }

        $data['suppliers'] = $this->Supplier_model->getAll($this->session->userdata('user')->BusinessId)->result();

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }

        $this->load->view('supplier/default', $data);
    }

    public function edit() {
        if (!isLogged()) {
            redirect('login');
        }

        $supplierId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        if ($this->session->userdata('user')->CustomerManagement != 1) {
            $data['readonly'] = 'readonly';
        } else {
            $data['readonly'] = '';
        }

        $supplier = $this->Supplier_model->getSupplier($supplierId, $businessId)->row();

        if ($supplier == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = array(
                'Name' => $_POST['name'],
                'StreetName' => $_POST['streetname'],
                'StreetNumber' => $_POST['streetnumber'],
                'StreetAddition' => $_POST['streetaddition'],
                'ZipCode' => $_POST['zipcode'],
                'City' => $_POST['city'],
                'Country' => $_POST['country'],
                'PhoneNumber' => $_POST['phonenumber'],
                'Fax' => $_POST['fax'],
                'Email' => $_POST['email'],
                'Website' => $_POST['website'],
                'BTW' => $_POST['btw'],
                'IBAN' => $_POST['iban'],
                'PaymentCondition' => $_POST['paymentcondition'],
                'TermOfPayment' => $_POST['termofpayment'],
            );

            $this->Supplier_model->updateSupplier($supplierId, $data);

            $this->session->set_tempdata('err_message', 'Leverancier succesvol geupdate', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("supplier");
        } else {

            $data['supplier'] = $supplier;
            $data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();

            $this->load->view('supplier/edit', $data);
        }
    }

    public function create() {
        if (!isLogged()) {
            redirect('login');
        }

        $businessId = $this->session->userdata('user')->BusinessId;
        $paymentConditions = $this->Paymentcondition_model->getAll($businessId)->result();
        
        if ($paymentConditions == null) {
            $this->session->set_tempdata('err_message', 'Er zijn geen betaalcondities aanwezig', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("supplier");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = array(
                'Name' => $_POST['name'],
                'StreetName' => $_POST['streetname'],
                'StreetNumber' => $_POST['streetnumber'],
                'StreetAddition' => $_POST['streetaddition'],
                'ZipCode' => $_POST['zipcode'],
                'City' => $_POST['city'],
                'Country' => $_POST['country'],
                'PhoneNumber' => $_POST['phonenumber'],
                'Fax' => $_POST['fax'],
                'Email' => $_POST['email'],
                'Website' => $_POST['website'],
                'BTW' => $_POST['btw'],
                'IBAN' => $_POST['iban'],
                'PaymentCondition' => $_POST['paymentcondition'],
                'TermOfPayment' => $_POST['termofpayment'],
                'BusinessId' => $businessId
            );

            $this->Supplier_model->createSupplier($data);

            $this->session->set_tempdata('err_message', 'Leverancier succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("supplier");
        } else {

            $supplier = (object) array(
                        'Name' => '',
                        'StreetName' => '',
                        'StreetNumber' => '',
                        'StreetAddition' => '',
                        'ZipCode' => '',
                        'City' => '',
                        'Country' => '',
                        'PhoneNumber' => '',
                        'Fax' => '',
                        'Email' => '',
                        'Website' => '',
                        'BTW' => '',
                        'KVK' => '',
                        'IBAN' => '',
                        'PaymentCondition' => '',
                        'TermOfPayment' => '',
                        'Note' => ''
            );

            $data['supplier'] = $supplier;
            $data['paymentConditions'] = $paymentConditions;
            $data['readonly'] = '';

            $this->load->view('supplier/edit', $data);
        }
    }

    public function invoices() {
        if (!isLogged()) {
            redirect('login');
        }

        $supplierId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Supplier_model->getSupplier($supplierId, $businessId)->row();

        if ($customer == null) {
            $this->session->set_tempdata('err_message', 'Deze leverancier bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }

        $data['invoices'] = $this->Supplier_model->getInvoices($supplierId, $businessId)->result();
        $data['tableFilter'] = form_dropdown('tableFilter', unserialize(INVOICEDROPDOWN), '', CLASSSELECTBOOTSTRAP . 'id="tableFilter"');

        $this->load->view('supplier/invoices', $data);
    }

    public function openinvoice() {
        if (!isLogged()) {
            redirect('login');
        }

        $invoiceId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $invoice = $this->Supplier_model->getInvoice($invoiceId, $businessId)->row();

        if ($invoice == null) {
            $this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = array(
                'InvoiceId' => $invoiceId,
                'Date' => strtotime($_POST['paymentdate']),
                'Amount' => $_POST['amount'],
                'BusinessId' => $businessId
            );
            
            $this->Invoice_model->insertInvoiceSupplierPayment($data);
            
            $totalInvoicePaymentAmounts = $this->Invoice_model->getSumInvoiceSupplierPaymentAmount($invoiceId, $businessId)->row();
            
            if ($totalInvoicePaymentAmounts->Amount >= $invoice->TotalIn) {
                $dataInvoice = array(
                    'PaymentDate' => strtotime($_POST['paymentdate'])
                );

                $this->session->set_tempdata('err_message', 'De deelbetaling is opgeslagen<br>Het Totale bedrag is betaald, de factuur heeft de status "betaald" gekregen', 300);
            }
            else{
                $dataInvoice = array(
                    'PaymentDate' => 0
                );

                $this->session->set_tempdata('err_message', 'De deelbetaling is opgeslagen', 300);
            }

            $this->Supplier_model->updateInvoice($invoice->Id, $dataInvoice);

            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect('supplier/openinvoice/' . $invoiceId);
        } else {

            $invoiceRules = $this->Supplier_model->getInvoiceRules($invoiceId, $businessId)->result();
            $invoicePayments = $this->Invoice_model->GetInvoiceSupplierPaymentsByInvoice($invoiceId, $businessId)->result();
            $totalInvoicePaymentAmounts = $this->Invoice_model->getSumInvoiceSupplierPaymentAmount($invoiceId, $businessId)->row();
            $supplier = $this->Supplier_model->getSupplier($invoice->SupplierId, $businessId)->row();

            $supplierData = (object) array(
                'Name' => $supplier != null ? $supplier->Name : ($invoice->CompanyName != null ? $invoice->CompanyName : $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName),
                'StreetName' => $supplier != null ? $supplier->StreetName : $invoice->Address,
                'StreetNumber' => $supplier != null ? $supplier->StreetNumber : $invoice->AddressNumber,
                'StreetAddition' => $supplier != null ? $supplier->StreetAddition : $invoice->AddressAddition,
                'ZipCode' => $supplier != null ? $supplier->ZipCode : $invoice->ZipCode,
                'City' => $supplier != null ? $supplier->City : $invoice->City
            );
            
            if ($this->session->tempdata('err_message')) {
                $data['tpl_msg'] = $this->session->tempdata('err_message');
                $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
                $this->session->unset_tempdata('err_message');
                $this->session->unset_tempdata('err_messagetype');
            }
            
            $data['supplierData'] = $supplierData;
            $data['invoice'] = $invoice;
            $data['invoiceRules'] = $invoiceRules;
            $data['invoicePayments'] = $invoicePayments;
            $data['totalInvoicePaymentAmounts'] = $totalInvoicePaymentAmounts;

            $this->load->view('supplier/invoicedetail', $data);
        }
    }

    public function contacts() {
        if (!isLogged()) {
            redirect('login');
        }

        $supplierId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Supplier_model->getSupplier($supplierId, $businessId)->row();

        if ($customer == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }

        $data['contacts'] = $this->Supplier_model->getContacts($supplierId, $businessId)->result();

        $this->load->view('supplier/contacts', $data);
    }

    public function createcontact() {
        if (!isLogged()) {
            redirect('login');
        }

        $supplierId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $supplier = $this->Supplier_model->getSupplier($supplierId, $businessId)->row();

        if ($supplier == null) {
            $this->session->set_tempdata('err_message', 'Deze leverancier bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $error = 0;

            $data = array(
                'FirstName' => $_POST['firstname'],
                'Insertion' => $_POST['insertion'],
                'LastName' => $_POST['lastname'],
                'Email' => $_POST['email'],
                'PhoneNumber' => $_POST['phonenumber'],
                'PhoneMobile' => $_POST['phonemobile'],
                'Function' => $_POST['function'],
                'SupplierId' => $supplierId,
                'BusinessId' => $businessId
            );

            $id = $this->Supplier_model->createContact($data);

            $this->session->set_tempdata('err_message', 'Contactpersoon succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("supplier/contacts/$supplierId");
        } else {

            // In scherm moet het omgezet worde naar stdClass Object, moet met (object)

            $contact = (object) array(
                        'FirstName' => "",
                        'Insertion' => '',
                        'LastName' => '',
                        'Email' => '',
                        'PhoneNumber' => '',
                        'PhoneMobile' => '',
                        'Function' => ''
            );

            $data['contact'] = $contact;

            $this->load->view('supplier/contactsedit', $data);
        }
    }

    public function updatecontact() {
        if (!isLogged()) {
            redirect('login');
        }

        $contactId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $contact = $this->Supplier_model->getContact($contactId, $businessId)->row();

        if ($contact == null) {

            $this->session->set_tempdata('err_message', 'Deze contactpersoon bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("client");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $error = 0;

            $data = array(
                'FirstName' => $_POST['firstname'],
                'Insertion' => $_POST['insertion'],
                'LastName' => $_POST['lastname'],
                'Email' => $_POST['email'],
                'PhoneNumber' => $_POST['phonenumber'],
                'PhoneMobile' => $_POST['phonemobile'],
                'Function' => $_POST['function'],
            );

            $id = $this->Supplier_model->updateContact($data, $contactId);

            $this->session->set_tempdata('err_message', 'Contactpersoon succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("supplier/contacts/$contact->SupplierId");
        } else {

            $data['contact'] = $contact;

            $this->load->view('supplier/contactsedit', $data);
        }
    }

    public function deletecontact() {
        if (!isLogged()) {
            redirect('login');
        }

        $contactId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $contact = $this->Supplier_model->getContact($contactId, $businessId)->row();

        if ($contact == null) {

            $this->session->set_tempdata('err_message', 'Deze contactpersoon bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("supplier");
        }

        $this->Customers_contactsmodel->deleteContact($contactId);

        $this->session->set_tempdata('err_message', 'Contactpersoon succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect("supplier/contacts/$contact->CustomerId");
    }

    public function search() {
        if (!isLogged()) {
            redirect('login');
        }

        $businessId = $this->session->userdata('user')->BusinessId;

        $data['suppliers'] = $this->Supplier_model->getAll($this->session->userdata('user')->BusinessId)->result();

        $this->load->view('supplier/search', $data);
    }

    public function purchaseorders(){
        if (!isLogged()) {
            redirect('login');
        }
        
        if ($this->session->userdata('user')->Tab_CPurchaseOrders != 1) {
            show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
        }

        $supplierId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $supplier = $this->Supplier_model->getSupplier($supplierId, $businessId)->row();

        if ($supplier == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST' && isset($_POST['invoiceFilter']) && $_POST['invoiceFilter'] == 'closed') {
            $orders = $this->PurchaseOrder_model->getClosedOrders($supplierId, $businessId)->result();
        }
        else{
            $orders = $this->PurchaseOrder_model->getOpenOrders($supplierId, $businessId)->result();
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }

        $data['orders'] = $orders;
        $this->load->view('supplier/purchaseorders', $data);
    }
    
    public function deleteInvoicePayment()
    {
        if (!isLogged()) {
            redirect('login');
        }
        
        if ($this->session->userdata('user')->Tab_CInvoice != 1) {
            show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
        }
        
        $businessId = $this->session->userdata('user')->BusinessId;
        $invoicePaymentId = $this->uri->segment(3);
        $invoicePayment = $this->Invoice_model->getInvoiceSupplierPayment($invoicePaymentId, $businessId)->row();

        if ($invoicePayment == null) {
            $this->session->set_tempdata('err_message', 'Deze betaling bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("supplier");
        }

        $invoice = $this->Supplier_model->getInvoice($invoiceId, $businessId)->row();

        $this->Invoice_model->deleteInvoiceSupplierPayment($invoicePayment->Id);

        $totalInvoicePaymentAmounts = $this->Invoice_model->getSumInvoiceSupplierPaymentAmount($invoice->Id, $businessId)->row();

        if ($totalInvoicePaymentAmounts->Amount != $invoice->TotalIn && $invoice->PaymentDate != 0) {
            $dataInvoice = array(
                'PaymentDate' => 0
            );

            $this->Invoice_model->updateInvoiceSupplier($invoice->Id, $dataInvoice);
            
            $this->session->set_tempdata('err_message', 'De deelbetaling is verwijderd<br>Het Totale bedrag is niet langer betaald, de factuur heeft de status "niet betaald" gekregen', 300);
        }
        else{
            $this->session->set_tempdata('err_message', 'De deelbetaling is verwijderd', 300);
        }

        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect("supplier/openinvoice/".$invoicePayment->InvoiceId);
    }

}
