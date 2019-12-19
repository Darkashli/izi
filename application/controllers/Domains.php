<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Domains extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('domains/Domain_model', '', TRUE);
		$this->load->helper('domain');
	}
	
	public function index()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}
		
		$businessId = $this->session->userdata('user')->BusinessId;
		
		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}
		
		$data['domains'] = $this->Domain_model->getAll($businessId)->result();
		
		$this->load->view('domains/default', $data);
	}
	
	public function create()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}
		
		$businessId = $this->session->userdata('user')->BusinessId;
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data = array(
				'Name' => $_POST['name'],
				'RegisterDate' => date('Y-m-d', strtotime($_POST['register_date'])),
				'Customer' => $_POST['customer'],
				'Reseller' => $_POST['reseller'],
				'HasHosting' => isset($_POST['has_hosting']) ? 1 : 0,
				'BusinessId' => $businessId
			);
			
			$this->Domain_model->create($data);
			
			$this->session->set_tempdata('err_message', 'Het domeinnaam is succesvol opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('domains');
		}
		
		if ($this->session->tempdata('err_message')) {
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}
		
		$data['domain'] = (object) array(
			'Name' => null,
			'RegisterDate' => date('Y-m-d'),
			'Customer' => 0,
			'Reseller' => null,
			'HasHosting' => 0
		);
		$data['customers'] = $this->Customers_model->getAll($businessId)->result();
		
		$this->load->view('domains/edit', $data);
		
	}
	
	public function update()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}
		
		$businessId = $this->session->userdata('user')->BusinessId;
		$domainId = $this->uri->segment(3);
		$domain = $this->Domain_model->get($domainId)->row();
		
		if ($domain == null || $domain->BusinessId != $businessId) {
			$this->session->set_tempdata('err_message', 'Deze domeinnaam bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$data = array(
				'Name' => $_POST['name'],
				'RegisterDate' => date('Y-m-d', strtotime($_POST['register_date'])),
				'Customer' => $_POST['customer'],
				'Reseller' => $_POST['reseller'],
				'HasHosting' => isset($_POST['has_hosting']) ? 1 : 0,
			);
		
			$this->Domain_model->update($domain->Id, $data);
		
			$this->session->set_tempdata('err_message', 'Het domeinnaam is succesvol opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect('domains');
		}
		
		$data['domain'] = $domain;
		$data['customers'] = $this->Customers_model->getAll($businessId)->result();
		
		$this->load->view('domains/edit', $data);
		
	}
	
	public function delete()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if ($this->session->userdata('user')->Tab_CRepeatingInv != 1) {
			show_error('U heeft geen bevoegdheden om deze pagina te bezoeken.', '', 'Toegang geweigerd');
		}

		if (!checkModule('ModuleRepeatingInvoice')) {
			$this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}
		
		$businessId = $this->session->userdata('user')->BusinessId;
		$domainId = $this->uri->segment(3);
		$domain = $this->Domain_model->get($domainId)->row();
		
		if ($domain == null || $domain->BusinessId != $businessId) {
			$this->session->set_tempdata('err_message', 'Deze domeinnaam bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}
		
		$this->Domain_model->delete($domainId);
		
		$this->session->set_tempdata('err_message', 'Deze domeinnaam is verwijderd', 300);
		$this->session->set_tempdata('err_messagetype', 'success', 300);
		redirect('domains');
	}
	
}
