<?php

class Tickets_productmodel extends CI_Model {

    private $tbl_parent = 'TicketProduct';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }
    
    function getProducts($ticketId, $businessId){
        $this->db->where('TicketId', $ticketId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function removeAll($ticketId, $businessId){
        $this->db->where('TicketId', $ticketId);
        $this->db->where('BusinessId', $businessId);
        $this->db->delete($this->tbl_parent);
    }
    
    function insertRule($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

    function updateTicketProduct($customerId, $data) {
        $this->db->where('Id', $customerId);
        $this->db->update($this->tbl_parent, $data);
    }

}
