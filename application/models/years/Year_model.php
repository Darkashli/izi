<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Year_model extends CI_Model
{
	
	private $tbl_parent = 'Year';
	
	function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}
	
	function create($data)
	{
		$this->db->insert($this->tbl_parent, $data);
	}
	
	function get($yearId)
	{
		$this->db->where('Id', $yearId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}
	
	function getByYear($year, $businessId)
	{
		$this->db->where('year', $year);
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}
	
	function getAll($businessId)
	{
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		$this->db->order_by('Year', 'desc');
		return $this->db->get();
	}
	
}
