<?php

class Defaulttext_model extends CI_Model {

	private $tbl_parent = 'Defaulttexts';

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

	function get($textId, $businessId){
		$this->db->where('Id', $textId);
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function update($textId, $data){
		$this->db->where('Id', $textId);
		$this->db->update($this->tbl_parent, $data);
	}

}
