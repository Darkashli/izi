<?php

class Project_model extends CI_Model {

	private $tbl_parent = 'Project';
	private $tbl_parentP = 'ProjectPhase';
	private $tbl_parentT = 'Ticket';

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}
	
	function create($data) {
		$this->db->insert($this->tbl_parent, $data);
		return $this->db->insert_id();
	}
	
	function update($projectId, $data)
	{
		$this->db->where('Id', $projectId);
		$this->db->update($this->tbl_parent, $data);
	}
	
	function get($projectId, $businessId) {
		$this->db->select("$this->tbl_parent.*");
		$this->db->select_sum("$this->tbl_parentT.Prognosis", 'Prognosis');
		$this->db->join("$this->tbl_parentP", "$this->tbl_parentP.ProjectId = $this->tbl_parent.Id", 'left');
		$this->db->join("$this->tbl_parentT", "$this->tbl_parentT.PhaseId = $this->tbl_parentP.Id", 'left');
		$this->db->where("$this->tbl_parent.Id", $projectId);
		$this->db->where("$this->tbl_parent.BusinessId", $businessId);
		return $this->db->get($this->tbl_parent);
	}
	
	function getAll($customerId = 0, $businessId) {
		if ($customerId != 0) {
			$this->db->where("CustomerId", $customerId);
		}
		$this->db->where("BusinessId", $businessId);
		$this->db->order_by("ProjectNumber", 'desc');
		return $this->db->get($this->tbl_parent);
	}
	
	function createPhase($data) {
		$this->db->insert($this->tbl_parentP, $data);
		return $this->db->insert_id();
	}
	
	function updatePhase($projectPhaseId, $data)
	{
		$this->db->where('Id', $projectPhaseId);
		$this->db->update($this->tbl_parentP, $data);
	}
	
	function deletePhase($projectPhaseId)
	{
		$this->db->where('Id', $projectPhaseId);
		$this->db->delete($this->tbl_parentP);
	}
	
	function getPhases($projectId, $businessId)
	{
		$this->db->select("$this->tbl_parentP.*");
		$this->db->select_sum("$this->tbl_parentT.Prognosis", 'Prognosis');
		$this->db->join("$this->tbl_parentT", "$this->tbl_parentT.PhaseId = $this->tbl_parentP.Id", 'left');
		$this->db->where("$this->tbl_parentP.ProjectId", $projectId);
		$this->db->where("$this->tbl_parentP.BusinessId", $businessId);
		$this->db->group_by("$this->tbl_parentP.Id");
		return $this->db->get($this->tbl_parentP);
	}

}
