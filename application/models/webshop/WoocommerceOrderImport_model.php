<?php

class WoocommerceOrderImport_model extends CI_Model {

	private $tbl_parent = 'WoocommerceOrderImports';

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	function create($data) {
		$this->db->insert($this->tbl_parent, $data);
		return $this->db->insert_id();
	}
	
	function getLatest($webshopId) {
		$this->db->order_by('ImportDate', 'desc');
		$this->db->where('WebshopId', $webshopId);
		$this->db->limit(1);
		return $this->db->get($this->tbl_parent);
	}
}
