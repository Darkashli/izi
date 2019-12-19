<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Overviews extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->helper('Ticket');
		$this->load->helper('user');
		$this->load->helper('account');
		$this->load->helper('productgroup');
		$this->load->helper('salesorder');
		$this->load->helper('invoice');
		$this->load->helper('csv');
		$this->load->library('session');
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('customers/Customers_invoicesmodel', '', TRUE);
		$this->load->model('customers/Customers_contactsmodel', '', TRUE);
		$this->load->model('customers/Customers_priceagreementmodel', '', TRUE);
		$this->load->model('invoices/Invoice_model', '', TRUE);
		$this->load->model('tickets/Tickets_model', '', TRUE);
		$this->load->model('tickets/Tickets_statusmodel', '', TRUE);
		$this->load->model('tickets/Tickets_productmodel', '', TRUE);
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('productgroup/Productgroup_model', '', TRUE);
		$this->load->model('users/Users_model', '', TRUE);
		$this->load->model('salesorders/SalesOrder_model', '', TRUE);
		$this->load->model('supplier/Supplier_model', '', TRUE);
		$this->load->model('sellers/Sellers_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
	}

	public function index() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$userId = $this->session->userdata('user')->Id;

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			if (isset($_POST['overviewSold'])) {
				// Overzicht van verkoopfacturen
				redirect('overviews/overviewSold/' . $_POST['from'] . '/' . $_POST['to'] . '/' . $_POST['customerid']);
			} elseif (isset($_POST['overviewBought'])) {
				redirect('overviews/overviewBought/' . $_POST['from'] . '/' . $_POST['to'] . '/' . $_POST['supplierid']);
			} elseif (isset($_POST['overviewRepeatingInvoice'])) {
				redirect('overviews/overviewRepeatingInvoice/' . $_POST['from'] . '/' . $_POST['to'] . '/' . $_POST['customerid']);
			} elseif (isset($_POST['overviewWork'])) {
				redirect('overviews/overviewWork/' . $_POST['from'] . '/' . $_POST['to'] . '/' . $_POST['user']);
			} elseif (isset($_POST['overviewBtw'])) {
				if ($_POST['periodType'] == 'month') {
					$period = $_POST['month'];
				}
				if ($_POST['periodType'] == 'quartal') {
					$period = $_POST['quartal'];
				}
				redirect('overviews/overviewBtw/' . $_POST['year'] . '/' . $_POST['periodType'] . '/' . $period);
			}
		} else {

			if ($this->session->tempdata('err_message')) {
				$data['tpl_msg'] = $this->session->tempdata('err_message');
				$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
				$this->session->unset_tempdata('err_message');
				$this->session->unset_tempdata('err_messagetype');
			}

			$users = getUserDropdown($businessId);
			//$users[0][0] = "Alle";

			$data['user'] = form_dropdown_disabled('user', $users[0], $userId, CLASSDROPDOWN, $users[1]);
			$data['productgroups'] = $this->Productgroup_model->getAll($businessId)->result();
			$data['suppliers'] = $this->Supplier_model->getAll($businessId)->result();
			$data['sellers'] = $this->Sellers_model->getAll($businessId)->result();
			$data['customers'] = $this->Customers_model->getAll($businessId)->result();

			$this->load->view('overviews/default', $data);
		}
	}

	public function overviewSold() {
		if (!isLogged()) {
			redirect('login');
		}

		if ($this->uri->total_rsegments() < 4) {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}

		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);
		$customerId = $this->uri->segment(5) ?? 0; // If 0 then generate an overview for all customers.
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		$data['from'] = $from;
		$data['to'] = $to;
		$data['invoices'] = $this->Invoice_model->getBetweenDateC($from, $to, $customerId, $businessId)->result();
		$data['business'] = $business;
		$data['customerId'] = $customerId;

		$this->load->view('overviews/overviewSold', $data);
	}

	public function overviewBought() {
		if (!isLogged()) {
			redirect('login');
		}

		if ($this->uri->total_rsegments() < 4) {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}

		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);
		$supplierId = $this->uri->segment(5) ?? 0; // If 0 then generate an overview for all suppliers.
		$businessId = $this->session->userdata('user')->BusinessId;

		$data['from'] = $from;
		$data['to'] = $to;
		$data['invoices'] = $this->Invoice_model->getBetweenDateS($from, $to, $supplierId, $businessId)->result();
		$data['supplierId'] = $supplierId;

		$this->load->view('overviews/overviewBought', $data);
	}

	public function overviewRepeatingInvoice() {
		if (!isLogged()) {
			redirect('login');
		}
		
		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		$this->load->helper('repeatingInvoice');

		$date = strtotime(date('d-m-Y'));
		$businessId = $this->session->userdata('user')->BusinessId;
		$timePeriod = array() + unserialize(TIMEPERIOD);
		$selected = 0;

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$selected = $_POST['period'];
			$date = strtotime("+ " . $_POST['period'], $date);
		}

		$data['period'] = form_dropdown('period', $timePeriod, $selected, CLASSSELECTBOOTSTRAPSUBMIT);
		$data['repeatingInvoices'] = $this->Customers_model->getAllRepeatingInvoiceBelowDate($date, $businessId)->result();
		$data['date'] = $date;

		$this->load->view('overviews/overviewRepeatingInvoice', $data);
	}

	public function overviewWork() {
		if ($this->uri->total_rsegments() != 5) {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}

		if (!checkModule('ModuleTickets')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}
		
		$from = $this->uri->segment(3);
		$to = $this->uri->segment(4);
		$userId = $this->uri->segment(5);
		$businessId = $this->session->userdata('user')->BusinessId;

		$data['from'] = $from;
		$data['to'] = $to;
		$data['userId'] = $userId;
		$data['activities'] = $this->Tickets_model->getActivityBetweetDate(strtotime($from), strtotime($to), $userId, $businessId)->result();

		$this->load->view('overviews/overviewWork', $data);
	}

	public function overviewOrders() {
		if (!isLogged()) {
			redirect('login');
		}

		if ($this->input->server('REQUEST_METHOD') != 'POST') {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$from = date('Y-m-d', strtotime($_POST['from']));
		$to = date('Y-m-d', strtotime($_POST['to']));
		$sellerId = $_POST['seller'] ?? 0;
		$salesOrders = $this->SalesOrder_model->getOrdersBetweenDateBySellerId($from, $to, $sellerId, $businessId)->result();

		$dataCsv[] = array(
			'Order datum',
			'Klantnaam',
			'Klantadres',
			'Klant PC',
			'Klant plaats',
			'Verkoopkanaal',
			'Vervoerder',
			'Aantal colli'
		);

		foreach ($salesOrders as $salesOrder) {
			$customer = $this->Customers_model->getCustomer($salesOrder->CustomerId, $businessId)->row();
			$seller = $this->Sellers_model->getSeller($salesOrder->Seller_id, $businessId)->row();

			$dataCsv[] = array(
				date('d-m-Y', strtotime($salesOrder->OrderDate)),
				$customer 	!= null ? $customer->Name : $salesOrder->CompanyName ?? $salesOrder->FrontName.' '.$salesOrder->Insertion.' '.$salesOrder->LastName,
				$customer 	!= null ? $customer->StreetName.' '.$customer->StreetNumber.$customer->StreetAddition : $salesOrder->Address.' '.$salesOrder->AddressNumber.$salesOrder->AddressAddition,
				$customer 	!= null ? $customer->ZipCode : $salesOrder->ZipCode,
				$customer 	!= null ? $customer->City : $salesOrder->City,
				$seller 	!= null ? $seller->Name : '',
				getTransporterName($salesOrder->Transport_id),
				(float)$salesOrder->Colli
			);
		}

		if ($sellerId != 0) {
			$filename = 'Orders per verkoopkanaal '.getSellerName($sellerId).' '.$from.' '.$to;
		}
		else {
			$filename = 'Orders per verkoopkanaal (alle) '.$from.' '.$to;
		}
		
		$filename = str_replace(' ', '_', $filename);
		$filename = str_replace('/', '_', $filename);
		$filename = str_replace('.', '_', $filename);
		$filename = ucfirst(strtolower($filename));
		
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

		$fp = fopen('php://output', 'w');

		foreach ($dataCsv as $row) {
			fputcsv($fp, $row);
		}

		fclose($fp);

		// $this->load->view('overviews/overviewBought', $data);
	}

	public function closedTickets() {
		if (!isLogged()) {
			redirect('login');
		}
		
		if (!checkModule('ModuleTickets')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}
		
		$this->load->library('Subquery');

		$businessId = $this->session->userdata('user')->BusinessId;
		$latestStatus = $this->Tickets_statusmodel->getLatestStatus($businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			
		} else {

			$data['tickets'] = $this->Tickets_model->getClosedNotInvoiced($businessId, $latestStatus->Id)->result();

			$this->load->view('overviews/closedTickets', $data);
		}
	}

	public function openTickets() {
		if (!isLogged()) {
			redirect('login');
		}
		
		if (!checkModule('ModuleTickets')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}
		
		$this->load->library('Subquery');

		$businessId = $this->session->userdata('user')->BusinessId;
		$latestStatus = $this->Tickets_statusmodel->getLatestStatus($businessId)->row();
		$postUserId = $this->input->post('userFilter') ? $this->input->post('userFilter') : 'all';

		$data['users'] = $this->Users_model->getAll($businessId)->result();
		$data['tickets'] = $this->Tickets_model->getOpenNotInvoiced($businessId, $latestStatus->Id, $postUserId)->result();
		$data['latestStatus'] = $latestStatus;
		$data['postUserId'] = $postUserId;

		$this->load->view('overviews/openTickets', $data);
	}

	public function openInvoice() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$openInvoices = $this->Invoice_model->getOpenInvoice($businessId)->result();

		$data['invoices'] = $openInvoices;

		$this->load->view('overviews/openInvoice', $data);
	}

	public function stocklist() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$productgroupId = $_POST['productgroup'] == 'alle' ? null : $_POST['productgroup'];
		$supplierId = $_POST['supplier'] == 'alle' ? null : $_POST['supplier'];

		$filename = 'Productlijst_per_' . date('d-m-Y') . '.csv';

	    header('Content-Type: application/excel');
	    header('Content-Disposition: attachment; filename="' . $filename . '"');

		$dataCsv = stockList($businessId, $productgroupId, $supplierId);

		$fp = fopen('php://output', 'w');
		foreach ($dataCsv as $row) {
		    fputcsv($fp, $row, ';');
		}
		fclose($fp);
	}
	
	public function overviewBtw()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->uri->total_rsegments() != 5) {
			$this->session->set_tempdata('err_message', 'Niet alle velden zijn ingevuld', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("overviews");
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

		$businessId = $this->session->userdata('user')->BusinessId;
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
		
		$this->load->view('overviews/overviewBtw', $data);
		
	}

}
