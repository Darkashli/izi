<?php

class QuotationStatus_model extends CI_Model {

	private $tbl_parent = 'QuotationStatus';

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	function get($statusId, $businessId)
	{
		$this->db->where('Id', $statusId);
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}
	
	function getAll($businessId)
	{
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		$this->db->order_by('SortingOrder', 'ASC');
		return $this->db->get();
	}
	
	function create($data) {
		$this->db->insert($this->tbl_parent, $data);
		return $this->db->insert_id();
	}
	
	function update($statusId, $data)
	{
		$this->db->where('Id', $statusId);
		$this->db->update($this->tbl_parent, $data);
	}

}
