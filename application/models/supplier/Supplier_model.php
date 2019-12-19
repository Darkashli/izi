<?php

class Supplier_model extends CI_Model {
    
    private $tbl_parent= 'Supplier';
    private $tbl_parentC = 'ContactsS';
    private $tbl_parentI = 'InvoiceSupplier';
    private $tbl_parentRulesI = 'InvoiceRulesSupplier';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }
    
    function getAll($BusinessId){
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getSupplier($SupplierId, $businessId){
        $this->db->where('Id', $SupplierId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function updateSupplier($SupplierId, $data){
        $this->db->where('Id', $SupplierId);
        $this->db->update($this->tbl_parent, $data);
    }
    
    function createSupplier($data){
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
    
    function getContacts($supplierId, $businessId){
        $this->db->where('SupplierId', $supplierId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentC);
        return $this->db->get();
    }
    
    function getContact($contactId, $businessId){
        $this->db->where('Id', $contactId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentC);
        return $this->db->get();
    }
    
    function createContact($data){
        $this->db->insert($this->tbl_parentC, $data);
        return $this->db->insert_id();
    }
    
    function updateContact($data, $contactId){
        $this->db->where('Id', $contactId);
        $this->db->update($this->tbl_parentC, $data);
    }
    
    function getInvoices($supplierId, $businessId){
        $this->db->where('SupplierId', $supplierId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentI);
        return $this->db->get();
    }
    
    function getInvoice($invoiceId, $businessId){
        $this->db->where('Id', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentI);
        return $this->db->get();
    }
    
    function getInvoiceRules($invoiceId, $businessId){
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentRulesI);
        return $this->db->get();
    }
    
    function updateInvoice($invoiceId, $data){
        $this->db->where('Id', $invoiceId);
        $this->db->update($this->tbl_parentI, $data);
    }

}
