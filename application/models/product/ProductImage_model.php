<?php

class ProductImage_model extends CI_Model
{
	private $tbl_parent = 'ProductImage';

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
	}
	
	public function get($productImageId, $businessId)
	{
		$this->db->where('Id', $productImageId);
		$this->db->where('BusinessId', $businessId);
		return $this->db->get($this->tbl_parent);
	}

	public function getFromProduct($productId, $businessId)
	{
		$this->db->where('BusinessId', $businessId);
		$this->db->where('ProductId', $productId);
		$this->db->order_by('Position', 'asc');
		return $this->db->get($this->tbl_parent);
	}
	
	public function getHighestPos($productId, $businessId)
	{
		$this->db->where('BusinessId', $businessId);
		$this->db->where('ProductId', $productId);
		$this->db->order_by('Position', 'desc');
		$this->db->limit(1);
		return $this->db->get($this->tbl_parent);
	}
	
	public function create($data)
	{
		return $this->db->insert($this->tbl_parent, $data);
	}
	
	public function delete($productImageId)
	{
		$this->db->where('Id', $productImageId);
		return $this->db->delete($this->tbl_parent);
	}
}
