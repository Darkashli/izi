<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->helper('Ticket');
		$this->load->helper('domain');
		$this->load->helper('quotation');
		$this->load->helper('invoice');
		$this->load->library('session');
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('customers/Customers_invoicesmodel', '', TRUE);
		$this->load->model('customers/Customers_contactsmodel', '', TRUE);
		$this->load->model('salesorders/SalesOrder_model', '', TRUE);
		$this->load->model('invoices/Invoice_model', '', TRUE);
		$this->load->model('tickets/Tickets_model', '', TRUE);
		$this->load->model('tickets/Tickets_productmodel', '', TRUE);
		$this->load->model('tickets/Tickets_statusmodel', '', TRUE);
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('customers/Customers_priceagreementmodel', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('paymentcondition/Paymentcondition_model', '', TRUE);
		$this->load->model('domains/Domain_model', '', TRUE);
		$this->load->model('quotations/Quotation_model', '', TRUE);
		$this->load->model('projects/Project_model', '', TRUE);
	}

	public function index() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->uri->segment(3) == 'work' && getBusiness($businessId)->ModuleTickets == 0) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->uri->segment(3) == 'systemmanagement' && getBusiness($businessId)->ModuleSystemManagement == 0) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->uri->segment(3) == 'quotation' && getBusiness($businessId)->ModuleQuotation == 0) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$data['customers'] = $this->Customers_model->getAll($businessId)->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('customers/default', $data);
	}

	public function edit() {
		if (!isLogged()) {
			redirect('login');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->session->userdata('user')->CustomerManagement != 1) {
			$data['readonly'] = 'readonly';
		} else {
			$data['readonly'] = '';
		}

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		$paymentConditions = $this->Paymentcondition_model->getAll($businessId)->result();
		if ($paymentConditions == NULL) {
			$this->session->set_tempdata('err_message', 'Er zijn geen betaalcondities toegevoegd. Voeg eerst een betaalconditie toe in de instellingen.', 300);
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
				'IBAN' => $_POST['iban'],
				'KVK' => $_POST['kvk'],
				'BTW' => $_POST['btw'],
				'PhoneNumber' => $_POST['phonenumber'],
				'Fax' => $_POST['fax'],
				'Email' => $_POST['email'],
				'Website' => $_POST['website'],
				'TwitterProfile' => $_POST['twitterProfile'],
				'FacebookPage' => $_POST['facebookPage'],
				'VisitStreetName' => $_POST['visitstreetname'],
				'VisitStreetNumber' => $_POST['visitstreetnumber'],
				'VisitStreetAddition' => $_POST['visitstreetaddition'],
				'VisitZipCode' => $_POST['visitzipcode'],
				'VisitCity' => $_POST['visitcity'],
				'VisitCountry' => $_POST['visitcountry'],
				'PaymentCondition' => $_POST['paymentcondition'],
				'TermOfPayment' => $_POST['termofpayment'],
				'ToAttention' => $_POST['toattention'],
				'PhonenumberFinancial' => $_POST['phonenumberfinancial'],
				'EmailFinancial' => $_POST['emailfinancial'],
				'Note' => $_POST['note'],
				'HeadCustomerId' => $_POST['headcustomerid']
			);

			$this->Customers_model->updateCustomer($customer->Id, $data);

			$this->session->set_tempdata('err_message', 'Klant is succesvol aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);

			redirect('customers/edit/' . $customer->Id);
		} else {

			if ($this->session->tempdata('err_message')) {
				$data['tpl_msg'] = $this->session->tempdata('err_message');
				$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
				$this->session->unset_tempdata('err_message');
				$this->session->unset_tempdata('err_messagetype');
			}

			$data['customer'] = $customer;
			$data['paymentConditions'] = $paymentConditions;
			$data['customers'] = $this->Customers_model->getAll($businessId)->result();

			$this->load->view('customers/edit', $data);
		}
	}

	public function create() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$data['readonly'] = '';

		$paymentConditions = $this->Paymentcondition_model->getAll($businessId)->result();
		if ($paymentConditions == NULL) {
			$this->session->set_tempdata('err_message', 'Er zijn geen betaalcondities toegevoegd. Voeg eerst een betaalconditie toe in de instellingen.', 300);
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
				'IBAN' => $_POST['iban'],
				'BTW' => $_POST['btw'],
				'PhoneNumber' => $_POST['phonenumber'],
				'Fax' => $_POST['fax'],
				'Email' => $_POST['email'],
				'Website' => $_POST['website'],
				'TwitterProfile' => $_POST['twitterProfile'],
				'FacebookPage' => $_POST['facebookPage'],
				'VisitStreetName' => $_POST['visitstreetname'],
				'VisitStreetNumber' => $_POST['visitstreetnumber'],
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

			$this->session->set_tempdata('err_message', 'De klant is succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('customers/edit/' . $customerId);
		} else {

			$customer = (object) array(
						'Name' => '',
						'StreetName' => '',
						'StreetNumber' => '',
						'StreetAddition' => '',
						'ZipCode' => '',
						'City' => '',
						'Country' => '',
						'IBAN' => '',
						'KVK' => '',
						'BTW' => '',
						'PhoneNumber' => '',
						'Fax' => '',
						'Email' => '',
						'Website' => '',
						'TwitterProfile' => '',
						'FacebookPage' => '',
						'VisitStreetName' => '',
						'VisitStreetNumber' => '',
						'VisitStreetAddition' => '',
						'VisitZipCode' => '',
						'VisitCity' => '',
						'VisitCountry' => '',
						'PaymentCondition' => '',
						'TermOfPayment' => '',
						'ToAttention' => '',
						'PhonenumberFinancial' => '',
						'EmailFinancial' => '',
						'Note' => '',
						'HeadCustomerId' => ''
			);

			$data['customer'] = $customer;
			$data['paymentConditions'] = $paymentConditions;
			$data['customers'] = $this->Customers_model->getAll($businessId)->result();

			$this->load->view('customers/edit', $data);
		}
	}

	public function SalesOrder() {
		if (!isLogged()) {
			redirect('login');
		}

		if ($this->session->userdata('user')->Tab_CSalesOrders != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST' && $_POST['invoiceFilter'] == 'closed') {
			$orders = $this->SalesOrder_model->getClosedOrders($customerId, $businessId)->result();
		} else {
			$orders = $this->SalesOrder_model->getOpenOrders($customerId, $businessId)->result();
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$data['orders'] = $orders;
		$this->load->view('customers/salesorders', $data);
	}

	public function invoices() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CInvoice != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			// Check if any invoices are sent.
			if (!$_POST['invoice']) {
				$this->session->set_tempdata('err_message', 'Je hebt geen facuturen geselecteerd', 300);
				$this->session->set_tempdata('err_messagetype', 'danger', 300);
				redirect('customers/invoices/' . $customerId);
			}

			// Check if each of the posted invoice ids exists.
			foreach ($_POST['invoice'] as $invoiceId) {
				$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();
				if ($invoice == null) {
					$this->session->set_tempdata('err_message', 'Factuur met ID ' . $invoiceId . ' bestaat niet. Er zijn geen herinneringen verzonden.', 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect("customers");
				}
			}

			switch ($_POST['action']) {
				case 'reminder':

					// Check if reminder text is not empty.
					if (!$business->ReminderText) {
						$this->session->set_tempdata('err_message', 'Er is geen tekst ingevuld vor de herinnering e-mail', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("customers/");
					}

					// Check if a reminder pfd layout exists.
					if (!file_exists(APPPATH . 'views/pdf/' . $business->DirectoryPrefix . '/reminder.php')) {
						$this->session->set_tempdata('err_message', 'Er is geen herinnering layout gevonden', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("customers/");
					}

					// Check if all given invoices exist.
					foreach ($_POST['invoice'] as $invoiceId) {
						$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();
						if ($invoice == null) {
							$this->session->set_tempdata('err_message', 'Een of meerdere ogegeven facturen bestaan niet', 300);
							$this->session->set_tempdata('err_messagetype', 'danger', 300);
							redirect("customers/");
						}
					}

					// Send reminders.
					if (sendReminder($_POST['invoice']) !== false) {
						$this->session->set_tempdata('err_message', 'Herinneringen zijn succesvol verzonden', 300);
						$this->session->set_tempdata('err_messagetype', 'success', 300);
						redirect("customers/");
					}
					else {
						$this->session->set_tempdata('err_message', 'Mail kon niet worden verzonden', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("customers/");
					}

					break;
				case 'dunning':

					// Check if dunning text is not empty.
					if (!$business->DunningText) {
						$this->session->set_tempdata('err_message', 'Er is geen tekst ingevuld vor de aanmaning e-mail', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("customers/");
					}

					// Check if a dunning pfd layout exists.
					if (!file_exists(APPPATH . 'views/pdf/' . $business->DirectoryPrefix . '/dunning.php')) {
						$this->session->set_tempdata('err_message', 'Er is geen aanmaning layout gevonden', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("customers/");
					}

					$remindersSent = true;

					foreach ($_POST['invoice'] as $invoiceId) {
						// Check if the invoice exist.
						$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();
						if ($invoice == null) {
							$this->session->set_tempdata('err_message', 'Een of meerdere ogegeven facturen bestaan niet. Er zijn geen aanmaningen verzonden.', 300);
							$this->session->set_tempdata('err_messagetype', 'danger', 300);
							redirect("customers/");
						}

						// Check if reminders are sent for this invoice.
						if (countInvoiceReminders($invoiceId, $businessId) == 0) {
							$remindersSent = false;
						}
					}

					if ($remindersSent == false) {
						$this->session->set_tempdata('err_message', 'Er zijn geen aanmaningen verstuurd omdat er voor een of meerdere opgegeven facturen nog geen herinnering is verstuurd. ', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("customers/");
					}

					// Send dunnings
					if (sendDunning($_POST['invoice']) !== false) {
						$this->session->set_tempdata('err_message', 'Aanmaningen zijn succesvol verzonden', 300);
						$this->session->set_tempdata('err_messagetype', 'success', 300);
						redirect("customers/");
					}
					else {
						$this->session->set_tempdata('err_message', 'Mail kon niet worden verzonden', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("customers/");
					}

					break;
			}

		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$data['invoices'] = $this->Customers_invoicesmodel->getCustomer($customerId, $businessId)->result();
		$data['tableFilter'] = form_dropdown('tableFilter', unserialize(INVOICEDROPDOWN), '', CLASSSELECTBOOTSTRAP . 'id="tableFilter"');

		$this->load->view('customers/invoices', $data);
	}

	public function quotations() {
		if (!isLogged()) {
			redirect('login');
		}
		if (!checkModule('ModuleQuotation'))
		{
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}
		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		$latestQuotationStatus = getLatestQiotationStatus($businessId);

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		if (!empty($_GET['quotationfilter']) && $_GET['quotationfilter'] == '1') {
			$data['quotations'] = $this->Quotation_model->getAllStatus($latestQuotationStatus->Key, $customerId, $businessId)->result();
		}
		else {
			$data['quotations'] = $this->Quotation_model->getAllStatusNot($latestQuotationStatus->Key, $customerId, $businessId)->result();
		}

		$data['latestQuotationStatus'] = $latestQuotationStatus;

		$this->load->view('customers/quotations', $data);
	}

	public function openinvoice() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CInvoice != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$invoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$invoice = $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row();

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

			$this->Invoice_model->insertInvoicePayment($data);

			$totalInvoicePaymentAmounts = $this->Invoice_model->getSumInvoicePaymentAmount($invoiceId, $businessId)->row();

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

			$this->Customers_invoicesmodel->updateInvoce($invoice->Id, $dataInvoice);

			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('customers/openinvoice/'.$invoiceId);
		}
		else{
			$invoiceRules = $this->Customers_invoicesmodel->getInvoiceRules($invoiceId, $businessId)->result();
			$invoicePayments = $this->Invoice_model->GetInvoicePaymentsByInvoice($invoiceId, $businessId)->result();
			$totalInvoicePaymentAmounts = $this->Invoice_model->getSumInvoicePaymentAmount($invoiceId, $businessId)->row();
			$invoiceReminders = $this->Invoice_model->GetAllReminders($invoiceId, $businessId)->result();
			$customer = $this->Customers_model->getCustomer($invoice->CustomerId, $businessId)->row();
			$customerData = (object) array(
						'Name' => $customer != null ? $customer->Name : ($invoice->CompanyName != null ? $invoice->CompanyName : $invoice->FrontName.' '.$invoice->Insertion.' '.$invoice->LastName),
						'StreetName' => $customer != null ? $customer->StreetName : $invoice->Address,
						'StreetNumber' => $customer != null ? $customer->StreetNumber : $invoice->AddressNumber,
						'StreetAddition' => $customer != null ? $customer->StreetAddition : $invoice->AddressAddition,
						'ZipCode' => $customer != null ? $customer->ZipCode : $invoice->ZipCode,
						'City' => $customer != null ? $customer->City : $invoice->City
			);

			if ($this->session->tempdata('err_message')) {
				$data['tpl_msg'] = $this->session->tempdata('err_message');
				$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
				$this->session->unset_tempdata('err_message');
				$this->session->unset_tempdata('err_messagetype');
			}

			$data['customerData'] = $customerData;
			$data['invoice'] = $invoice;
			$data['invoiceRules'] = $invoiceRules;
			$data['invoicePayments'] = $invoicePayments;
			$data['totalInvoicePaymentAmounts'] = $totalInvoicePaymentAmounts;
			$data['invoiceReminders'] = $invoiceReminders;

			$this->load->view('customers/invoicedetail', $data);
		}
	}

	public function contacts() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CContacts != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

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

		$data['contacts'] = $this->Customers_contactsmodel->getCustomer($customerId, $businessId)->result();

		$this->load->view('customers/contacts', $data);
	}

	public function createcontact() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CContacts != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$error = 0;

			$data = array(
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

			$id = $this->Customers_contactsmodel->createContact($data);

			$this->session->set_tempdata('err_message', 'Contactpersoon succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("customers/contacts/$customerId");
		} else {

			// In scherm moet het omgezet worde naar stdClass Object, moet met (object)

			$contact = (object) array(
						'FirstName' => "",
						'Insertion' => '',
						'LastName' => '',
						'Sex' => '',
						'Salutation' => '',
						'Email' => '',
						'PhoneNumber' => '',
						'PhoneMobile' => '',
						'Function' => '',
						'Employed' => '1'
			);

			$data['contact'] = $contact;

			$data['customerId'] = $customerId;

			$this->load->view('customers/contactsedit', $data);
		}
	}

	public function updatecontact() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CContacts != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$contactId = $this->uri->segment(3);

		$contact = $this->Customers_contactsmodel->getContact($contactId)->row();

		if ($contact == null) {

			$this->session->set_tempdata('err_message', 'Deze contactpersoon bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("client");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$data = array(
				'FirstName' => $_POST['firstname'],
				'Insertion' => $_POST['insertion'],
				'LastName' => $_POST['lastname'],
				'Sex' => $_POST['sex'],
				'Salutation' => $_POST['salutation'],
				'Email' => $_POST['email'],
				'PhoneNumber' => $_POST['phonenumber'],
				'PhoneMobile' => $_POST['phonemobile'],
				'Function' => $_POST['function'],
				'Employed' => $_POST['employed']
			);

			$id = $this->Customers_contactsmodel->updateContact($data, $contactId);

			$this->session->set_tempdata('err_message', 'Contactpersoon succesvol aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("customers/contacts/$contact->CustomerId");
		} else {

			$data['contact'] = $contact;

			$data['customerId'] = $contact->CustomerId;

			$this->load->view('customers/contactsedit', $data);
		}
	}

	public function deletecontact() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CContacts != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$contactId = $this->uri->segment(3);

		$contact = $this->Customers_contactsmodel->getContact($contactId)->row();

		if ($contact == null) {

			$this->session->set_tempdata('err_message', 'Deze contactpersoon bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("client");
		}

		$this->Customers_contactsmodel->deleteContact($contactId);

		$this->session->set_tempdata('err_message', 'Contactpersoon succesvol verwijderd', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		redirect("customers/contacts/$contact->CustomerId");
	}

	public function work()
	{
		if (!isLogged())
		{
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CWork != 1)
		{
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$this->load->library('Subquery');
		//$this->output->enable_profiler(TRUE);

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		if (!checkModule('ModuleTickets'))
		{
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null)
		{
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/work");
		}

		$statusses = $this->Tickets_statusmodel->getAll($businessId)->result();
		if ($statusses == NULL)
		{
			$this->session->set_tempdata('err_message', 'Er zijn geen statussen voor de tickets. Maak eerst een status aan in de instellingen voordat je een ticket toevoegt', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/work");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			if (isset($_POST['invoice']))
			{
				$this->_createInvoiceTicket($_POST['ticket']);
			}

			elseif (isset($_POST['archive']))
			{
				$this->_putTicketArchive($_POST['ticket']);
			}
		}

		$data['latestStatus'] = $this->Tickets_statusmodel->getLatestStatus($businessId)->row();
		$data['tickets'] = $this->Tickets_model->getAll($customerId, $businessId)->result();
		$data['ticketStatus'] = get_cookie('customers/work/ticketStatus') ?? 'open';

		if ($this->session->tempdata('err_message'))
		{
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('customers/work', $data);
	}

	public function projects()
	{
		if (!isLogged())
		{
			redirect('login');
		}

		if ($this->session->userdata('user')->Tab_CWork != 1)
		{
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleTickets'))
		{
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null)
		{
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/projects");
		}

		if ($this->session->tempdata('err_message')){
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$data['customer'] = $customer;
		$data['projects'] = $this->Project_model->getAll($customerId, $businessId)->result();

		$this->load->view('customers/projects', $data);
	}

	public function priceagreement() {

		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CPriceAgr != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModulePriceAgreement')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/work");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->load->helper('Business');
			$this->db->trans_start();
			$a = array();

			$this->Customers_priceagreementmodel->removeAll($customerId, $businessId);

			foreach ($_POST['number'] as $value) {
				$articlenumber = $_POST['articlenumber' . $value];

				// if (empty($this->Product_model->getProductByArticleNumber($articlenumber, $businessId)->row())) {
				// 	$this->session->set_tempdata('err_message', '"'.$articlenumber.'" is geen geldig artikelnummer', 300);
				// 	$this->session->set_tempdata('err_messagetype', 'warning', 300);
				// 	redirect('customers/priceagreement/' . $customerId);
				// }

				$articledescription = $_POST['articledescription' . $value];
				$salesprice = $_POST['salesprice' . $value];
				$discount = $_POST['discount' . $value];

				$dataRule = array(
					'ArticleNumber' => $articlenumber,
					'Description' => $articledescription,
					'Price' => $salesprice,
					'Discount' => $discount,
					'CustomerId' => $customerId,
					'BusinessId' => $businessId
				);

				$this->Customers_priceagreementmodel->insertRule($dataRule);
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'Prijsafspraak succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('customers/priceagreement/' . $customerId);
		} else {

			$priceAgreements = $this->Customers_priceagreementmodel->getAll($customerId, $businessId)->result();

			if ($this->session->tempdata('err_message')) {
				$data['tpl_msg'] = $this->session->tempdata('err_message');
				$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');

				$this->session->unset_tempdata('err_message');
				$this->session->unset_tempdata('err_messagetype');
			}

			$data['customer'] = $customer;
			$data['priceAgreements'] = $priceAgreements;

			$this->load->view('customers/priceagreement', $data);
		}
	}

	/**
	 * Get priceAgreement in json format.
	 * @return json priceagreements.
	 *
	 */
	public function searchPriceagreement()
	{
		if (!isLogged()) {
			redirect('login');
		}

		$customerId = $this->uri->segment(3);
		$articleC = $this->uri->segment(4);
		$businessId = $this->session->userdata('user')->BusinessId;

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$data['error'] = 'Deze klant bestaat niet';
			echo json_encode($data);
			exit;
		}

		$product = $this->Product_model->getProductByArticleNumber($articleC, $businessId)->row();

		if ($product == null) {
			$data['error'] = 'Dit product bestaat niet';
			echo json_encode($data);
			exit;
		}

		$data['priceagreement'] = $this->Customers_priceagreementmodel->getAgreement($product->ArticleNumber, $customerId)->row();
		echo json_encode($data);
	}

	public function search() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$data['customers'] = $this->Customers_model->getAll($businessId)->result();

		$this->load->view('customers/search', $data);
	}

	// public function searchZip() {
	// 	if (!isLogged()) {
	// 		redirect('login');
	// 	}
	//
	// 	$zipCode = $_GET['zip'];
	// 	$streetNumber = $_GET['number'];
	//
	// 	//echo $zipCode . ' ' . $streetNumber;
	//
	// 	echo json_encode(getAddressFromZipCode($zipCode, $streetNumber));
	// }

	public function repeatingInvoice() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$this->load->helper('repeatingInvoice');

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		if (getBusiness($businessId)->ModuleRepeatingInvoice == 0) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/work");
		}


		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$data['tpl_timeout'] = $this->session->tempdata('err_timeout');

			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
			$this->session->unset_tempdata('err_timeout');
		}

		$data['repeatingInvoices'] = $this->Customers_model->getAllRepeatingInvoiceByCustomer($customerId, $businessId)->result();

		$this->load->view('customers/repeatingInvoice', $data);
	}

	public function createRepeatingInvoice() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$customerId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		if (getBusiness($businessId)->ModuleRepeatingInvoice == 0) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/repeatingInvoice");
		}


		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$rules = array();

			$invoiceDate = $_POST['invoicedate'];
			$timePeriod = $_POST['period'];


			foreach ($_POST['number'] as $value) {
				$articlenumber = $_POST['articlenumber' . $value];
				$articledescription = $_POST['articledescription' . $value];
				$domain = isset($_POST['domain' . $value]) ? $_POST['domain' . $value] : 0;
				$amount = $_POST['amount' . $value];
				$tax = $_POST['tax' . $value];
				$total = $_POST['total' . $value];
				$salesPrice = $_POST['salesprice' . $value];
				$discount = $_POST['discount' . $value];

				$rule = (object) array(
					'ArticleNumber' => $articlenumber,
					'ArticleDescription' => $articledescription,
					'Domain' => $domain,
					'Amount' => $amount,
					'SalesPrice' => $salesPrice,
					'Discount' => $discount,
					'Tax' => $tax,
					'Total' => $total
				);

				$rules[$value] = $rule;
			}

			$data = array(
				'InvoiceDate' => strtotime($invoiceDate),
				'TimePeriod' => $timePeriod,
				'PaymentCondition' => $_POST['paymentcondition'],
				'TermOfPayment' => $_POST['termofpayment'],
				'ContactId' => $_POST['contact'],
				'InvoiceDescription' => $_POST['invoicedescription'],
				'InvoiceRules' => serialize($rules),
				'CustomerId' => $customerId,
				'BusinessId' => $businessId
			);

			$this->Customers_model->createNewRepeatingInvoice($data);

			$this->session->set_tempdata('err_message', 'Factuur succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('customers/repeatingInvoice/' . $customerId);
		} else {

			$contacts[0] = '';
			$contacts = $contacts + getContactDropdown($customer->Id, $businessId)[0];

			$data['customer'] = $customer;
			$data['contact'] = form_dropdown('contact', $contacts, '', CLASSDROPDOWN);
			$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();
			$data['period'] = form_dropdown('period', unserialize(TIMEPERIOD), '', CLASSDROPDOWN);
			$data['domains'] = $this->Domain_model->getAll($businessId)->result();

			$this->load->view('customers/createRepeatingInvoice', $data);
		}
	}

	public function updateRepeatingInvoice() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$repeatingInvoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		if (getBusiness($businessId)->ModuleRepeatingInvoice == 0) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$repeatingInvoice = $this->Customers_model->getRepeatingInvoice($repeatingInvoiceId, $businessId)->row();

		if ($repeatingInvoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/repeatingInvoice");
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$data['tpl_timeout'] = $this->session->tempdata('err_timeout');

			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
			$this->session->unset_tempdata('err_timeout');
		}

		$customer = $this->Customers_model->getCustomer($repeatingInvoice->CustomerId, $businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$rules = array();

			$invoiceDate = $_POST['invoicedate'];
			$timePeriod = $_POST['period'];


			foreach ($_POST['number'] as $value) {
				$articlenumber = $_POST['articlenumber' . $value];
				if (empty($this->Product_model->getProductByArticleNumber($articlenumber, $businessId)->row())) {
					$this->session->set_tempdata('err_message', '"' . $articlenumber . '" is geen geldig artikelnummer', 300);
					$this->session->set_tempdata('err_messagetype', 'warning', 300);
					redirect('customers/updateRepeatingInvoice/' . $this->uri->segment(3));
				}

				$articledescription = $_POST['articledescription' . $value];
				$domain = isset($_POST['domain' . $value]) ? $_POST['domain' . $value] : 0;
				$amount = $_POST['amount' . $value];
				$tax = $_POST['tax' . $value];
				$total = $_POST['total' . $value];
				$salesPrice = $_POST['salesprice' . $value];
				$discount = $_POST['discount' . $value];

				$rule = (object) array(
					'ArticleNumber' => $articlenumber,
					'ArticleDescription' => $articledescription,
					'Domain' => $domain,
					'Amount' => $amount,
					'SalesPrice' => $salesPrice,
					'Discount' => $discount,
					'Tax' => $tax,
					'Total' => $total
				);

				$rules[$value] = $rule;
			}

			$data = array(
				'InvoiceDate' => strtotime($invoiceDate),
				'TimePeriod' => $timePeriod,
				'PaymentCondition' => $_POST['paymentcondition'],
				'TermOfPayment' => $_POST['termofpayment'],
				'ContactId' => $_POST['contact'],
				'InvoiceDescription' => $_POST['invoicedescription'],
				'InvoiceRules' => serialize($rules)
			);

			$this->Customers_model->updateRepeatingInvoice($repeatingInvoiceId, $data);

			$this->session->set_tempdata('err_message', 'Factuur succesvol aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('customers/repeatingInvoice/' . $repeatingInvoice->CustomerId);
		} else {

			$contacts[0] = '';
			$contacts = $contacts + getContactDropdown($repeatingInvoice->CustomerId, $businessId);

			$data['customer'] = $customer;
			$data['contact'] = form_dropdown('contact', $contacts, $repeatingInvoice->ContactId, CLASSDROPDOWN);
			$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();
			$data['period'] = form_dropdown('period', unserialize(TIMEPERIOD), $repeatingInvoice->TimePeriod, CLASSDROPDOWN);
			$data['repeatingInvoice'] = $repeatingInvoice;
			$data['domains'] = $this->Domain_model->getAll($businessId)->result();

			$this->load->view('customers/updateRepeatingInvoice', $data);
		}
	}

	public function createInvoiceRepeating() {
		if (!isLogged()) {
			redirect('login');
		}

		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}
		$this->load->helper('Business');

		$repeatingInvoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if (!file_exists(APPPATH . 'views/pdf/' . $business->DirectoryPrefix . '/invoice.php')) {
			$this->session->set_tempdata('err_message', 'Er is geen factuur layout gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/");
		}

		$repeatingInvoice = $this->Customers_model->getRepeatingInvoice($repeatingInvoiceId, $businessId)->row();

		if ($repeatingInvoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/repeatingInvoice");
		}

		$customer = $this->Customers_model->getCustomer($repeatingInvoice->CustomerId, $businessId)->row();

		$this->db->trans_start();


		$a = array();

		$invoiceNumber = $businessId . sprintf('%05d', getBusinessInvoiceNumber($businessId) + 1);
		$invoiceDate = date('d-m-Y', $repeatingInvoice->InvoiceDate);
		$expirationDate = strtotime("+ " . $repeatingInvoice->TermOfPayment . " day", strtotime($invoiceDate));
		$timePeriod = $repeatingInvoice->TimePeriod;

		$totalEx = 0;
		$totalIn = 0;
		$totalTax21 = 0;
		$totalExTax21 = 0;
		$totalTax6 = 0;
		$totalExTax6 = 0;
		$totalExTax0 = 0;
		$totalIn21 = 0;

		foreach (unserialize($repeatingInvoice->InvoiceRules) as $rule) {

			$totalIn6 = 0;

			$total = $rule->SalesPrice * $rule->Amount * (1 - ($rule->Discount / 100));

			switch ($rule->Tax) {
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

			$description = $rule->ArticleDescription;
			if (isset($rule->Domain) && $rule->Domain != 0) {
				$domain = $this->Domain_model->get($rule->Domain)->row();
				if ($domain != null) {
					$description .= ' '.$domain->Name;
				}
			}

			$dataRule = array(
				'InvoiceNumber' => $invoiceNumber,
				'ArticleC' => $rule->ArticleNumber,
				'Amount' => $rule->Amount,
				'Description' => $description,
				'Price' => $rule->SalesPrice,
				'Discount' => $rule->Discount,
				'Tax' => $rule->Tax,
				'Total' => $total,
				'CustomerId' => $repeatingInvoice->CustomerId,
				'BusinessId' => $businessId
			);

			$ticketRuleId = $this->Invoice_model->insertInvoiceRule($dataRule);

			$a[] = $ticketRuleId;
		}

		$dataInvoice = array(
			'InvoiceNumber' => $invoiceNumber,
			'TotalEx' => $totalEx,
			'TotalIn' => $totalIn,
			'TotalTax21' => $totalTax21,
			'TotalExTax21' => $totalExTax21,
			'TotalTax6' => $totalTax6,
			'TotalExTax6' => $totalExTax6,
			'TotalExTax0' => $totalExTax0,
			'TotalIn21' => $totalIn21,
			'TotalIn6' => $totalIn6,
			'InvoiceDate' => strtotime($invoiceDate),
			'ExpirationDate' => $expirationDate,
			'TimePeriod' => $timePeriod,
			'PaymentCondition' => $repeatingInvoice->PaymentCondition,
			'TermOfPayment' => $repeatingInvoice->TermOfPayment,
			'CustomerId' => $repeatingInvoice->CustomerId,
			'BusinessId' => $businessId
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

		/**
		 * Update repeating invoice date
		 */
		$newInvoicedate = strtotime("+ " . $repeatingInvoice->TimePeriod, strtotime($invoiceDate));

		$dataRI = array(
			'InvoiceDate' => $newInvoicedate
		);

		$this->Customers_model->updateRepeatingInvoice($repeatingInvoiceId, $dataRI);

		$this->db->trans_complete();


		$this->session->set_tempdata('err_message', 'Factuur succesvol aangemaakt', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		redirect('customers/invoices/' . $repeatingInvoice->CustomerId);
	}

	public function deleteRepeatingInvoice() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$repeatingInvoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		if (getBusiness($businessId)->ModuleRepeatingInvoice == 0) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$repeatingInvoice = $this->Customers_model->getRepeatingInvoice($repeatingInvoiceId, $businessId)->row();

		if ($repeatingInvoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/repeatingInvoice");
		}

		$this->session->set_tempdata('err_message', 'Weet u zeker dat u deze factuur wilt verwijderen? Klik <a href="' . base_url() . 'customers/deleteRepeatingInvoiceConfirm/' . $repeatingInvoiceId . '">hier</a> om dit te bevestigen.', 300);
		$this->session->set_tempdata('err_messagetype', 'warning', 300);
		$this->session->set_tempdata('err_timeout', 0, 300);
		redirect('customers/repeatingInvoice/' . $repeatingInvoice->CustomerId);
	}

	public function deleteRepeatingInvoiceConfirm() {
		if (!isLogged()) {
			redirect('login');
		}
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$repeatingInvoiceId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		if (getBusiness($businessId)->ModuleRepeatingInvoice == 0) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$repeatingInvoice = $this->Customers_model->getRepeatingInvoice($repeatingInvoiceId, $businessId)->row();

		if ($repeatingInvoice == null) {
			$this->session->set_tempdata('err_message', 'Deze factuur bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/repeatingInvoice");
		}

		$this->Customers_model->deleteRepeatingInvoice($repeatingInvoice->Id);

		$this->session->set_tempdata('err_message', 'Factuur succesvol verwijderd', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		redirect('customers/repeatingInvoice/' . $repeatingInvoice->CustomerId);
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
		$invoicePayment = $this->Invoice_model->getInvoicePayment($invoicePaymentId, $businessId)->row();

		if ($invoicePayment == null) {
			$this->session->set_tempdata('err_message', 'Deze betaling bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		$invoice = $this->Customers_invoicesmodel->getInvoice($invoicePayment->InvoiceId, $businessId)->row();

		$this->Invoice_model->deleteInvoicePayment($invoicePayment->Id);

		$totalInvoicePaymentAmounts = $this->Invoice_model->getSumInvoicePaymentAmount($invoice->Id, $businessId)->row();

		if ($totalInvoicePaymentAmounts->Amount != $invoice->TotalIn && $invoice->PaymentDate != 0) {
			$dataInvoice = array(
				'PaymentDate' => 0
			);

			$this->Customers_invoicesmodel->updateInvoce($invoice->Id, $dataInvoice);

			$this->session->set_tempdata('err_message', 'De deelbetaling is verwijderd<br>Het Totale bedrag is niet langer betaald, de factuur heeft de status "niet betaald" gekregen', 300);
		}
		else{
			$this->session->set_tempdata('err_message', 'De deelbetaling is verwijderd', 300);
		}

		$this->session->set_tempdata('err_messagetype', 'success', 300);
		redirect("customers/openinvoice/".$invoicePayment->InvoiceId);
	}

	/* Private functions */

	private function _putTicketArchive($ticketIds) {
		$businessId = $this->session->userdata('user')->BusinessId;

		foreach ($ticketIds as $ticketId) {
			/* Begin updaten van ticket */

			$ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();

			if ($ticket == null) {
				$this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
				$this->session->set_tempdata('err_messagetype', 'danger', 300);
				redirect("customers/index/work");
			}

			$dataU = array(
				'Status' => 2
			);

			$this->Tickets_model->updateTicket($ticketId, $dataU);

			/* Einde updaten van ticket */
		}

		$this->session->set_tempdata('err_message', 'Ticket(s) succesvol gearchiveerd', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		redirect('customers/work/' . $ticket->CustomerId);
	}

	private function _createInvoiceTicket($ticketIds) {
		$this->load->helper('Business');
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if (!file_exists(APPPATH . 'views/pdf/' . $business->DirectoryPrefix . '/invoice.php')) {
			$this->session->set_tempdata('err_message', 'Er is geen factuur layout gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/");
		}


		$this->db->trans_begin();

		$a = array();

		$invoiceNumber = $businessId . sprintf('%05d', getBusinessInvoiceNumber($businessId) + 1);
		$invoiceDate = date('d-m-Y');

		$totalEx = 0;
		$totalIn = 0;
		$totalTax21 = 0;
		$totalExTax21 = 0;
		$totalTax6 = 0;
		$totalExTax6 = 0;
		$totalExTax0 = 0;
		$totalIn21 = 0;

		foreach ($ticketIds as $ticketId) {
			$ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();

			if ($ticket == null) {
				$this->db->trans_rollback();
				$this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
				$this->session->set_tempdata('err_messagetype', 'danger', 300);
				redirect("customers/index/work");
			}

			$customer = $this->Customers_model->getCustomer($ticket->CustomerId, $businessId)->row();

			$ticketRules = $this->Tickets_model->getTicketRulesOrderBy($ticketId, $businessId)->result();

			$expirationDate = strtotime("+ " . $customer->TermOfPayment . " day", strtotime($invoiceDate));


			$totalIn6 = 0;

			foreach ($ticketRules as $ticketRule) {
				if ($ticketRule->TotalWork == 0) {
					continue;
				}

				$product = $this->Product_model->getProductByUserNature($ticketRule->UserIdLink, $ticketRule->NatureOfWorkId)->row();

				if ($product == null) {

					$this->db->trans_rollback();
					$this->session->set_tempdata('err_message', 'De gebruiker ' . getAccountName($ticketRule->UserIdLink) . ' heeft geen product voor ' . getNatureOfWork($ticketRule->NatureOfWorkId), 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect("customers/work/" . $ticket->CustomerId);

					exit;
					return;
				}

				$priceAgreement = $this->Customers_priceagreementmodel->getAgreement($product->ArticleNumber, $customer->Id)->row();
				$discount = 0;

				if ($priceAgreement != null) {
					if ($priceAgreement->Discount != 0) {
						$discount = $priceAgreement->Discount;
					} else {
						$product->SalesPrice = $priceAgreement->Price;
					}
				}



				$total = $product->SalesPrice * $ticketRule->TotalWork * (1 - ($discount / 100));

				switch ($product->BTW) {
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

				$dataRule = array('InvoiceNumber' => $invoiceNumber,
					'ArticleC' => $product->ArticleNumber, 'Amount' => $ticketRule->TotalWork, 'Description' => $product->Description, 'Price' => $product->SalesPrice,
					'Discount' => $discount,
					'Tax' => $product->BTW,
					'Total' => $total,
					'CustomerId' => $ticket->CustomerId,
					'BusinessId' => $businessId
				);

				$ticketRuleId = $this->Invoice_model->insertInvoiceRule($dataRule);

				$a[] = $ticketRuleId;
			}

			/* Begin van factureren van bijgevoegde producten */

			$products = $this->Tickets_productmodel->getProducts($ticketId, $businessId)->result();

			if ($products != null) {

				foreach ($products as $product) {

					$articlenumber = $product->ArticleC;
					$amount = $product->Amount;
					$articledescription = $product->Description;
					$salesprice = $product->Price;
					$discount = $product->Discount;
					$tax = $product->Tax;
					$total = $product->Total;

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
						'CustomerId' => $ticket->CustomerId,
						'BusinessId' => $businessId
					);

					$ticketRuleId = $this->Invoice_model->insertInvoiceRule($dataRule);

					$a[] = $ticketRuleId;
				}
			}

			/* Einde van factureren van bijgevoegde producten */


			/* Begin updaten van ticket */

			$dataU = array(
				'Status' => 1
			);

			$this->Tickets_model->updateTicket($ticketId, $dataU);

			/* Einde updaten van ticket */
		}

		$dataInvoice = array('InvoiceNumber' => $invoiceNumber,
			'TotalEx' => $totalEx,
			'TotalIn' => $totalIn,
			'TotalTax21' => $totalTax21,
			'TotalExTax21' => $totalExTax21,
			'TotalTax6' => $totalTax6,
			'TotalExTax6' => $totalExTax6,
			'TotalExTax0' => $totalExTax0,
			'TotalIn21' => $totalIn21,
			'TotalIn6' => $totalIn6,
			'WorkOrder' => serialize($ticketIds),
			'InvoiceDate' => strtotime($invoiceDate),
			'ExpirationDate' => $expirationDate,
			'CustomerId' => $ticket->CustomerId,
			'SentPerMail' => 0,
			'BusinessId' => $businessId
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

		$this->db->trans_commit();

		$this->session->set_tempdata('err_message', 'Factuur is succesvol aangemaakt', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		redirect('customers/invoices/' . $ticket->CustomerId);
	}

	private function _createsTicket($ticketIds) {
		$this->load->helper('Business');
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if (!file_exists(APPPATH . 'views/pdf/' . $business->DirectoryPrefix . '/invoice.php')) {
			$this->session->set_tempdata('err_message', 'Er is geen factuur layout gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/");
		}


		$this->db->trans_begin();

		$a = array();

		$invoiceNumber = $businessId . sprintf('%05d', getBusinessInvoiceNumber($businessId) + 1);
		$invoiceDate = date('d-m-Y');

		$totalEx = 0;
		$totalIn = 0;
		$totalTax21 = 0;
		$totalExTax21 = 0;
		$totalTax6 = 0;
		$totalExTax6 = 0;
		$totalExTax0 = 0;
		$totalIn21 = 0;

		foreach ($ticketIds as $ticketId) {
			$ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();

			if ($ticket == null) {
				$this->db->trans_rollback();
				$this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
				$this->session->set_tempdata('err_messagetype', 'danger', 300);
				redirect("customers/index/work");
			}

			$customer = $this->Customers_model->getCustomer($ticket->CustomerId, $businessId)->row();

			$ticketRules = $this->Tickets_model->getTicketRulesOrderBy($ticketId, $businessId)->result();

			$expirationDate = strtotime("+ " . $customer->TermOfPayment . " day", strtotime($invoiceDate));


			$totalIn6 = 0;

			foreach ($ticketRules as $ticketRule) {
				if ($ticketRule->TotalWork == 0) {
					continue;
				}

				$product = $this->Product_model->getProductByUserNature($ticketRule->UserIdLink, $ticketRule->NatureOfWorkId)->row();

				if ($product == null) {

					$this->db->trans_rollback();
					$this->session->set_tempdata('err_message', 'De gebruiker ' . getAccountName($ticketRule->UserIdLink) . ' heeft geen product voor ' . getNatureOfWork($ticketRule->NatureOfWorkId), 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect("customers/work/" . $ticket->CustomerId);

					exit;
					return;
				}

				$priceAgreement = $this->Customers_priceagreementmodel->getAgreement($product->ArticleNumber, $customer->Id)->row();
				$discount = 0;

				if ($priceAgreement != null) {
					if ($priceAgreement->Discount != 0) {
						$discount = $priceAgreement->Discount;
					} else {
						$product->SalesPrice = $priceAgreement->Price;
					}
				}



				$total = $product->SalesPrice * $ticketRule->TotalWork * (1 - ($discount / 100));

				switch ($product->BTW) {
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

				$dataRule = array('InvoiceNumber' => $invoiceNumber,
					'ArticleC' => $product->ArticleNumber, 'Amount' => $ticketRule->TotalWork, 'Description' => $product->Description, 'Price' => $product->SalesPrice,
					'Discount' => $discount,
					'Tax' => $product->BTW,
					'Total' => $total,
					'CustomerId' => $ticket->CustomerId,
					'BusinessId' => $businessId
				);

				$ticketRuleId = $this->Invoice_model->insertInvoiceRule($dataRule);

				$a[] = $ticketRuleId;
			}

			/* Begin van factureren van bijgevoegde producten */

			$products = $this->Tickets_productmodel->getProducts($ticketId, $businessId)->result();

			if ($products != null) {

				foreach ($products as $product) {

					$articlenumber = $product->ArticleC;
					$amount = $product->Amount;
					$articledescription = $product->Description;
					$salesprice = $product->Price;
					$discount = $product->Discount;
					$tax = $product->Tax;
					$total = $product->Total;

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
						'CustomerId' => $ticket->CustomerId,
						'BusinessId' => $businessId
					);

					$ticketRuleId = $this->Invoice_model->insertInvoiceRule($dataRule);

					$a[] = $ticketRuleId;
				}
			}

			/* Einde van factureren van bijgevoegde producten */


			/* Begin updaten van ticket */

			$dataU = array(
				'Status' => 1
			);

			$this->Tickets_model->updateTicket($ticketId, $dataU);

			/* Einde updaten van ticket */
		}

		$dataInvoice = array('InvoiceNumber' => $invoiceNumber,
			'TotalEx' => $totalEx,
			'TotalIn' => $totalIn,
			'TotalTax21' => $totalTax21,
			'TotalExTax21' => $totalExTax21,
			'TotalTax6' => $totalTax6,
			'TotalExTax6' => $totalExTax6,
			'TotalExTax0' => $totalExTax0,
			'TotalIn21' => $totalIn21,
			'TotalIn6' => $totalIn6,
			'WorkOrder' => serialize($ticketIds),
			'InvoiceDate' => strtotime($invoiceDate),
			'ExpirationDate' => $expirationDate,
			'CustomerId' => $ticket->CustomerId,
			'SentPerMail' => 0,
			'BusinessId' => $businessId
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

		$this->db->trans_commit();

		$this->session->set_tempdata('err_message', 'Factuur is succesvol aangemaakt', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		redirect('customers/invoices/' . $ticket->CustomerId);
	}

}
