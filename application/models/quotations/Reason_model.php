<?php

class Reason_model extends CI_Model {

	private $tbl_parent = 'Reasons';

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	function create($data){
		$this->db->insert($this->tbl_parent, $data);
	}

	function getAll($businessId){
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function get($reasonId, $businessId){
		$this->db->where('BusinessId', $businessId);
		$this->db->where('Id', $reasonId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function update($reasonId, $data){
		$this->db->where('Id', $reasonId);
		$this->db->update($this->tbl_parent, $data);
	}

}
