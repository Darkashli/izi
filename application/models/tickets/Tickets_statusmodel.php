<?php

class Tickets_statusmodel extends CI_Model {
    
    private $tbl_parent= 'Status';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }
    
    function getAll($BusinessId){
        $this->db->where('BusinessId', $BusinessId);
        $this->db->order_by('Order', 'asc');
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getStatus($statusId, $businessId){
        $this->db->where('Id', $statusId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function updateStatus($data, $statusId){
        $this->db->where('Id', $statusId);
        $this->db->update($this->tbl_parent, $data);
    }
    
    function createStatus($data){
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
    
    function getLatestStatus($businessId){
        $this->db->where('BusinessId', $businessId);
        $this->db->order_by('Order', 'desc');
        $this->db->limit(1);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getFirstStatus($businessId){
        $this->db->where('BusinessId', $businessId);
        $this->db->order_by('Order', 'asc');
        $this->db->limit(1);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function deleteStatus($statusId){
        $this->db->where('Id', $statusId);
        return $this->db->delete($this->tbl_parent);
    }

}
