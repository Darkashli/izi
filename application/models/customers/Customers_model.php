<?php

class Customers_model extends CI_Model {

    private $tbl_parent = 'Customers';
    private $tbl_repeatingInvoice = "RepeatingInvoice";

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($BusinessId) {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getCustomer($CustomerId, $businessId) {
        $this->db->where('Id', $CustomerId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function updateCustomer($customerId, $data) {
        $this->db->where('Id', $customerId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createCustomer($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

    function getAllRepeatingInvoice($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_repeatingInvoice);
        return $this->db->get();
    }
    
    function getAllRepeatingInvoiceByCustomer($customerId, $businessId) {
        $this->db->where('CustomerId', $customerId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_repeatingInvoice);
        return $this->db->get();
    }

    function getAllRepeatingInvoiceBelowDate($date, $businessId) {
        $this->db->where('InvoiceDate <=', $date);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_repeatingInvoice);
        return $this->db->get();
    }

    function getRepeatingInvoice($repeatingInvoiceId, $businessId) {
        $this->db->where('Id', $repeatingInvoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_repeatingInvoice);
        return $this->db->get();
    }

    function createNewRepeatingInvoice($data) {
        $this->db->insert($this->tbl_repeatingInvoice, $data);
        return $this->db->insert_id();
    }

    function updateRepeatingInvoice($repeatingInvoiceId, $data) {
        $this->db->where('Id', $repeatingInvoiceId);
        $this->db->update($this->tbl_repeatingInvoice, $data);
    }

    function deleteRepeatingInvoice($repeatingInvoiceId) {
        $this->db->where('Id', $repeatingInvoiceId);
        $this->db->delete($this->tbl_repeatingInvoice);
    }
    

}
