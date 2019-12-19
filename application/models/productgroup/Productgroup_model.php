<?php

class Productgroup_model extends CI_Model {

    private $tbl_parent = 'Productgroup';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($BusinessId) {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getProductgroup($productGroupId, $businessId) {
        $this->db->where('Id', $productGroupId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function updateProductgroup($productgroupId, $data) {
        $this->db->where('Id', $productgroupId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createProductgroup($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

}
