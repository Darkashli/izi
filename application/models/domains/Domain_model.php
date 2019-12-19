<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Domain_model extends CI_Model
{
	
	private $tbl_parent = 'Domain';
	
	function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}
	
	function create($data)
	{
		$this->db->insert($this->tbl_parent, $data);
	}
	
	function update($domainId, $data)
	{
		$this->db->where('Id', $domainId);
		$this->db->update($this->tbl_parent, $data);
	}
	
	function delete($domainId)
	{
		$this->db->where('Id', $domainId);
		$this->db->delete($this->tbl_parent);
	}
	
	function get($domainId)
	{
		$this->db->where('Id', $domainId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}
	
	function getAll($businessId)
	{
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}
	
}
