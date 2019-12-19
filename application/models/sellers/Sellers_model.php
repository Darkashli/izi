<?php

class Sellers_model extends CI_Model {

    private $tbl_parent= 'Seller';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($BusinessId){
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getSeller($Seller_id, $businessId){
        $this->db->where('Seller_id', $Seller_id);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getWithImport($businessId, $not = NULL)
    {
        $this->db->where('Import is NOT NULL', NULL, FALSE);
        if ($not != NULL) {
            $this->db->where('Import <>', $not);
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getByImport($import, $businessId)
    {
        $this->db->where('Import', $import);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function updateSeller($Seller_id, $data){
        $this->db->where('Seller_id', $Seller_id);
        $this->db->update($this->tbl_parent, $data);
    }

    function createSeller($data){
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

}
