<?php

class Quotation_model extends CI_Model {

	private $tbl_parent = 'Quotation';
	private $tbl_parentR = 'QuotationRules';
	private $tbl_parentC = 'QuotationCustomField';
	private $tbl_parentF = 'QuotationFile';

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	function getQuotation($quotationId, $businessId)
	{
		$this->db->where('Id', $quotationId);
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function getQuotationKey($quotationId, $key)
	{
		$data = array(
			'Id' => $quotationId,
			'Key' => $key,
		);

		$this->db->where($data);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function resetKey($key)
	{
		$data['key'] = null;
		$this->db->where('Key', $key);
		$this->db->update($this->tbl_parent, $data);
	}

	function getQuotationFile($quotationId)
	{
		$this->db->where('QuotationId', $quotationId);
		$this->db->from($this->tbl_parentF);
		return $this->db->get();
	}

	function getAll($customerId = 0, $businessId)
	{
		if ($customerId != 0) {
			$this->db->where('CustomerId', $customerId);
		}
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function getAllStatus($status, $customerId = 'all', $businessId)
	{
		if ($customerId !== 'all') {
			$this->db->where('CustomerId', $customerId);
		}
		$this->db->where('BusinessId', $businessId);
		$this->db->where('Status', $status);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function getAllStatusNot($status, $customerId = 'all', $businessId)
	{
		if ($customerId !== 'all') {
			$this->db->where('CustomerId', $customerId);
		}
		$this->db->where('BusinessId', $businessId);
		$this->db->where('Status <>', $status);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function getQuotationRuleById($quotationId, $businessId)
	{
		$this->db->where('QuotationId', $quotationId);
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parentR);
		return $this->db->get();
	}

	function create($data) {
		$this->db->insert($this->tbl_parent, $data);
		return $this->db->insert_id();
	}

	function createQuotationFile($dataF) {
		$this->db->insert($this->tbl_parentF, $dataF);
		return $this->db->insert_id();
	}

	function createRule($data) {
		$this->db->insert($this->tbl_parentR, $data);
		return $this->db->insert_id();
	}

	function update($quotationId, $data)
	{
		$this->db->where('Id', $quotationId);
		$this->db->update($this->tbl_parent, $data);
	}

	//Escaping Queries:
	//$data = $this->input->post('name');
	//$query = 'SELECT * FROM Quotation WHERE name=' .$this->db->escape($data);
	//$this->db->query($query);

	//Query Binding:
	//$sql = "SELECT * FROM Quotation WHERE firstname = ? AND lastname= ?";
	//$this->db->query($sql, array('Jacky', 'Valkparkiet'));

	//Active Record Class:
	//$this->db->get_where('Quotation', array('firstname => 'Jacky', 'lastname' => 'Valkparkiet'));

	function updateRule($quotationRuleId, $data)
	{
		$this->db->where('Id', $quotationRuleId);
		$this->db->update($this->tbl_parentR, $data);
	}


	function deleteRule($quotationRuleId)
	{
		$this->db->where('Id', $quotationRuleId);
		$this->db->delete($this->tbl_parentR);
	}

	function getRules($quotationId, $businessId, $type = null)
	{
		$this->db->where('QuotationId', $quotationId);
		$this->db->where('BusinessId', $businessId);
		if ($type !== null) {
			$this->db->where('Type', $type);
		}
		$this->db->from($this->tbl_parentR);
		return $this->db->get();
	}

	public function createCustomField($data)
	{
		return $this->db->insert($this->tbl_parentC, $data);
	}

	public function getCustomFields($quotationId, $businessId)
	{
		$this->db->where('QuotationId', $quotationId);
		$this->db->where('BusinessId', $businessId);
		return $this->db->get($this->tbl_parentC);
	}
}
