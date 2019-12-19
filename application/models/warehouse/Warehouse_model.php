<?php

class Warehouse_model extends CI_Model {

    private $tbl_parent = 'Warehouse';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($BusinessId) {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getWarehouse($warehouseId, $businessId) {
        $this->db->where('Id', $warehouseId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function updateWarehouse($warehouseId, $data) {
        $this->db->where('Id', $warehouseId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createWarehouse($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
    
    function deleteWarehouse($warehouseId){
        $this->db->where('Id', $warehouseId);
        $this->db->delete($this->tbl_parent); 
    }

}
