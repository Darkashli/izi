<?php

class Customers_invoicesmodel extends CI_Model {
    
    private $tbl_parent= 'Invoice';
    private $tbl_parentP= 'InvoicePayments';
    private $tbl_parentR= 'InvoiceRules';

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
    
    function getInvoice($invoiceId, $businessId){
        $this->db->where('Id', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getPayments($invoiceId, $businessId){
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentP);
        return $this->db->get();
    }
    
    function getInvoiceRules($invoiceId, $businessId){
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentR);
        return $this->db->get();
    }

    function updateInvoce($invoiceId, $data){
        $this->db->where('Id', $invoiceId);
        $this->db->update($this->tbl_parent, $data);
    }


}