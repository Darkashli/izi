<?php

class Customers_priceagreementmodel extends CI_Model {

    private $tbl_parent = 'PriceAgreement';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($CustomerId, $BusinessId) {
        $this->db->where('CustomerId', $CustomerId);
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function removeAll($CustomerId, $businessId){
        $this->db->where('CustomerId', $CustomerId);
        $this->db->where('BusinessId', $businessId);
        $this->db->delete($this->tbl_parent);
    }
    
    function insertRule($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
    
    function getAgreement($articleC, $cutomerId){
        $this->db->where('ArticleNumber', $articleC);
        $this->db->where('CustomerId', $cutomerId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function updateCustomer($customerId, $data) {
        $this->db->where('Id', $customerId);
        $this->db->update($this->tbl_parent, $data);
    }

}
