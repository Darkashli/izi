<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->library('woocommerce');
		$this->load->model('tickets/Tickets_model', '', TRUE);
		$this->load->model('tickets/Tickets_statusmodel', '', TRUE);
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('warehouse/Warehouse_model', '', TRUE);
		$this->load->model('productgroup/Productgroup_model', '', TRUE);
		$this->load->model('users/Users_model', '', TRUE);
		$this->load->model('natureofwork/Natureofwork_model', '', TRUE);
		$this->load->model('webshop/Webshop_model', '', TRUE);
		$this->load->model('paymentcondition/Paymentcondition_model', '', TRUE);
		$this->load->model('quotations/Quotation_model', '', TRUE);
		$this->load->model('quotations/Reason_model', '', TRUE);
		$this->load->model('quotations/Defaulttext_model', '', TRUE);
		$this->load->model('quotations/QuotationStatus_model', '', TRUE);
		$this->load->model('years/Year_model', '', TRUE);
		$this->load->helper('ticket');
		$this->load->helper('webshop');
	}

	public function index() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if ($business == null) {
			$this->session->set_tempdata('err_message', 'Dit bedrijf bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/business");
		}

		if (!checkPerm(15) && !isFromBusiness($businessId)) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$conditionFiles = array(
				'ConditionsGeneralPdf' => 'conditionsgeneralpdf',
				 'ConditionsSalesPdf' => 'conditionssalespdf'
			);

			$data = array(
				'Name' => $_POST['name'],
				'StreetName' => $_POST['streetname'],
				'StreetNumber' => $_POST['streetnumber'],
				'StreetAddition' => $_POST['streetaddition'],
				'ZipCode' => $_POST['zipcode'],
				'City' => $_POST['city'],
				'Country' => $_POST['country'],
				'IBAN' => $_POST['iban'],
				'GREK' => $_POST['grek'],
				'KVK' => $_POST['kvk'],
				'BIC' => $_POST['bic'],
				'BTW' => $_POST['btw'],
				'PhoneNumber' => $_POST['phonenumber'],
				'Fax' => $_POST['fax'],
				'Email' => $_POST['email'],
				'Website' => $_POST['website'],
				'InvoiceText' => $_POST['invoicetext'],
				'InvoiceCopyText' => $_POST['invoicecopytext'],
				'InvoiceEmail' => $_POST['invoiceemail'],
				'InvoiceCopyEmail' => $_POST['invoicecopyemail'],
				'ReminderText' => $_POST['remindertext'],
				'DunningText' => $_POST['dunningtext'],
				'WorkEmailTextBC' => $_POST['workemailtextbc'],
				'WorkEmailTextCC' => $_POST['workemailtextcc'],
				'WorkEmailTextCU' => $_POST['workemailtextcu'],
				'NewUserMailText' => $_POST['NewUserMailText'],
				'WorkEmail' => $_POST['workEmail'],
				'QuotationEmailText' => $_POST['quotationemailtext'],
				'SignConfirmationForCustomer' => $_POST['signconfirmationforcustomer'],
				'SignConfirmationForCollaborator' => $_POST['signconfirmationforcollaborator'],
				'OfferConfirmed' => $_POST['offerconfirmed'],
				'CoditionsGeneralText' => $_POST['conditionsgeneraltext'],
				'ConditionsSalesText' => $_POST['conditionssalestext'],
				'ModuleTickets' => $_POST['module_tickets'] ? '1' : '0',
				'ModuleWebsite' => $_POST['module_websites'] ? '1' : '0',
				'ModuleSystemManagement' => isset($_POST['module_systemmanagement']) ? '1' : '0',
				'ModuleTransporters' => isset($_POST['module_transporters']) ? '1' : '0',
				'ModuleSellers' => isset($_POST['module_sellers']) ? '1' : '0',
				'ModulePriceAgreement' => isset($_POST['module_priceagreement']) ? '1' : '0',
				'ModuleRepeatingInvoice' => isset($_POST['module_repeatinginvoices']) ? '1' : '0',
				'ModuleQuotation' => isset($_POST['module_quotation']) ? '1' : '0',
			);

			foreach ($conditionFiles as $key => $conditionFile){
				if ($_FILES[$conditionFile]) {
					$upload_path = "./uploads/$business->DirectoryPrefix/business/condition_general/";
					// Remove the existing image.
					// @unlink("$upload_path");

					if (!file_exists($upload_path)) {
						mkdir($upload_path, 0777, true);
					}
					// $file_exploded = explode('.', $_FILES[$conditionFile]['name']);
					// $file_ext = end($file_exploded);
					// $file_name = "1.$file_ext"; // "1" is for the first image so more than 1 images are possible in the future.
					// Remove any image with the same name and path.
					// $path = "$upload_path$file_name";
					// @unlink($path);
					$config['upload_path'] = "$upload_path";
					$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
					$config['max_size'] = 2097152;
					$config['max_width'] = 7680;
					$config['max_height'] = 4320;
					// $config['file_name'] = $file_name;

					$this->load->library('upload', $config);

					if ($this->upload->do_upload($conditionFile)) {
						$data[$conditionFile] = $_FILES[$conditionFile]['name'];
					}
				}
			}

			$this->Business_model->updateBusiness($data, $businessId);

			$this->session->set_tempdata('err_message', 'Bedrijf succesvol aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);

			redirect('settings');
		} else {
			$data['business'] = $business;

			$this->load->view('settings/updatebusiness', $data);
		}
	}

	public function status() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$data['statuses'] = $this->Tickets_statusmodel->getAll($businessId)->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('settings/status', $data);
	}

	public function updatestatus() {
		if (!isLogged()) {
			redirect('login');
		}

		$statusId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$status = $this->Tickets_statusmodel->getStatus($statusId, $businessId)->row();
		$statusses = $this->Tickets_statusmodel->getAll($businessId)->result();

		if ($status == null) {

			$this->session->set_tempdata('err_message', 'Deze status bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/status");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->db->trans_start();

			$order = 1;

			if ($_POST['after'] == 'begin') {
				$data = array(
					'Color' => $_POST['color'],
					'Description' => $_POST['description'],
					'Order' => $order++,
					'ProgressPercentage' => $_POST['progress_percentage']
				);
				$this->Tickets_statusmodel->updateStatus($data, $statusId);
			}

			foreach ($statusses as $status2) { // NOTE: The variable $status is already used.
				if ($status2->Id == $statusId) {
					continue;
				}

				$data = array(
					'Order' => $order++
				);
				$this->Tickets_statusmodel->updateStatus($data, $status2->Id);

				if ($_POST['after'] == $status2->Id) {
					$data = array(
						'Color' => $_POST['color'],
						'Description' => $_POST['description'],
						'Order' => $order++,
						'ProgressPercentage' => $_POST['progress_percentage']
					);
					$this->Tickets_statusmodel->updateStatus($data, $statusId);
				}
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'Status succesvol aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("settings/status");
		} else {

			$data['status'] = $status;
			$data['statusses'] = $statusses;

			$this->load->view('settings/statusedit', $data);
		}
	}

	public function createstatus() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$statusses = $this->Tickets_statusmodel->getAll($businessId)->result();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->db->trans_start();

			$order = 1;

			if ($_POST['after'] == 'begin') {
				$data = array(
					'Color' => $_POST['color'],
					'Description' => $_POST['description'],
					'Order' => $order++,
					'ProgressPercentage' => $_POST['progress_percentage'],
					'BusinessId' => $businessId
				);
				$this->Tickets_statusmodel->createStatus($data);
			}

			foreach ($statusses as $status) {
				$data = array(
					'Order' => $order++
				);
				$this->Tickets_statusmodel->updateStatus($data, $status->Id);

				if ($_POST['after'] == $status->Id) {
					$data = array(
						'Color' => $_POST['color'],
						'Description' => $_POST['description'],
						'Order' => $order++,
						'ProgressPercentage' => $_POST['progress_percentage'],
						'BusinessId' => $businessId
					);
					$this->Tickets_statusmodel->createStatus($data);
				}
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'Status succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("settings/status");
		} else {

			$data['status'] = (object) array(
				'Color' => '#000000',
				'Description' => '',
				'Order' => 1,
				'ProgressPercentage' => '0.00'
			);
			$data['statusses'] = $statusses;

			$this->load->view('settings/statusedit', $data);
		}
	}

	public function deleteStatus()
	{
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$statusId = $this->uri->segment(3);
		$status = $this->Tickets_statusmodel->getStatus($statusId, $businessId)->row();

		if ($status == null) {
			$this->session->set_tempdata('err_message', 'Deze status bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/status");
		}

		if (!$this->Tickets_statusmodel->deleteStatus($status->Id)){
			$this->session->set_tempdata('err_message', 'De status kon niet worden verwijderd', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
		} else {
			$this->session->set_tempdata('err_message', 'De status is succesvol verwijderd', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
		}
		redirect("settings/status");
	}

	public function warehouse() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$data['warehouses'] = $this->Warehouse_model->getAll($businessId)->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('settings/warehouse', $data);
	}

	public function createwarehouse() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$data = array(
				'Description' => $_POST['description'],
				'Name' => $_POST['name'],
				'BusinessId' => $businessId
			);

			$this->Warehouse_model->createWarehouse($data);

			$this->session->set_tempdata('err_message', 'Magazijn succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("settings/warehouse");
		} else {

			$data['warehouse'] = (object) array(
						'Description' => '',
						'Name' => ''
			);


			$this->load->view('settings/warehouseupdate', $data);
		}
	}

	public function updatewarehouse() {
		if (!isLogged()) {
			redirect('login');
		}

		$warehouseId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$warehouse = $this->Warehouse_model->getWarehouse($warehouseId, $businessId)->row();

		if ($warehouse == null) {

			$this->session->set_tempdata('err_message', 'Dit magazijn bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/warehouse");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$data = array(
				'Description' => $_POST['description'],
				'Name' => $_POST['name']
			);

			$this->Warehouse_model->updateWarehouse($warehouse->Id, $data);

			$this->session->set_tempdata('err_message', 'Magazijn succesvol aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("settings/warehouse");
		} else {

			$data['warehouse'] = $warehouse;

			$this->load->view('settings/warehouseupdate', $data);
		}
	}

	// public function deletewarehouse() {
	//     if (!isLogged()) {
	//         redirect('login');
	//     }
	//     $warehouseId = $this->uri->segment(3);
	//     $businessId = $this->session->userdata('user')->BusinessId;
	//     $warehouse = $this->Warehouse_model->getWarehouse($warehouseId, $businessId)->row();
	//     if ($warehouse == null) {
	//         $this->session->set_tempdata('err_message', 'Dit magazijn bestaat niet', 300);
	//         $this->session->set_tempdata('err_messagetype', 'danger', 300);
	//         redirect("settings/warehouse");
	//     }
	//     $this->Warehouse_model->deleteWarehouse($warehouse->Id);
	//     $this->session->set_tempdata('err_message', 'Magazijn succesvol verwijderd', 300);
	//     $this->session->set_tempdata('err_messagetype', 'success', 300);
	//     redirect('settings/warehouse');
	// }

	public function users() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$data['users'] = $this->Users_model->getAll($businessId)->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('settings/users', $data);
	}

	public function createuser() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			if ($_POST['password'] == $_POST['password2']) {
				$data = array(
					'Username' => strtolower($_POST['username']),
					'FirstName' => $_POST['firstname'],
					'Insertion' => $_POST['insertion'],
					'LastName' => $_POST['lastname'],
					'Mobile' => $_POST['phonenumber'],
					'Email' => $_POST['email'],
					'Level' => 1,
					'Activated' => 1,
					'Salt' => hash("md5", strtolower($_POST['username'])),
					'Password' => hash("sha256", $_POST['password'] . hash("md5", strtolower($_POST['username']))),
					'CustomerManagement' => $_POST['CustomerManagement'],
					'Tab_CContacts' => $_POST['CContacts'],
					'Tab_CSalesOrders' => $_POST['CSalesorders'],
					'Tab_CPurchaseOrders' => $_POST['CPurchaseorders'],
					'Tab_CInvoice' => $_POST['CInvoice'],
					'Tab_CQuotations' => $_POST['CQuotations'],
					'Tab_CWork' => $_POST['CWork'],
					'Tab_CPriceAgr' => $_POST['CPriceAgr'],
					'Tab_CRepeatingInv' => $_POST['CRepeatingInv'],
					'BusinessId' => $businessId
				);

				$userId = $this->Users_model->createUser($data);

				$mailResult = true;
				if ($business->NewUserMailText) {
					$this->load->helper('mail');
					$mailResult = sendNewUser($userId, $_POST['password']);
				}

				$this->session->set_tempdata('err_message', 'Gebruiker succesvol aangemaakt', 300);
				$this->session->set_tempdata('err_messagetype', 'success', 300);
			} else {
				$this->session->set_tempdata('err_message', 'Je opgegeven wachtwoorden komen niet overeen', 300);
				$this->session->set_tempdata('err_messagetype', 'danger', 300);
			}
			redirect("settings/users");
			} else {

			$data['user'] = (object) array(
						'Username' => '',
						'FirstName' => '',
						'Insertion' => '',
						'LastName' => '',
						'Mobile' => '',
						'Email' => '',
						'CustomerManagement' => '',
						'Tab_CContacts' => '',
						'Tab_CSalesOrders' => '',
						'Tab_CPurchaseOrders' => '',
						'Tab_CInvoice' => '',
						'Tab_CQuotations' => '',
						'Tab_CWork' => '',
						'Tab_CPriceAgr' => '',
						'Tab_CRepeatingInv' => ''
			);

			$this->load->view('settings/updateuser', $data);
		}
	}

	public function updateUser() {
		if (!isLogged()) {
			redirect('login');
		}

		$userId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$user = $this->Users_model->getUser($userId, $businessId)->row();

		if ($user == null) {
			$this->session->set_tempdata('err_message', 'Deze gebruiker bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/users");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data = array(
				'Activated' => $_POST['active'] ? 1 : 0,
				'Username' => $_POST['username'],
				'FirstName' => $_POST['firstname'],
				'Insertion' => $_POST['insertion'],
				'LastName' => $_POST['lastname'],
				'Mobile' => $_POST['phonenumber'],
				'Email' => $_POST['email'],
				'CustomerManagement' => $_POST['CustomerManagement'],
				'Tab_CContacts' => $_POST['CContacts'],
				'Tab_CSalesOrders' => $_POST['CSalesorders'],
				'Tab_CPurchaseOrders' => $_POST['CPurchaseorders'],
				'Tab_CInvoice' => $_POST['CInvoice'],
				'Tab_CQuotations' => $_POST['CQuotations'],
				'Tab_CWork' => $_POST['CWork'],
				'Tab_CPriceAgr' => $_POST['CPriceAgr'],
				'Tab_CRepeatingInv' => $_POST['CRepeatingInv']
			);

			if (!empty($_POST['password']) || !empty($_POST['password2'])) {
				if ($_POST['password'] != $_POST['password2']) {
					$this->session->set_tempdata('err_message', 'Je opgegeven wachtwoorden komen niet overeen', 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
					redirect("settings/users");
				} else {
					$salt = hash("md5", strtolower($_POST['username']));
					$password = hash("sha256", $_POST['password'] . $salt);
					$data['Salt'] = $salt;
					$data['Password'] = $password;
				}
			}

			$this->Users_model->updateUser($userId, $data);

			$this->session->set_tempdata('err_message', 'Gebruiker succesvol aangepast.', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("settings/users");
		} else {
			$data['user'] = $user;
			$this->load->view('settings/updateuser', $data);
		}
	}

	public function productgroups() {
		if (!isLogged()) {
			redirect('login');
		}

		$this->load->helper('productgroup');

		$businessId = $this->session->userdata('user')->BusinessId;

		$data['productgroups'] = $this->Productgroup_model->getAll($businessId)->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('settings/productgroups', $data);
	}

	public function creategroup() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$data = array(
				'Description' => $_POST['description'],
				'Name' => $_POST['name'],
				'ParentId' => $_POST['parent_productgroup'],
				'BusinessId' => $businessId
			);
			if (hasWebshop()) {
				$data['IsShop'] = $_POST['is_shop'];
			}
			$productGroupId = $this->Productgroup_model->createProductgroup($data);

			$this->session->set_tempdata('err_message', 'De productgroep is succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);

			if ($_FILES['image']['name'] != ''){
				// TODO: Create a general helper function for uploading files.
				$upload_path = "./uploads/$business->DirectoryPrefix/productgroups/$productGroupId/";
				if (!file_exists($upload_path)) {
					mkdir($upload_path, 0777, true);
				}
				$file_exploded = explode('.', $_FILES['image']['name']);
				$file_ext = end($file_exploded);
				$file_name = "1.$file_ext"; // "1" is for the first image so more than 1 images are possible in the future.
				// Remove any image with the same name and path.
				$path = "$upload_path$file_name";
				@unlink($path);

				$config['upload_path'] = $upload_path;
				$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
				$config['max_size'] = 2097152;
				$config['max_width'] = 7680;
				$config['max_height'] = 4320;
				$config['file_name'] = $file_name;
				$this->load->library('upload', $config);

				if ($this->upload->do_upload('image')){
					$data = array('image' => $file_name);
					$this->Productgroup_model->updateProductgroup($productGroupId, $data);
				} else{
					$this->session->set_tempdata('err_message', 'De productgroep is opgeslagen maarde afbeelding kon niet worden geüpload: ' . $this->upload->display_errors(), 300);
					$this->session->set_tempdata('err_messagetype', 'warning', 300);
				}
			}

			// Save to webshop
			if (hasWebshop() && $_POST['is_shop'] == 1) {
				$this->woocommerce->saveProductgroup($productGroupId);
			}

			redirect("settings/productgroups");
		} else {

			$data['parentProductgroups'] = $this->Productgroup_model->getAll($businessId)->result();
			$data['productgroup'] = (object) array(
				'Id' => null,
				'Description' => '',
				'Name' => '',
				'Image' => '',
				'ParentId' => null,
				'IsShop' => 0,
				'ShopId' => null
			);


			$this->load->view('settings/updategroup', $data);
		}
	}

	public function editgroup() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();
		$productgroupId = $this->uri->segment(3);
		$productGroup = $this->Productgroup_model->getProductgroup($productgroupId, $businessId)->row();

		if ($productGroup == null) {
			$this->session->set_tempdata('err_message', 'Deze productgroep bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/productgroups");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$data = array(
				'Name' => $_POST['name'],
				'Description' => $_POST['description'],
				'ParentId' => $_POST['parent_productgroup']
			);
			if (hasWebshop()) {
				$data['IsShop'] = $_POST['is_shop'];
			}

			if ($_FILES['image']['name'] != NULL) {
				$upload_path = "./uploads/$business->DirectoryPrefix/productgroups/$productGroup->Id/";
				// Remove the existing image.
				@unlink("$upload_path$productGroup->Image");

				if (!file_exists($upload_path)) {
					mkdir($upload_path, 0777, true);
				}
				$file_exploded = explode('.', $_FILES['image']['name']);
				$file_ext = end($file_exploded);
				$file_name = "1.$file_ext"; // "1" is for the first image so more than 1 images are possible in the future.
				// Remove any image with the same name and path.
				$path = "$upload_path$file_name";
				@unlink($path);
				$config['upload_path'] = "$upload_path";
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = 2097152;
				$config['max_width'] = 7680;
				$config['max_height'] = 4320;
				$config['file_name'] = $file_name;

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('image')){
					$data['image'] = $file_name;
				}
			}

			$this->Productgroup_model->updateProductgroup($productGroup->Id, $data);

			// Save to webshop
			if (hasWebshop()) {
				if ($_POST['is_shop'] == 1) {
					$this->woocommerce->saveProductgroup($productgroupId);
				} else {
					if ($productGroup->ShopId != null) {
						$this->woocommerce->deleteProductgroup($productgroupId);
					}
				}
			}

			$this->session->set_tempdata('err_message', 'De productgroep is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/productgroups');
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$data['business'] = $business;
		$data['parentProductgroups'] = $this->Productgroup_model->getAll($businessId)->result();
		$data['productgroup'] = $productGroup;

		$this->load->view('settings/updategroup', $data);
	}

	public function natureofwork() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$data['natureofwork'] = $this->Natureofwork_model->getAll($businessId)->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('settings/natureofwork', $data);
	}

	public function createnatureofwork() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$data = array(
				'Description' => $_POST['description'],
				'BusinessId' => $businessId
			);

			$this->Natureofwork_model->createNatureofwork($data);

			$this->session->set_tempdata('err_message', 'Aard succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("settings/natureofwork");
		} else {

			$data['natureofwork'] = (object) array(
						'Description' => '',
						'Name' => ''
			);


			$this->load->view('settings/updatenatureofwork', $data);
		}
	}

	public function editnatureofwork() {
		if (!isLogged()) {
			redirect('login');
		}

		$natureofworkId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;


		$natureofwork = $this->Natureofwork_model->getNatureofwork($natureofworkId, $businessId)->row();

		if ($natureofwork == null) {

			$this->session->set_tempdata('err_message', 'Deze aard is niet gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/natureofwork");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data = array(
				'Description' => $_POST['description']
			);

			$this->Natureofwork_model->updateNatureofwork($natureofwork->Id, $data);

			$this->session->set_tempdata('err_message', 'De aard is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/natureofwork');
		} else {

			$data['natureofwork'] = $natureofwork;

			$this->load->view('settings/updatenatureofwork', $data);
		}
	}

	private function pliegerCSV($csvFile) {
		$rawCSV = fopen($csvFile, "r");
		$businessId = $this->session->userdata('user')->BusinessId;
		$count = 0;

		while (!feof($rawCSV)) {

			$linearr = fgetcsv($rawCSV, 1000, ',', '"');
			if (!is_array($linearr)) {
				continue;
			}
			foreach ($linearr as $line) {
				$linarrS = str_getcsv($line);

				$articleNumber = $linarrS[0];
				$description = $linarrS[5];
				$price = $linarrS[7];

				/* echo "Artikelnummer: " . $articleNumber . '<br />';
				  echo "Omschrijving: " . $description . '<br />';
				  echo "Prijs: " . $price . "<br />";
				  echo "<hr>"; */

				$product = $this->Product_model->getProductByArticleNumber($articleNumber, $businessId)->row();

				if ($product != null) {
					// Product bestaat al!
					$data = array(
						'SalesPrice' => $price
					);

					$this->Product_model->updateProduct($product->Id, $data);
				} else {
					// Product bestaat nog niet
					$data = array(
						'ArticleNumber' => $articleNumber,
						'Description' => $description,
						'SalesPrice' => $price,
						'BTW' => "21",
						'BusinessId' => $businessId
					);

					$this->Product_model->createProduct($data);
				}

				$count++;

				//print_r($linarrS);
			}
			//print_r($linearr);
		}

		fclose($rawCSV);

		return $count;

		/* $this->load->helper('Csv');

		  $array = csv_to_array($csvFile);

		  print_r($array); */
	}

	private function smokaXLS($xlsFile) {
		$businessId = $this->session->userdata('user')->BusinessId;
		//load the excel library
		$this->load->library('excel');

		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($xlsFile);

		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
		//var_dump($sheetData);

		$count = 0;
		$total = 0;

		foreach ($sheetData as $row) {
			if ($count < 8) {
				$count++;
				continue;
			}

			$articleNumber = $row[0];
			$description = $row[1];
			$price = $row[2];

			/* echo "Artikelnummer: " . $articleNumber . '<br />';
			  echo "Omschrijving: " . $description . '<br />';
			  echo "Prijs: " . $price . "<br />";
			  echo "<hr>"; */

			$product = $this->Product_model->getProductByArticleNumber($articleNumber, $businessId)->row();

			if ($product != null) {
				// Product bestaat al!
				$data = array(
					'SalesPrice' => $price
				);

				$this->Product_model->updateProduct($product->Id, $data);
			} else {
				// Product bestaat nog niet
				$data = array(
					'ArticleNumber' => $articleNumber,
					'Description' => $description,
					'SalesPrice' => $price,
					'BTW' => "21",
					'BusinessId' => $businessId
				);

				$this->Product_model->createProduct($data);
			}

			$total++;

			$count++;
		}

		return $total;

		/* //extract to a PHP readable array format
		  foreach ($cell_collection as $cell) {
		  $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		  $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		  $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		  //header will/should be in row 1 only. of course this can be modified to suit your need.
		  if ($row < 8)
		  continue;

		  if ($row == 8) {
		  $header[$row][$column] = $data_value;
		  } else {
		  $arr_data[$row][$column] = $data_value;
		  }
		  }

		  $data['header'] = $header;
		  $data['values'] = $arr_data;

		  print_r($header);

		  print_r($arr_data); */
	}

	public function business() {
		if (!isLogged()) {
			redirect('login');
		}

		$data['businesses'] = $this->Business_model->getAll()->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('settings/business', $data);
	}

	public function createbusiness() {
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkPerm(15)) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
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
				'GREK' => $_POST['grek'],
				'KVK' => $_POST['kvk'],
				'BIC' => $_POST['bic'],
				'BTW' => $_POST['btw'],
				'PhoneNumber' => $_POST['phonenumber'],
				'Fax' => $_POST['fax'],
				'Email' => $_POST['email'],
				'Website' => $_POST['website'],
				'InvoiceText' => $_POST['invoicetext'],
				'InvoiceEmail' => $_POST['invoiceemail'],
				'ReminderText' => $_POST['remindertext'],
				'DunningText' => $_POST['dunningtext'],
				'WorkEmailTextBC' => $_POST['workemailtextbc'],
				'WorkEmailTextCC' => $_POST['workemailtextcc'],
				'WorkEmailTextCU' => $_POST['workemailtextcu'],
				'NewUserMailText' => $_POST['NewUserMailText'],
				'WorkEmail' => $_POST['workEmail'],
				'QuotationEmailText' => $_POST['quotationemailtext'],
				'SignConfirmationForCustomer' => $_POST['signconfirmationforcustomer'],
				'SignConfirmationForCollaborator' => $_POST['signconfirmationforcollaborator'],
				'OfferConfirmed' => $_POST['offerconfirmed']
			);

			$this->Business_model->createBusiness($data);

			$this->session->set_tempdata('err_message', 'Bedrijf succesvol toegevoegd', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/business');
		} else {

			$data['business'] = (object) array(
				'Name' => null,
				'StreetName' => null,
				'StreetNumber' => null,
				'StreetAddition' => null,
				'ZipCode' => null,
				'City' => null,
				'Country' => null,
				'ModuleTickets' => 0,
				'ModuleWebsite' => 0,
				'ModuleSystemManagement' => 0,
				'ModuleTransporters' => 0,
				'ModuleSellers' => 0,
				'ModulePriceAgreement' => 0,
				'ModuleRepeatingInvoice' => 0,
				'ModuleQuotation' => 0,
				'InvoiceText' => null,
				'InvoiceCopyText' => null,
				'ReminderText' => null,
				'DunningText' => null,
				'NewUserMailText' => null,
				'PhoneNumber' => null,
				'Fax' => null,
				'Email' => null,
				'Website' => null,
				'IBAN' => null,
				'BTW' => null,
				'KVK' => null,
				'BIC' => null,
				'GREK' => null,
				'InvoiceEmail' => null,
				'InvoiceCopyEmail' => null,
				'WorkEmail' => null,
				'WorkEmailTextBC' => null,
				'WorkEmailTextCC' => null,
				'WorkEmailTextCU' => null,
				'QuotationEmailText' => null,
				'SignConfirmationForCustomer' => null,
				'SignConfirmationForCollaborator' => null,
				'OfferConfirmed' => null
			);
			$this->load->view('settings/updatebusiness', $data);
		}
	}

	public function updatebusiness() {
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->uri->segment(3);

		$business = $this->Business_model->getBusiness($businessId)->row();

		if ($business == null) {
			$this->session->set_tempdata('err_message', 'Dit bedrijf bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/business");
		}

		if (!checkPerm(15) && !isFromBusiness($businessId)) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
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
				'GREK' => $_POST['grek'],
				'KVK' => $_POST['kvk'],
				'BIC' => $_POST['bic'],
				'BTW' => $_POST['btw'],
				'PhoneNumber' => $_POST['phonenumber'],
				'Fax' => $_POST['fax'],
				'Email' => $_POST['email'],
				'Website' => $_POST['website'],
				'InvoiceText' => $_POST['invoicetext'],
				'InvoiceEmail' => $_POST['invoiceemail'],
				'ReminderText' => $_POST['remindertext'],
				'DunningText' => $_POST['dunningtext'],
				'WorkEmailTextBC' => $_POST['workemailtextbc'],
				'WorkEmailTextCC' => $_POST['workemailtextcc'],
				'WorkEmailTextCU' => $_POST['workemailtextcu'],
				'NewUserMailText' => $_POST['NewUserMailText'],
				'WorkEmail' => $_POST['workEmail'],
				'QuotationEmailText' => $_POST['quotationemailtext'],
				'SignConfirmationForCustomer' => $_POST['signconfirmationforcustomer'],
				'SignConfirmationForCollaborator' => $_POST['signconfirmationforcollaborator'],
				'OfferConfirmed' => $_POST['offerconfirmed']
			);

			$this->Business_model->updateBusiness($data, $businessId);

			$this->session->set_tempdata('err_message', 'Bedrijf succesvol aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/business');
		} else {
			$data['business'] = $business;

			$this->load->view('settings/updatebusiness', $data);
		}
	}

	public function webshop(){
		if (!isLogged()) {
			redirect('login');
		}

		$this->load->helper('webshop');

		$businessId = $this->session->userdata('user')->BusinessId;
		$webshop = $this->Webshop_model->getWebshop($businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$data = array(
				'Description' => $_POST['description'],
				'Url' => $_POST['url'],
				'ApiKey' => $_POST['api_key'],
				'Secret' => $_POST['secret'],
				'OrderFormat' => !empty($_POST['incoming_orders']) ? $_POST['order_format'] : null,
				'Active' => $_POST['active'] == 'on' ? 1 : 0,
				'BusinessId' => $businessId
			);

			if ($webshop == NULL) {
				$webshopId = $this->Webshop_model->createWebshop($data);
			}
			else{
				$webshopId = $webshop->Id;
				$this->Webshop_model->updateWebshop($webshopId, $data);
			}

			$this->session->set_tempdata('err_message', 'De webshop instellingen zijn succesvol opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);

			if ($_POST['active'] == 'on') {
				$woocommerce = new Woocommerce;
				if (!$woocommerce->testConn()) {
					$this->session->set_tempdata('err_message', 'De webshop instellingen zijn opgeslagen maar er kon geen verbinding worden gemaakt met de webshop', 300);
					$this->session->set_tempdata('err_messagetype', 'warning', 300);

					$data = array('Active' => 0);
					$this->Webshop_model->updateWebshop($webshopId, $data);
				}
				else {
					$this->session->set_tempdata('err_message', 'De webshop instellingen zijn succesvol opgeslagen en er is verbinding gemaakt met de webshop', 300);
					$this->session->set_tempdata('err_messagetype', 'success', 300);
				}
			}
			redirect('settings/webshop');

		}

		if ($this->session->tempdata('err_message')){
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		if ($webshop == NULL) {
			$webshop = (object) array(
				'Description' => '',
				'Url' => '',
				'ApiKey' => '',
				'Secret' => '',
				'OrderFormat' => null,
				'Active' => '1'
			);
		}

		$data['webshop'] = $webshop;
		$this->load->view('settings/webshop', $data);
	}

	public function paymentconditions()
	{
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$data['paymentconditions'] = $this->Paymentcondition_model->getAll($businessId)->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$this->load->view('settings/paymentconditions', $data);
	}

	public function createpaymentconditoin($value='')
	{
		if (!isLogged()) {
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$data = array(
				'Name' => $_POST['name'],
				'BusinessId' => $businessId
			);

			$this->Paymentcondition_model->createPaymentCondition($data);

			$this->session->set_tempdata('err_message', 'Betaalmethode succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("settings/paymentconditions");
		}
		else{
			$paymentconditoin = (object) array(
				'Name' => NULL,
			);
			$data['paymentcondition'] = $paymentconditoin;
			$this->load->view('settings/updatepaymentcondition', $data);
		}
	}

	public function editpaymentcondition() {
		if (!isLogged()) {
			redirect('login');
		}

		$paymentconditionId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$paymentcondition = $this->Paymentcondition_model->getPaymentCondition($paymentconditionId, $businessId)->row();

		if ($paymentcondition == null) {
			$this->session->set_tempdata('err_message', 'Deze betaalconditie is niet gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/paymentconditions");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data = array(
				'Name' => $_POST['name']
			);

			$this->Paymentcondition_model->updatePaymentCondition($paymentcondition->Id, $data);

			$this->session->set_tempdata('err_message', 'De betaalconditie is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/paymentconditions');
		} else {

			$data['paymentcondition'] = $paymentcondition;

			$this->load->view('settings/updatepaymentcondition', $data);
		}
	}

	public function quotations()
	{
		if (!isLogged()){
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')){
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		$reasons = $this->Reason_model->getAll($businessId)->result();
		$defaulttexts = $this->Defaulttext_model->getAll($businessId)->result();
		$statusses = $this->QuotationStatus_model->getAll($businessId)->result();

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$data['reasons'] = $reasons;
		$data['defaulttexts'] = $defaulttexts;
		$data['statusses'] = $statusses;

		$this->load->view('settings/quotations', $data);
	}

	public function createreason()
	{
		if (!isLogged()){
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')){
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['Description'] = $_POST['description'];
			$data['BusinessId'] = $businessId;

			$this->Reason_model->create($data);

			$this->session->set_tempdata('err_message', 'De aanleiding is aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/quotations');
		}

		else
		{
			$data['reason'] = (object) array(
				'Description' => null
			);
			$this->load->view('settings/editreason', $data);
		}
	}

	public function updatereason()
	{
		if (!isLogged()){
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')){
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$reasonId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$reason = $this->Reason_model->get($reasonId, $businessId)->row();

		if ($reason == null) {
			$this->session->set_tempdata('err_message', 'Deze aanleiding is niet gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/quotations");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['Description'] = $_POST['description'];

			$this->Reason_model->update($reasonId, $data);

			$this->session->set_tempdata('err_message', 'De aanleiding is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/quotations');
		}

		else
		{
			$data['reason'] = $reason;

			$this->load->view('settings/editreason', $data);
		}
	}

	public function createtext()
	{
		if (!isLogged()){
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')){
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['Titel'] = $_POST['titel'];
			$data['Text'] = $_POST['defaultText'];
			$data['BusinessId'] = $businessId;

			$this->Defaulttext_model->create($data);

			$this->session->set_tempdata('err_message', 'De standaard tekst is aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/quotations');
		}

		else
		{
			$data['text'] = (object) array(
				'Titel' => null,
				'Text' => null
			);
			$this->load->view('settings/edittext', $data);
		}
	}

	public function updatetext()
	{
		if (!isLogged()){
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')){
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$textId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$text = $this->Defaulttext_model->get($textId, $businessId)->row();

		if ($text == null) {
			$this->session->set_tempdata('err_message', 'Deze standaard tekst is niet gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("settings/quotations");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['Titel'] = $_POST['titel'];
			$data['Text'] = $_POST['defaultText'];



			$this->Defaulttext_model->update($textId, $data);

			$this->session->set_tempdata('err_message', 'De standaard tekst is aangepast', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/quotations');
		}

		else
		{
			$data['text'] = $text;

			$this->load->view('settings/edittext', $data);
		}
	}

	public function createQuotationStatus()
	{
		if (!isLogged()){
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')){
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$statusses = $this->QuotationStatus_model->getAll($businessId)->result();

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$this->db->trans_start();

			$order = 1;

			if ($_POST['after'] == 'begin') {
				$data = array(
					'Description' => $_POST['description'],
					'SortingOrder' => $order++,
					'BusinessId' => $businessId
				);
				$this->QuotationStatus_model->create($data);
			}

			foreach ($statusses as $status) {
				$data = array(
					'SortingOrder' => $order++
				);
				$this->QuotationStatus_model->update($status->Id, $data);

				if ($_POST['after'] == $status->Id) {
					$data = array(
						'Description' => $_POST['description'],
						'SortingOrder' => $order++,
						'BusinessId' => $businessId
					);
					$this->QuotationStatus_model->create($data);
				}

			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De status is succesvol opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/quotations');
		}

		$data['status'] = (object) array(
			'Description' => null,
			'SortingOrder' => 1
		);
		$data['statusses'] = $statusses;

		$this->load->view('settings/editquotationstatus', $data);
	}

	public function updateQuotationStatus()
	{
		if (!isLogged()){
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')){
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$statusId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$status = $this->QuotationStatus_model->get($statusId, $businessId)->row();
		$statusses = $this->QuotationStatus_model->getAll($businessId)->result();

		if ($status == null) {
			$this->session->set_tempdata('err_message', 'Deze status bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('settings/quotations');
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$this->db->trans_start();

			$order = 1;

			if ($_POST['after'] == 'begin') {
				$data = array(
					'Description' => $_POST['description'],
					'SortingOrder' => $order++
				);
				$this->QuotationStatus_model->update($statusId, $data);
			}

			foreach ($statusses as $status2) { // NOTE: The variable $status is already used.
				if ($status2->Id == $statusId) {
					continue;
				}

				$data = array(
					'SortingOrder' => $order++
				);
				$this->QuotationStatus_model->update($status2->Id, $data);

				if ($_POST['after'] == $status2->Id) {
					$data = array(
						'Description' => $_POST['description'],
						'SortingOrder' => $order++
					);
					$this->QuotationStatus_model->update($statusId, $data);
				}

			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De status is succesvol opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('settings/quotations');
		}

		$data['status'] = $status;
		$data['statusses'] = $statusses;

		$this->load->view('settings/editquotationstatus', $data);
	}

	public function year()
	{
		if (!isLogged())
		{
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;

		if ($this->input->server('REQUEST_METHOD') == 'POST'){

			$year = $this->Year_model->getByYear($_POST['year'], $businessId)->row();

			if ($year != null) {
				$data['tpl_msg'] = 'Dit boekjaar bestaat al';
				$data['tpl_msgtype'] = 'danger';
			}
			else {
				$data = array(
					'Year' => $_POST['year'],
					'BusinessId' => $businessId
				);
				$this->Year_model->create($data);

				unset($data);

				$data['tpl_msg'] = 'het boekjaar is succesvol toegevoegd';
				$data['tpl_msgtype'] = 'success';
			}

		}

		$data['years'] = $this->Year_model->getAll($businessId)->result();

		$this->load->view('settings/years', $data);

	}
}
