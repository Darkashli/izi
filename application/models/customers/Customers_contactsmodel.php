<?php

class Customers_contactsmodel extends CI_Model {
    
    private $tbl_parent= 'Contacts';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }
    
    function getAll($BusinessId){
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getCustomer($CustomerId, $businessId){
        $this->db->where('CustomerId', $CustomerId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getCustomerEmployed($CustomerId, $businessId){
        $this->db->where('CustomerId', $CustomerId);
        $this->db->where('BusinessId', $businessId);
        $this->db->where('Employed', 1);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getContact($contactId){
        $this->db->where('Id', $contactId);
        $this->db->from($this->tbl_parent);
        return $this->db->get(); 
    }
    
    function updateContact($data, $contactId){
        $this->db->where('Id', $contactId);
        $this->db->update($this->tbl_parent, $data);
    }
    
    function createContact($data){
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
    
    function deleteContact($contactId){
        $this->db->where('Id', $contactId);
        $this->db->delete($this->tbl_parent);
    }

}