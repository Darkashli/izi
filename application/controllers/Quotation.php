<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('customers/Customers_contactsmodel', '', TRUE);
		$this->load->model('customers/Customers_invoicesmodel', '', TRUE);
		$this->load->model('quotations/Quotation_model', '', TRUE);
		$this->load->model('quotations/Reason_model', '', TRUE);
		$this->load->model('quotations/Defaulttext_model', '', TRUE);
		$this->load->model('quotations/QuotationStatus_model', '', TRUE);
		$this->load->model('paymentcondition/Paymentcondition_model', '', TRUE);
		$this->load->model('invoices/Invoice_model', '', TRUE);
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('users/Users_model', '', TRUE);
		$this->load->model('transporter/Transporter_model', '', TRUE);
		$this->load->model('sellers/Sellers_model', '', TRUE);
		$this->load->model('salesorders/SalesOrder_model', '', TRUE);
		$this->load->model('domains/Domain_model', '', TRUE);
		$this->load->helper('user');
		$this->load->helper('quotation');
		$this->load->helper('product');
		$this->load->helper('domain');
		$this->load->helper('customfield');
	}

	public function create()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();

		$customerId = $this->uri->segment(3);
		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null) {
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/quotation");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->db->trans_start();

			$contactId = $_POST['contact'];
			$contact = $this->Customers_contactsmodel->getContact($contactId)->row();


			// Increment new quotation number by 1.
			$quotationNumber = ($business->QuotationNumber + 1);
			$quotationCode = date("Y").'-'.sprintf('%04d', $quotationNumber);


			$data = array(
				'UserId' => $this->session->userdata('user')->Id,
				'CreatorName' => $_POST['creator_name'],
				'CustomerId' => $customerId,
				'CustomerName' => $customer->Name,
				'CustomerStreet' => $customer->StreetName,
				'CustomerHousenumber' => $customer->StreetNumber,
				'CustomerHousenumberAddition' => $customer->StreetAddition,
				'CustomerZipCode' => $customer->ZipCode,
				'CustomerCity' => $customer->City,
				'CustomerCountry' => $customer->Country,
				'CustomerMailaddress' => $customer->Email,
				'CreatedDate' => date('Y-m-d', strtotime($_POST['date'])),
				'ContactId' => $contactId,
				'ContactFirstName' => $contact != null ? $contact->FirstName : null,
				'ContactInsertion' => $contact != null ? $contact->Insertion : null,
				'ContactLastName' => $contact != null ? $contact->LastName : null,
				'ContactSex' => $contact != null ? $contact->Sex : null,
				'ContactSalutation' => $contact != null ? $contact->Salutation : null,
				'QuotationNumber' => 'O'.$quotationCode,
				'Subject' => $_POST['subject'],
				'Reason' => $_POST['reason'],
				'ContactDate' => date('Y-m-d', strtotime($_POST['dateContact'])),
				'WorkDescription' => $_POST['description'],
				'WorkAmount' => $_POST['price'],
				'WorkArticleC' => $_POST['work_articlenumber'],
				'ProductDescription' => $_POST['product_description'],
				'RecurringDescription' => $_POST['recurring_description'],
				'RecurringTimePeriod' => $_POST['period'],
				'ProjectDescription' => $_POST['projectDescription'],
				'ValidDays' => $_POST['validity'],
				'DeliveryTime' => $_POST['time'],
				'PaymentConditionId' => $_POST['payment'],
				'PaymentTerm' => $_POST['paymentTerm'],
				'IsComparison' => !empty($_POST['comparison']) ? 1 : 0,
				'CurrentSituationAndAdvice' => $_POST['currentsituationandadvice'],
				'Template' => !empty($_POST['template']) ? $_POST['template'] : null,
				'BusinessId' => $businessId
			);

			$quotationId = $this->Quotation_model->create($data);

			$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();

			if (!empty($_FILES)) {
				$statusPdf = $_FILES['upload']['name'];
				$statusNames = $_POST['FileDescription'];

				$path = "";
				$upload_path = "./uploads/$business->DirectoryPrefix/business/status/$quotation->Id/";

				foreach ($statusPdf as $key => $status){

					$name = $_FILES['upload']['name'][$key];

					$path = $upload_path . $name;

					if (!file_exists($upload_path)) {
						mkdir($upload_path, 0777, true);
					}

					if (!empty($_POST['FileDescription'][$key]) && isset($_POST['FileDescription'][$key])) {
                        $displayName = $_POST['FileDescription'][$key];
					} else {
                        $displayName = $name;
                    }

					move_uploaded_file($_FILES["upload"]["tmp_name"][$key], $path);
					$dataF = array(
						'Name' => $_FILES['upload']['name'][$key],
						'DisplayFileName' => $displayName,
						'QuotationId' => $quotationId,
						'BusinessId' =>  $businessId
					);


					$this->Quotation_model->createQuotationFile($dataF);
				}
			}

			// Save data for products.
			foreach ($_POST['product_number'] as $num) {
				$dataR = array(
					'BusinessId' => $businessId,
					'QuotationId' => $quotationId,
					'ArticleC' => $_POST['product_articlenumber'.$num],
					'EanCode' => $_POST['product_ean_code'.$num],
					'ArticleDescription' => $_POST['product_articledescription'.$num],
					'Amount' => $_POST['product_amount'.$num],
					'SalesPrice' => $_POST['product_salesprice'.$num],
					'Discount' => $_POST['product_discount'.$num],
					'Tax' => $_POST['product_tax'.$num],
					'Type' => 1 // = Product.
				);
				$this->Quotation_model->createRule($dataR);
			}
			// Save data for repeating.
			foreach ($_POST['repeating_number'] as $num) {
				$dataR = array(
					'BusinessId' => $businessId,
					'QuotationId' => $quotationId,
					'ArticleC' => $_POST['repeating_articlenumber'.$num],
					'EanCode' => $_POST['repeating_ean_code'.$num],
					'ArticleDescription' => $_POST['repeating_articledescription'.$num],
					'Amount' => $_POST['repeating_amount'.$num],
					'SalesPrice' => $_POST['repeating_salesprice'.$num],
					'Discount' => $_POST['repeating_discount'.$num],
					'Tax' => $_POST['repeating_tax'.$num],
					'Type' => 2 // = Repeating.
				);
				$this->Quotation_model->createRule($dataR);
			}

			// Save new quotation number.
			$dataB = array(
				'QuotationNumber' => $quotationNumber
			);
			$this->Business_model->updateBusiness($dataB, $businessId);

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De offerte is opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('customers/quotations/'.$customerId);
		} else {
			$userId = $this->session->userdata('user')->Id;
			$defaulttexts = $this->Defaulttext_model->getAll($businessId)->result();
			$reasons = $this->Reason_model->getAll($businessId)->result();
			$paymentConditions = $this->Paymentcondition_model->getAll($businessId)->result();
			$contacts = $this->Customers_contactsmodel->getCustomerEmployed($customerId, $businessId)->result();
			$users = getUserDropdown($businessId);

			if ($reasons == null) {
				$this->session->set_tempdata('err_message', 'Er zijn geen aanleidingen beschikbaar', 300);
				$this->session->set_tempdata('err_messagetype', 'danger', 300);
				redirect('customers/quotations/'.$customerId);
			}

			if ($contacts == null) {
				$this->session->set_tempdata('err_message', 'Er zijn geen contactpersonen beschikbaar', 300);
				$this->session->set_tempdata('err_messagetype', 'danger', 300);
				redirect('customers/quotations/'.$customerId);
			}

			// if ($handle = opendir(APPPATH . "views/pdf/{$business->DirectoryPrefix}/")) {
			// 	$prefix = 'quotation_';
			// 	while (false !== $file = readdir($handle)) {
			// 		if ($file != '.' && $file != '..' && substr($file, 0, strlen($prefix)) === $prefix) {
			// 			var_dump($file);
			// 		}
			// 	}
			// }
			// die('end');

			$userName = getUserFullName($this->session->userdata('user')->Id);

			$data['customer'] = $customer;
			$data['contacts'] = $contacts;
			$data['reasons'] = $reasons;
			$data['defaulttexts'] = $defaulttexts;
			$data['paymentConditions'] = $paymentConditions;
			$data['users'] = $users;
			$data['quotation'] = (object) array(
				'CreatorName' => $userName,
				'CreatedDate' => date('Y-m-d'),
				'ContactId' => null,
				'Subject' => null,
				'ContactDate' => date('Y-m-d'),
				'WorkDescription' => null,
				'WorkAmount' => null,
				'WorkArticleC' => null,
				'ProductDescription' => null,
				'RecurringDescription' => null,
				'ProjectDescription' => null,
				'ValidDays' => 14,
				'DeliveryTime' => null,
				'PaymentConditionId' => null,
				'PaymentTerm' => null,
				'IsComparison' => null,
				'Template' => null,
				'CurrentSituationAndAdvice' => null
			);
			if ($business->DirectoryPrefix == 'commpro') {
				$data['templates'] = (object) array(
					'commpro' => 'CommPro',
					'cebonline' => 'CEB Online'
				);
			}
			$data['period'] = form_dropdown('period', unserialize(TIMEPERIOD), '', CLASSDROPDOWN);
			$data['customFields'] = array();

			$this->load->view('quotations/editQuotation', $data);
		}
	}

	public function update()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$quotationId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();
		$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();


		if ($quotation == null) {
			$this->session->set_tempdata('err_message', 'Deze offerte bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/quotation");
		}

		$customer = $this->Customers_model->getCustomer($quotation->CustomerId, $businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$this->db->trans_start();

			if (!empty($_FILES)) {
				$statusPdf = $_FILES['upload']['name'];
				$statusNames = $_POST['FileDescription'];

				$path = "";
				$upload_path = "./uploads/$business->DirectoryPrefix/business/status/$quotation->Id/";

				foreach ($statusPdf as $key => $status){

					$name = $_FILES['upload']['name'][$key];

					$path = $upload_path . $name;

					if (!file_exists($upload_path)) {
						mkdir($upload_path, 0777, true);
					}

					if (!empty($_POST['FileDescription'][$key]) && isset($_POST['FileDescription'][$key])) {
                        $displayName = $_POST['FileDescription'][$key];
					} else {
                        $displayName = $name;
                    }

					move_uploaded_file($_FILES["upload"]["tmp_name"][$key], $path);
					$dataF = array(
						'Name' => $_FILES['upload']['name'][$key],
						'DisplayFileName' => $displayName,
						'QuotationId' => $quotationId,
						'BusinessId' =>  $businessId
					);

					$this->Quotation_model->createQuotationFile($dataF);
				}
			}


			if ($customer != null) {
				$contactId = $_POST['contact'];
				$contact = $this->Customers_contactsmodel->getContact($contactId)->row();
			}

			$data = array(
				'CreatorName' 			=> $_POST['creator_name'],
				'CreatedDate' 			=> date('Y-m-d', strtotime($_POST['date'])),
				'Subject' 				=> $_POST['subject'],
				'Reason' 				=> $_POST['reason'],
				'ContactDate' 			=> date('Y-m-d', strtotime($_POST['dateContact'])),
				'WorkDescription' 		=> $_POST['description'],
				'WorkAmount' 			=> $_POST['price'],
				'WorkArticleC' 			=> $_POST['work_articlenumber'],
				'ProductDescription' 	=> $_POST['product_description'],
				'RecurringDescription' 	=> $_POST['recurring_description'],
				'RecurringTimePeriod' 	=> $_POST['period'],
				'ProjectDescription' 	=> $_POST['projectDescription'],
				'ValidDays' 			=> $_POST['validity'],
				'DeliveryTime' 			=> $_POST['time'],
				'PaymentConditionId' 	=> $_POST['payment'],
				'PaymentTerm' 			=> $_POST['paymentTerm'],
				'IsComparison' 			=> !empty($_POST['comparison']) ? 1 : 0,
				'Template' 				=> !empty($_POST['template']) ? $_POST['template'] : null
			);
			if ($customer != null) {
				$data['ContactFirstName'] 	= !empty($contact) ? $contact->FirstName 	: null;
				$data['ContactInsertion'] 	= !empty($contact) ? $contact->Insertion 	: null;
				$data['ContactLastName'] 	= !empty($contact) ? $contact->LastName 	: null;
				$data['ContactSex'] 		= !empty($contact) ? $contact->Sex 			: null;
				$data['ContactSalutation'] 	= !empty($contact) ? $contact->Salutation 	: null;
			} else {
				$data['CustomerName'] 					= $_POST['company_name'];
				$data['CustomerStreet'] 				= $_POST['address'];
				$data['CustomerHousenumber'] 			= $_POST['address_number'];
				$data['CustomerHousenumberAddition'] 	= $_POST['address_addition'];
				$data['CustomerZipCode'] 				= $_POST['zip_code'];
				$data['CustomerCity'] 					= $_POST['city'];
				$data['CustomerCountry'] 				= $_POST['country'];
				$data['CustomerMailaddress']			= $_POST['email'];
				$data['ContactFirstName'] 				= $_POST['front_name'];
				$data['ContactInsertion'] 				= $_POST['insertion'];
				$data['ContactLastName'] 				= $_POST['last_name'];
				$data['ContactSex'] 					= $_POST['sex'];
				$data['ContactSalutation'] 				= $_POST['salutation'];
			}
			$this->Quotation_model->update($quotationId, $data);

			$ruleIds = array();

			// Save data for products.
			foreach ($_POST['product_number'] as $num) {
				$dataR = array(
					'ArticleC' => $_POST['product_articlenumber'.$num],
					'EanCode' => $_POST['product_ean_code'.$num],
					'ArticleDescription' => $_POST['product_articledescription'.$num],
					'Amount' => $_POST['product_amount'.$num],
					'SalesPrice' => $_POST['product_salesprice'.$num],
					'Tax' => $_POST['product_tax'.$num]
				);
				if (isset($_POST['product_ruleNumber'.$num])) {
					// Existing QuotationRule.
					$quotationRuleId = $_POST['product_ruleNumber'.$num];
					$this->Quotation_model->updateRule($quotationRuleId, $dataR);
				} else {
					// New QuotationRule.
					$dataR['BusinessId'] = $businessId;
					$dataR['QuotationId'] = $quotationId;
					$dataR['Type'] = 1; // = Product.
					$quotationRuleId = $this->Quotation_model->createRule($dataR);
				}
				$ruleIds[] = $quotationRuleId;
			}
			// Save data for repeating.
			foreach ($_POST['repeating_number'] as $num) {
				$dataR = array(
					'ArticleC' => $_POST['repeating_articlenumber'.$num],
					'EanCode' => $_POST['repeating_ean_code'.$num],
					'ArticleDescription' => $_POST['repeating_articledescription'.$num],
					'Amount' => $_POST['repeating_amount'.$num],
					'SalesPrice' => $_POST['repeating_salesprice'.$num],
					'Tax' => $_POST['repeating_tax'.$num]
				);
				if (isset($_POST['repeating_ruleNumber'.$num])) {
					// Existing QuotationRule.
					$quotationRuleId = $_POST['repeating_ruleNumber'.$num];
					$this->Quotation_model->updateRule($quotationRuleId, $dataR);
				} else {
					// New QuotationRule.
					$dataR['BusinessId'] = $businessId;
					$dataR['QuotationId'] = $quotationId;
					$dataR['Type'] = 2; // = Repeating.
					$quotationRuleId = $this->Quotation_model->createRule($dataR);
				}
				$ruleIds[] = $quotationRuleId;
			}

			// Now check if quotation rules have to be deleted.
			$quotationRules = $this->Quotation_model->getRules($quotationId, $businessId)->result();
			foreach ($quotationRules as $rule) {
				if (!in_array($rule->Id, $ruleIds)) {
					$this->Quotation_model->deleteRule($rule->Id);
				}
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De offerte is opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			if ($customer == null) {
				redirect('quotation/anonymousQuotations');
			} else {
				redirect('customers/quotations/'.$quotation->CustomerId);
			}
		}

		$defaulttexts = $this->Defaulttext_model->getAll($businessId)->result();
		$reasons = $this->Reason_model->getAll($businessId)->result();
		$paymentConditions = $this->Paymentcondition_model->getAll($businessId)->result();
		if ($customer != null) {
			$contacts = $this->Customers_contactsmodel->getCustomerEmployed($customer->Id, $businessId)->result();
		} else {
			$contacts = null;
		}
		$users = getUserDropdown($businessId);

		if ($reasons == null) {
			$this->session->set_tempdata('err_message', 'Er zijn geen aanleidingen beschikbaar', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			if ($customer == null) {
				redirect('quotation/anonymousQuotations');
			} else {
				redirect('customers/quotations/'.$quotation->CustomerId);
			}
		}

		// if ($contacts == null) {
		// 	$this->session->set_tempdata('err_message', 'Er zijn geen contactpersonen beschikbaar', 300);
		// 	$this->session->set_tempdata('err_messagetype', 'danger', 300);
		// 	redirect('customers/quotations/'.$quotation->CustomerId);
		// }



		$data['customer'] = $customer;
		$data['contacts'] = $contacts;
		$data['reasons'] = $reasons;
		$data['defaulttexts'] = $defaulttexts;
		$data['paymentConditions'] = $paymentConditions;
		$data['users'] = $users;
		$data['quotation'] = $quotation;
		$data['quotationRulesP'] = $this->Quotation_model->getRules($quotationId, $businessId, 1)->result();
		$data['quotationRulesR'] = $this->Quotation_model->getRules($quotationId, $businessId, 2)->result();
		if ($business->DirectoryPrefix == 'commpro') {
			$data['templates'] = (object) array(
				'commpro' => 'CommPro',
				'cebonline' => 'CEB Online'
			);
		}
		$data['period'] = form_dropdown('period', unserialize(TIMEPERIOD), $quotation->RecurringTimePeriod, CLASSDROPDOWN);
		$data['customFields'] = $this->Quotation_model->getCustomFields($quotationId, $businessId)->result();
		$data['quotationFiles'] = $this->Quotation_model->getQuotationFile($quotationId)->result();
		$data['business'] = $business;


		$this->load->view('quotations/editQuotation', $data);
	}

	public function anonymousQuotations(){

		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$latestQuotationStatus = getLatestQiotationStatus($businessId);

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		if (!empty($_GET['quotationfilter']) && $_GET['quotationfilter'] == '1') {
			$data['quotations'] = $this->Quotation_model->getAllStatus($latestQuotationStatus->Key, 0, $businessId)->result();
		} else {
			$data['quotations'] = $this->Quotation_model->getAllStatusNot($latestQuotationStatus->Key, 0, $businessId)->result();
		}

		$data['latestQuotationStatus'] = $latestQuotationStatus;

		$this->load->view('quotations/anonymousQuotations', $data);
	}

	public function detail()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$quotationId = $this->uri->segment(3);
		$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();

		if ($quotation == null) {
			$this->session->set_tempdata('err_message', 'Deze offerte bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/quotation");
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$dataQ = array(
				'Status' => $_POST['status'],
				'Rejected' => $_POST['rejected']
			);
			$this->Quotation_model->update($quotationId, $dataQ);

			$data['tpl_msg'] = 'De status is succesvol opgeslagen';
			$data['tpl_msgtype'] = 'success';

			// Get updated data
			$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();
		}

		$data['quotation'] = $quotation;
		$data['quotationRulesP'] = $this->Quotation_model->getRules($quotationId, $businessId, 1)->result();
		$data['quotationRulesR'] = $this->Quotation_model->getRules($quotationId, $businessId, 2)->result();
		$data['statusses'] = getQuotationStatusses($businessId);
		$data['latestStatus'] = getLatestQiotationStatus($businessId);

		$this->load->view('quotations/detail', $data);
	}

	public function signedDetail()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		// Permits you to retrieve a specific segment. Where 3 or 4 are the segments numbers you wish to retrieve. Segments are numbered from left to right.
		$businessId = $this->session->userdata('user')->BusinessId;
		$quotationId = $this->uri->segment(3);
		$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();

		if ($quotation == null) {
			$this->session->set_tempdata('err_message', 'Deze offerte bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/quotation");
		}

		if ($quotation->Notification == 1 && $quotation->Signature != null) {
			$data['Notification'] = 0;

			$this->Quotation_model->update($quotation->Id, $data);
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

			$businessId = $quotation->BusinessId;
			$business = $this->Business_model->getBusiness($quotation->BusinessId)->row();
			$paymentCondition = $this->Paymentcondition_model->getPaymentCondition($quotation->PaymentConditionId, $quotation->BusinessId)->row();
			$quotationRulesP = $this->Quotation_model->getRules($quotation->Id, $quotation->BusinessId, 1)->result();
			$quotationRulesR = $this->Quotation_model->getRules($quotation->Id, $quotation->BusinessId, 2)->result();
			$businessName = $business->DirectoryPrefix;

			setlocale(LC_ALL, 'nl_NL');
			$createdDate = new DateTime($quotation->CreatedDate);
			$createdDateFormatted = strftime("%e %B %Y", $createdDate->getTimestamp());
			// Locale should be set to  "en_US" before generating the pdf
			setlocale(LC_ALL, 'en_US');

			$data = array(
				'quotation' => $quotation,
				'business' => $business,
				'quotationId' => $quotationId,
				'quotationRulesP' => $quotationRulesP,
				'quotationRulesR' => $quotationRulesR,
				'paymentCondition' => $paymentCondition,
				'recurringTableType' => checkRecurringTableType($quotationRulesR),
				'discriptionLength' => strlen($quotation->WorkDescription),
				'name' => formatContactName($quotation),
				'contactAddress' => $quotation->CustomerStreet,
				'customerHouseNumber' => $quotation->CustomerHousenumber,
				'country' => array('', 'nl', 'n.l', 'nederland', 'ned'),
				'createdDate' => $createdDateFormatted
			);
				$this->load->view('quotations/signedDetail', $data);
	}

	public function downloadQuotation()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$quotationId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;

		$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();

		if ($quotation == null) {
			$this->session->set_tempdata('err_message', 'Deze offerte bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers");
		}

		$this->_quotationPdf($quotation);

		$this->dompdf_lib_confirm1->stream('Offerte_' . $quotation->QuotationNumber);
	}

	public function mailQuotation()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$quotationId = $this->uri->segment(3);
		$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();

		if ($quotation == null) {
			$this->session->set_tempdata('err_message', 'Deze offerte bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/quotation");
		}

		$business = $this->Business_model->getBusiness($businessId)->row();

		$pdfContent = $this->_quotationPdf($quotation);

		if (!empty($quotation->ContactId)) {
			$contact = $this->Customers_contactsmodel->getContact($quotation->ContactId)->row();

			$toEmail = $contact->Email;
			$toAttention = $contact->FirstName.' '.$contact->Insertion.' '.$contact->LastName;
		} else {
			$toEmail = $quotation->CustomerMailaddress;
			$toAttention = "$quotation->ContactFirstName $quotation->ContactInsertion $quotation->ContactLastName";
		}

		$subject = "Uw offerte: " . $quotation->QuotationNumber;
		$filename = "Offerte_" . $quotation->QuotationNumber . ".pdf";
		$bccName = "";

		$attachments[0] = array(
			'fileName' => $filename,
			'content' => $pdfContent
		);

		$this->load->helper('mail');

		$key = md5(uniqid(rand(), true));

		//replace all occurrences of the search string "offerte gegevens" ->{LINK}+{FIRSTNAME} with the replacement string
		$body = str_replace('{LINK}', base_url() . 'quotation/sign/' . $quotation->Id . '/' . $key, $business->QuotationEmailText);

		$body = str_replace('{FIRSTNAME}', $quotation->CustomerName, $body);

		$result = sendTicket($business->InvoiceEmail, $business->Name, $toEmail, $toAttention, $subject, $body, $attachments, $business->InvoiceCopyEmail, $bccName);

		if ($result) {

			// Update the quotation status.
			$data = array(
				'Status' => DEFAULTQUOTATIONSENT,
				'Key' => $key,
			);

			$this->Quotation_model->update($quotationId, $data);

			$this->session->set_tempdata('err_message', 'Offerte succesvol verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
		} else {
			$this->session->set_tempdata('err_message', 'Offerte is niet verzonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
		}

		redirect("quotation/detail/$quotationId");
	}

	public function sign()
	{
		// Permits you to retrieve a specific segment. Where 3 or 4 are the segments numbers you wish to retrieve. Segments are numbered from left to right.
		$quotationId = $this->uri->segment(3);
		$key = $this->uri->segment(4);
		$quotation = $this->Quotation_model->getQuotationKey($quotationId, $key)->row();

		if ($quotation == null) {
			$this->load->view('quotations/invalidkey');
		} else {
			// strtotime — Parse about any English textual datetime description into a Unix timestamp
			$validDate = strtotime($quotation->CreatedDate . " +" . $quotation->ValidDays . " days");

		if (strtotime("now") >= $validDate) {
			$this->load->view('quotations/invalidkey');
		} else {
			$businessId = $quotation->BusinessId;
			$business = $this->Business_model->getBusiness($quotation->BusinessId)->row();
			$paymentCondition = $this->Paymentcondition_model->getPaymentCondition($quotation->PaymentConditionId, $quotation->BusinessId)->row();
			$quotationRulesP = $this->Quotation_model->getRules($quotation->Id, $quotation->BusinessId, 1)->result();
			$quotationRulesR = $this->Quotation_model->getRules($quotation->Id, $quotation->BusinessId, 2)->result();
			$businessName = $business->DirectoryPrefix;

			//isset — Determine if a variable is declared and is different than NULL
			if (isset($_POST['URL'])) {
				$name = uniqid() . ".png";
				$upload_path = "./uploads/$businessName/business/signature/$quotation->Id/";

			if (!file_exists($upload_path)) {
				mkdir($upload_path, 0777, true);
			}

			$url = $_POST['URL'];

			// file_put_contents — Write data to a file.
			// file_get_contents — Reads entire file into a string.
			file_put_contents($upload_path . $name, file_get_contents($url));

			$this->session->set_tempdata('err_message', 'Tekening succesvol opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);

			$data['Signature'] = $name;
			$this->Quotation_model->update($quotationId, $data);
		}

			setlocale(LC_ALL, 'nl_NL');
			$createdDate = new DateTime($quotation->CreatedDate);
			$createdDateFormatted = strftime("%e %B %Y", $createdDate->getTimestamp());
			// Locale should be set to  "en_US" before generating the pdf
			setlocale(LC_ALL, 'en_US');

			$data = array(
				'quotation' => $quotation,
				'business' => $business,
				'quotationId' => $quotationId,
				'quotationRulesP' => $quotationRulesP,
				'quotationRulesR' => $quotationRulesR,
				'paymentCondition' => $paymentCondition,
				'recurringTableType' => checkRecurringTableType($quotationRulesR),
				'discriptionLength' => strlen($quotation->WorkDescription),
				'name' => formatContactName($quotation),
				'contactAddress' => $quotation->CustomerStreet,
				'customerHouseNumber' => $quotation->CustomerHousenumber,
				'country' => array('', 'nl', 'n.l', 'nederland', 'ned'),
				'createdDate' => $createdDateFormatted
			);
				$this->load->view('quotations/sign', $data);
			}
		}
	}


	public function mailQuotationAfterSign()
	{
		// Permits you to retrieve a specific segment. Where 3 or 4 are the segments numbers you wish to retrieve. Segments are numbered from left to right.
		$quotationId = $this->uri->segment(3);
		$key = $this->uri->segment(4);
		$quotation = $this->Quotation_model->getQuotationKey($quotationId, $key)->row();

		if ($quotation == null) {
			$this->load->view('quotations/invalidkey');
		} else {
				$businessId = $quotation->BusinessId;
				$business = $this->Business_model->getBusiness($businessId)->row();

				// $notification = getMessage($this->session->userdata('user')->Id, $this->session->userdata('user')->BusinessId);

				if (!empty($quotation->ContactId)) {
					$contact = $this->Customers_contactsmodel->getContact($quotation->ContactId)->row();

					$toEmail = $contact->Email;
					$toAttention = $contact->FirstName.' '.$contact->Insertion.' '.$contact->LastName;
				} else {
					$toEmail = $quotation->CustomerMailaddress;
					$toAttention = "$quotation->ContactFirstName $quotation->ContactInsertion $quotation->ContactLastName";
				}

				$subject = "Uw offerte bevestiging: " . $quotation->QuotationNumber;
				$bccName = "";

				$pdfContentCustomer = $this->_offerConfirmPdf($quotation);
				$filenameCustomer = "Orderbevestiging_" . $quotation->QuotationNumber . ".pdf";
				$attachmentsCustomer[0] = array(
					'fileName' => $filenameCustomer,
					'content' => $pdfContentCustomer
				);

				$this->load->helper('mail');

				//replace all occurrences of the search string "offerte gegevens" ->{FIRSTNAME} with the replacement string.
				$customertext = $business->OfferConfirmed;
				$customertext = str_replace('{FIRSTNAME}', $quotation->CustomerName, $customertext);

				$result = sendTicket($business->InvoiceEmail, $business->Name, $toEmail, $toAttention, $subject, $customertext, $attachmentsCustomer, $business->InvoiceCopyEmail, $bccName = null);

				$pdfContent = $this->_quotationPdf($quotation);
				$filename = "Offerte_" . $quotation->QuotationNumber . ".pdf";
				$attachments[0] = array(
					'fileName' => $filename,
					'content' => $pdfContent
				);


				$collaboratortext = $business->SignConfirmationForCollaborator;
				$collaboratortext = str_replace('{FIRSTNAME}', $quotation->CreatorName, $collaboratortext);

				$result = sendTicket($business->InvoiceEmail, $business->Name, $toEmail, $toAttention, $subject, $collaboratortext, $attachments, $business->InvoiceCopyEmail, $bccName);

				$data['Notification'] = 1;

				$this->Quotation_model->resetKey($key);

				$this->Quotation_model->update($quotation->Id, $data);

				if ($result) {

					$this->session->set_tempdata('err_message', 'Offerte succesvol verzonden', 300);
					$this->session->set_tempdata('err_messagetype', 'success', 300);
				} else {
					$this->session->set_tempdata('err_message', 'Offerte is niet verzonden', 300);
					$this->session->set_tempdata('err_messagetype', 'danger', 300);
				}

				$this->load->view('quotations/thankyou');
		}
	}

	public function convertToInvoice()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$quotationId = $this->uri->segment(3);
		$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();

		if ($quotation == null) {
			$this->session->set_tempdata('err_message', 'Deze offerte bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/quotation");
		}

		$customer = $this->Customers_model->getCustomer($quotation->CustomerId, $businessId)->row();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->db->trans_start();

			// First make a normal invoice...
			if ($_POST['create_invoice'] == 1) {

				$a = array();

				$invoiceNumber = $businessId . sprintf('%05d', getBusinessInvoiceNumber($businessId) + 1);
				$invoiceDate = $_POST['invoicedateP'];
				$paymentCondition = $customer != null ? $customer->PaymentCondition : $_POST['paymentconditionP'];
				$termOfPayment = $_POST['termofpaymentP'];
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

				foreach ($_POST['numberP'] as $value) {
					$articlenumber = $_POST['articlenumberP' . $value];
					$articledescription = $_POST['articledescriptionP' . $value];
					$amount = $_POST['amountP' . $value];

					if ($_POST['calculatePurchaseP'] == 1) {
						$salesprice = $_POST['salespriceP' . $value] / $amount;
						$discount = 0;
					} else {
						$salesprice = $_POST['salespriceP' . $value];
						$discount = $_POST['discountP' . $value];
					}

					$tax = $_POST['taxP' . $value];
					$total = $_POST['totalP' . $value];

					$metaData = $_POST['meta_dataP' . $value];

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
						'MetaData' => $metaData,
						'CustomerId' => $customer->Id ?? null,
						'BusinessId' => $businessId
					);
					$invoicewRuleId = $this->Invoice_model->insertInvoiceRule($dataRule);

					$a[] = $invoicewRuleId;

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
					'ShortDescription' 	=> $_POST['short_descriptionP'],
					'Description' 		=> $_POST['descriptionP'],
					'PaymentCondition' 	=> $paymentCondition,
					'TermOfPayment' 	=> $termOfPayment,
					'contact' 			=> $_POST['contactP'] ?? null,
					'CustomerId' 		=> $customer->Id ?? null,
					'SentPerMail'		=> 0,
					'BusinessId' 		=> $businessId
				);
				if ($customer == null) {
					$dataInvoice['CompanyName'] 	= $_POST['company_nameP'];
					$dataInvoice['FrontName'] 		= $_POST['front_nameP'];
					$dataInvoice['Insertion'] 		= $_POST['insertionP'];
					$dataInvoice['LastName'] 		= $_POST['last_nameP'];
					$dataInvoice['Address'] 		= $_POST['addressP'];
					$dataInvoice['AddressNumber'] 	= $_POST['address_numberP'];
					$dataInvoice['AddressAddition'] = $_POST['address_additionP'];
					$dataInvoice['ZipCode'] 		= $_POST['zip_codeP'];
					$dataInvoice['City'] 			= $_POST['cityP'];
					$dataInvoice['Country'] 		= $_POST['countryP'];
					$dataInvoice['MailAddress'] 	= $_POST['mail_addressP'];
				}
				$invoicePId = $this->Invoice_model->insertInvoice($dataInvoice);

				$dataB = array(
					'InvoiceNumber' => getBusinessInvoiceNumber($businessId) + 1
				);

				$this->Invoice_model->updateInvoiceNumber($businessId, $dataB);

				foreach ($a as $ruleId) {
					$data = array(
						'InvoiceId' => $invoicePId
					);

					$this->Invoice_model->updateInvoiceRule($ruleId, $data);
				}

				// Add custom fields to the invoice.
				$customFields = $this->Quotation_model->getCustomFields($quotationId, $businessId)->result();
				foreach ($customFields as $customField) {
					$dataC = array(
						'InvoiceId' => $invoicePId,
						'Key' => $customField->Key,
						'Value' => $customField->Value,
						'BusinessId' => $businessId
					);
					$this->Invoice_model->createCustomField($dataC);
				}

			}

			// Create a repeating invoice...
			if (
				checkModule('ModuleRepeatingInvoice') &&
				$customer != null &&
				$_POST['create_invoice_repeating'] == 1
			) {

				unset($a);

				$a = array();

				$invoiceDate = $_POST['invoicedateR'];
				$timePeriod = $_POST['periodR'];


				foreach ($_POST['numberR'] as $value) {
					$articlenumber = $_POST['articlenumberR' . $value];
					$articledescription = $_POST['articledescriptionR' . $value];
					$domain = isset($_POST['domainR' . $value]) ? $_POST['domainR' . $value] : 0;
					$amount = $_POST['amountR' . $value];
					$tax = $_POST['taxR' . $value];
					$total = $_POST['totalR' . $value];
					$salesPrice = $_POST['salespriceR' . $value];
					$discount = $_POST['discountR' . $value];

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

					$a[$value] = $rule;
				}

				$data = array(
					'InvoiceDate' => strtotime($invoiceDate),
					'TimePeriod' => $timePeriod,
					'PaymentCondition' => $_POST['paymentconditionR'],
					'TermOfPayment' => $_POST['termofpaymentR'],
					'ContactId' => $_POST['contactR'],
					'InvoiceDescription' => $_POST['invoicedescriptionR'],
					'InvoiceRules' => serialize($a),
					'CustomerId' => $customer->Id,
					'BusinessId' => $businessId
				);

				$invoiceRId = $this->Customers_model->createNewRepeatingInvoice($data);
			}

			// Set the quotation status to invoiced.
			$data = array(
				'Status' => DEFAULTQUOTATIONINVOICED
			);
			$this->Quotation_model->update($quotationId, $data);

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De offerte is succesvol omgezet', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			$this->session->set_flashdata('invoiceId', $invoicePId ?? 0, 300);
			$this->session->set_flashdata('RepeatingInvoiceId', $invoiceRId ?? 0, 300);
			redirect('quotation/convertResult');
		}

		if ($customer != null) {
			$contacts[0] = '';
			$contacts = $contacts + getContactDropdown($customer->Id, $businessId)[0];
		}

		$quotationRules = $this->Quotation_model->getRules($quotationId, $businessId)->result();

		$data['invoiceRules'] = array();
		$data['repeatingInvoiceRules'] = array();

		foreach ($quotationRules as $quotationRule) {
			if ($quotationRule->Type == 1) { // Product
				$data['invoiceRules'][] = (object) array(
					'ArticleC' => $quotationRule->ArticleC,
					'EanCode' => $quotationRule->EanCode,
					'Description' => $quotationRule->ArticleDescription,
					'Amount' => $quotationRule->Amount,
					'Price' =>$quotationRule->SalesPrice,
					'Discount' => $quotationRule->Discount,
					'Tax' => $quotationRule->Tax,
					'Total' => 0, // Will be calculated by Javascript in the view...
					'MetaData' => $quotationRule->MetaData
				);
			}
			elseif ($quotationRule->Type == 2) { // Repeating costs
				$data['repeatingInvoiceRules'][] = (object) array(
					'ArticleNumber' => $quotationRule->ArticleC,
					// 'EanCode' => $quotationRule->EanCode,
					'ArticleDescription' => $quotationRule->ArticleDescription,
					'Amount' => $quotationRule->Amount,
					'SalesPrice' =>$quotationRule->SalesPrice,
					'Discount' => $quotationRule->Discount,
					'Tax' => $quotationRule->Tax,
					'Total' => 0, // Will be calculated by Javascript in the view...
					'MetaData' => $quotationRule->MetaData
				);
			}
		}

		// Add work description as a order rule.
		$workDescription = oneLiner($quotation->WorkDescription);
		$workArticle = $this->Product_model->getProductByArticleNumber($quotation->WorkArticleC, $businessId)->row();
		if ($workDescription != '' || $workDescription != null) {
			$data['invoiceRules'][] = (object) array(
				'ArticleC' => $quotation->WorkArticleC,
				'EanCode' => null,
				'Description' => $workDescription != '' ? $workDescription : $workArticle->Description,
				'Amount' => 1,
				'Price' => $quotation->WorkAmount,
				'Discount' => 0,
				'Tax' => $workArticle != null ? $workArticle->BTW : 21,
				'Total' => 0,
				'MetaData' => null
			);
		}

		$data['customer'] = $customer;
		if ($customer != null) {
			$data['contactP'] = form_dropdown('contactP', $contacts, '', CLASSDROPDOWN);
			$data['contactR'] = form_dropdown('contactR', $contacts, '', CLASSDROPDOWN);
		}
		$data['quotation'] = $quotation;
		$data['domains'] = $this->Domain_model->getAll($businessId)->result();
		$data['periodR'] = form_dropdown('periodR', unserialize(TIMEPERIOD), $quotation->RecurringTimePeriod, CLASSDROPDOWN);
		$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();

		$this->load->view('quotations/convertToInvoice', $data);
	}

	public function convertToOrder()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$quotationId = $this->uri->segment(3);
		$quotation = $this->Quotation_model->getQuotation($quotationId, $businessId)->row();

		if ($quotation == null) {
			$this->session->set_tempdata('err_message', 'Deze offerte bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/quotation");
		}

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

				$metaData			= $_POST['meta_data' . $value];

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
					'MetaData'			=> $metaData,
					'CustomerId' 		=> $quotation->CustomerId,
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
				'CustomerId' 			=> $quotation->CustomerId,
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

			// Add custom fields to the salesorder.
			$customFields = $this->Quotation_model->getCustomFields($quotationId, $businessId)->result();
			foreach ($customFields as $customField) {
				$dataC = array(
					'SalesOrderId' => $SalesOrderId,
					'Key' => $customField->Key,
					'Value' => $customField->Value,
					'BusinessId' => $businessId
				);
				$this->SalesOrder_model->createCustomField($dataC);
			}

			$this->db->trans_complete();

			$this->session->set_tempdata('err_message', 'De offerte is succesvol omgezet', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			$this->session->set_flashdata('salesOrderId', $SalesOrderId);
			redirect('quotation/convertResult');
		}

		// Get only product rules because orders does not support repeating.
		$quotationRules = $this->Quotation_model->getRules($quotationId, $businessId, 1)->result();

		$data['quotationRules'] = array();
		$data['repeatingInvoiceRules'] = array();

		foreach ($quotationRules as $quotationRule) {
			$data['quotationRules'][] = (object) array(
				'ArticleC' => $quotationRule->ArticleC,
				'EanCode' => $quotationRule->EanCode,
				'Description' => $quotationRule->ArticleDescription,
				'Amount' => $quotationRule->Amount,
				'Price' =>$quotationRule->SalesPrice,
				'Discount' => $quotationRule->Discount,
				'Tax' => $quotationRule->Tax,
				'Total' => 0, // Will be calculated by Javascript in the view...
				'MetaData' => $quotationRule->MetaData
			);
		}

		// Add work description as a order rule.
		$workDescription = oneLiner($quotation->WorkDescription);
		$workArticle = $this->Product_model->getProductByArticleNumber($quotation->WorkArticleC, $businessId)->row();
		if ($workDescription != '' || $workDescription != null) {
			$data['quotationRules'][] = (object) array(
				'ArticleC' => $quotation->WorkArticleC,
				'EanCode' => null,
				'Description' => $workDescription != '' ? $workDescription : $workArticle->Description,
				'Amount' => 1,
				'Price' => $quotation->WorkAmount,
				'Discount' => 0,
				'Tax' => $workArticle != null ? $workArticle->BTW : 21,
				'Total' => 0,
				'MetaData' => null
			);
		}

		$data['quotation'] = $quotation;
		$data['transporters'] = $this->Transporter_model->getAll($this->session->userdata('user')->BusinessId)->result();
		$data['sellers'] = $this->Sellers_model->getAll($this->session->userdata('user')->BusinessId)->result();
		$data['paymentConditions'] = $this->Paymentcondition_model->getAll($businessId)->result();

		$this->load->view('quotations/convertToOrder', $data);
	}

	public function convertResult()
	{
		if (!isLogged()) {
			redirect('login');
		}

		if (!checkModule('ModuleQuotation')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}

		if ($this->session->userdata('user')->Tab_CQuotations != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$invoiceId 			= $_SESSION['invoiceId'] 			?? null;
		$repeatingInvoiceId = $_SESSION['RepeatingInvoiceId'] 	?? null;
		$salesOrderId 		= $_SESSION['salesOrderId'] 		?? null;

		$businessId = $this->session->userdata('user')->BusinessId;

		$data['invoice'] 			= $invoiceId 			!= null ? $this->Customers_invoicesmodel->getInvoice($invoiceId, $businessId)->row() 			: null;
		$data['invoiceRepeating'] 	= $repeatingInvoiceId 	!= null ? $this->Customers_model->getRepeatingInvoice($repeatingInvoiceId, $businessId)->row() 	: null;
		$data['salesOrder'] 		= $salesOrderId 		!= null ? $this->SalesOrder_model->getSalesOrder($salesOrderId, $businessId)->row() 			: null;

		$this->load->view('quotations/convertResult', $data);
	}

	private function _quotationPdf($quotation)
	{

		$businessId = $quotation->BusinessId;
		$customer = $this->Customers_model->getCustomer($quotation->CustomerId, $businessId)->row();
		$business = $this->Business_model->getBusiness($businessId)->row();

		$layout = $quotation->Template != null ? "quotation_$quotation->Template" : "quotation";

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/{$layout}.php")) {
			show_error('De opgegeven offerte layout bestaat niet');
		}

		setlocale(LC_ALL, 'nl_NL');
		$createdDate = new DateTime($quotation->CreatedDate);
		$createdDateFormatted = strftime("%e %B %Y", $createdDate->getTimestamp());
		// Locale should be set to  "en_US" before generating the pdf
		setlocale(LC_ALL, 'en_US');

		$quotationRulesP = $this->Quotation_model->getRules($quotation->Id, $businessId, 1)->result();
		$quotationRulesR = $this->Quotation_model->getRules($quotation->Id, $businessId, 2)->result();

		$data['createdDate'] = $createdDateFormatted;
		$data['business'] = $this->Business_model->getBusiness($businessId)->row();
		$data['quotation'] = $quotation;
		$data['quotationRulesP'] = $quotationRulesP;
		$data['quotationRulesR'] = $quotationRulesR;
		$data['customer'] = $customer;
		$data['paymentCondition'] = $this->Paymentcondition_model->getPaymentCondition($quotation->PaymentConditionId, $businessId)->row();
		$data['discriptionLength'] = strlen($quotation->WorkDescription);
		$data['name'] = formatContactName($quotation);
		$data['contactAddress'] = $quotation->CustomerStreet.' '.$quotation->CustomerHousenumber.$quotation->CustomerHousenumberAddition;
		$data['country'] = array('', 'nl', 'n.l', 'nederland', 'ned');
		$data['recurringTableType'] = checkRecurringTableType($quotationRulesR);

		// $this->load->view("pdf/{$business->DirectoryPrefix}/{$layout}", $data);

		unset($this->dompdf_lib);

		$this->load->library('dompdf_lib', array(), 'dompdf_lib_confirm1');

		$this->dompdf_lib_confirm1->load_view("pdf/{$business->DirectoryPrefix}/{$layout}", $data);
		$this->dompdf_lib_confirm1->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib_confirm1->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib_confirm1->set_option('isRemoteEnabled', true);
		$this->dompdf_lib_confirm1->setPaper('A4', 'portrait');
		$this->dompdf_lib_confirm1->render();

		return $this->dompdf_lib_confirm1->output(array('compress' => 0));
	}

	private function _offerConfirmPdf($quotation)
	{
		$businessId = $quotation->BusinessId;
		$customer = $this->Customers_model->getCustomer($quotation->CustomerId, $businessId)->row();
		$business = $this->Business_model->getBusiness($businessId)->row();

		$layout = $quotation->Template != null ? "offerConfirmed_$quotation->Template" : "offerConfirmed";

		if (!file_exists(APPPATH . "views/pdf/{$business->DirectoryPrefix}/{$layout}.php")) {
			show_error('De opgegeven offerte layout bestaat niet');
		}

		setlocale(LC_ALL, 'nl_NL');
		$createdDate = new DateTime($quotation->CreatedDate);
		$createdDateFormatted = strftime("%e %B %Y", $createdDate->getTimestamp());
		// Locale should be set to  "en_US" before generating the pdf
		setlocale(LC_ALL, 'en_US');

		$quotationRulesP = $this->Quotation_model->getRules($quotation->Id, $businessId, 1)->result();
		$quotationRulesR = $this->Quotation_model->getRules($quotation->Id, $businessId, 2)->result();

		$data['createdDate'] = $createdDateFormatted;
		$data['business'] = $this->Business_model->getBusiness($businessId)->row();
		$data['quotation'] = $quotation;
		$data['quotationRulesP'] = $quotationRulesP;
		$data['quotationRulesR'] = $quotationRulesR;
		$data['customer'] = $customer;
		$data['paymentCondition'] = $this->Paymentcondition_model->getPaymentCondition($quotation->PaymentConditionId, $businessId)->row();
		$data['discriptionLength'] = strlen($quotation->WorkDescription);
		$data['name'] = formatContactName($quotation);
		$data['contactAddress'] = $quotation->CustomerStreet.' '.$quotation->CustomerHousenumber.$quotation->CustomerHousenumberAddition;
		$data['country'] = array('', 'nl', 'n.l', 'nederland', 'ned');
		$data['recurringTableType'] = checkRecurringTableType($quotationRulesR);

		// $this->load->view("pdf/{$business->DirectoryPrefix}/{$layout}", $data);

		unset($this->dompdf_lib);

		$this->load->library('dompdf_lib', array(), 'dompdf_lib_confirm2');

		$this->dompdf_lib_confirm2->load_view("pdf/{$business->DirectoryPrefix}/{$layout}", $data);
		$this->dompdf_lib_confirm2->set_option('isHtml5ParserEnabled', true);
		$this->dompdf_lib_confirm2->set_option('isCssFloatEnabled', true);
		$this->dompdf_lib_confirm2->set_option('isRemoteEnabled', true);
		$this->dompdf_lib_confirm2->setPaper('A4', 'portrait');
		$this->dompdf_lib_confirm2->render();

		return $this->dompdf_lib_confirm2->output(array('compress' => 0));
	}
}
