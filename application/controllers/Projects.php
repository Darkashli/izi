<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('customers/Customers_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('projects/Project_model', '', TRUE);
		$this->load->model('tickets/Tickets_statusmodel', '', TRUE);
		$this->load->helper('Ticket');
		$this->load->helper('Project');
	}

	public function index() {
		redirect('customers/index/projects');
	}
	
	public function create()
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
		
		$business = $this->Business_model->getBusiness($businessId)->row();
		$customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

		if ($customer == null)
		{
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/projects");
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$this->db->trans_start();
			
			$projectNumber = ++$business->ProjectNumber;
			
			$data = array(
				'ProjectNumber' => "P".date('Y').$customerId.sprintf('%03d', ($projectNumber)),
				'Description' => $_POST['description'],
				'LongDescription' => $_POST['long_description'],
				'NatureOfWorkId' => $_POST['natureofwork'],
				'CustomerId' => $customerId,
				'BusinessId' => $businessId
			);
			$projectId = $this->Project_model->create($data);
			
			foreach ($_POST['phases'] as $num) {
				$dataR = array(
					'Name' => $_POST["phase_name$num"],
					'ProjectId' => $projectId,
					'BusinessId' => $businessId
				);
				$this->Project_model->CreatePhase($dataR);
			}
			
			// Save the incremented projectNumber.
			$dataB = array(
				'ProjectNumber' => $projectNumber
			);
			$this->Business_model->updateBusiness($dataB, $businessId);
			
			$this->db->trans_complete();
			
			$this->session->set_tempdata('err_message', 'Het project is succesvol aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("customers/projects/$customerId");
		}
		
		$data['project'] = (object) array(
			'ProjectNumber' => null,
			'Description' => null,
			'LongDescription' => null,
			'NatureOfWorkId' => null,
		);
		$data['projectPhases'] = null;
		$data['natureOfWork'] = form_dropdown('natureofwork', getNatureOfWorkDropdown($businessId), '', CLASSDROPDOWN);
		
		$this->load->view('projects/edit', $data);
		
	}
	
	public function update()
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
		
		$projectId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		
		$business = $this->Business_model->getBusiness($businessId)->row();
		$project = $this->Project_model->get($projectId, $businessId)->row();

		if ($project == null)
		{
			$this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/projects");
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$this->db->trans_start();
		
			$data = array(
				'Description' => $_POST['description'],
				'LongDescription' => $_POST['long_description'],
				'NatureOfWorkId' => $_POST['natureofwork']
			);
			$this->Project_model->update($projectId, $data);
			
			$ruleIds = array();
		
			foreach ($_POST['phases'] as $num) {
				$dataR = array(
					'Name' => $_POST["phase_name$num"]
				);
				if (isset($_POST["phase_id$num"])) {
					// Existing phase.
					$projectPhaseId = $_POST["phase_id$num"];
					$this->Project_model->updatePhase($projectPhaseId, $dataR);
				}
				else {
					// New phase.
					$dataR['ProjectId'] = $projectId;
					$dataR['BusinessId'] = $businessId;
					$projectPhaseId = $this->Project_model->CreatePhase($dataR);
				}
				$ruleIds[] = $projectPhaseId;
			}
		
			// Now check if phases have to be deleted.
			$projectPhases = $this->Project_model->getPhases($projectId, $businessId)->result();
			foreach ($projectPhases as $phase) {
				if (!in_array($phase->Id, $ruleIds)) {
					$this->Project_model->deletePhase($phase->Id);
				}
			}
		
			$this->db->trans_complete();
		
			$this->session->set_tempdata('err_message', 'Het project is succesvol opgeslagen', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
			redirect("customers/projects/$project->CustomerId");
		}
		
		$data['project'] = $project;
		$data['projectPhases'] = $this->Project_model->getPhases($projectId, $businessId)->result();
		$data['natureOfWork'] = form_dropdown('natureofwork', getNatureOfWorkDropdown($businessId), $project->NatureOfWorkId, CLASSDROPDOWN);
		
		$this->load->view('projects/edit', $data);
		
	}
	
	public function overview()
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
		
		$projectId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$project = $this->Project_model->get($projectId, $businessId)->row();

		if ($project == null)
		{
			$this->session->set_tempdata('err_message', 'Dit project bestaat niet', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("customers/index/projects");
		}
		
		$projectPhases = $this->Project_model->getPhases($projectId, $businessId)->result();
		
		$data['project'] = $project;
		$data['projectPhases'] = $projectPhases;
		$data['firstStatus'] = $this->Tickets_statusmodel->getfirstStatus($businessId)->row();
		$data['latestStatus'] = $this->Tickets_statusmodel->getLatestStatus($businessId)->row();
		
		$this->load->view('projects/overview', $data);
	}
	
	/* AJAX functions */
	
	public function ajax_getPhases()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		
		if (!isLogged()) {
			echo json_encode(array('error' => 'login'));
			exit();
		}
		
		if ($this->session->userdata('user')->Tab_CWork != 1){
			echo json_encode(array('error' => 'no_permission'));
			exit();
		}
		
		if (!checkModule('ModuleTickets')){
			echo json_encode(array('error' => 'no_permission'));
			exit();
		}
		
		$businessId = $this->session->userdata('user')->BusinessId;
		$projectId = $this->uri->segment(3);
		$project = $this->Project_model->get($projectId, $businessId)->row();
		
		if ($project == null) {
			echo json_encode(array('error' => 'product_not_found'));
			exit();
		}
		
		$data['projectPhases'] = $this->Project_model->getPhases($projectId, $businessId)->result();
		
		echo json_encode($data);
	}

}
