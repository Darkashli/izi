<?php

class Import_model extends CI_Model {
    
    private $tbl_customer = 'Customers';
    private $tbl_supplier = 'Supplier';
    private $tbl_invoice = 'Invoice';
    private $tbl_invoiceSupplier = 'InvoiceSupplier';

    function getCustomerByInvoiceId($invoiceId, $businessId){
        $this->db->join($this->tbl_invoice, 'Customers.Id = Invoice.CustomerId', 'left');
        $this->db->where('Invoice.Id', $invoiceId);
        $this->db->where('Customers.BusinessId', $businessId);
        $this->db->from($this->tbl_customer);
        return $this->db->get();
    }

    function getSupplierByInvoiceId($invoiceId, $businessId){
        $this->db->join($this->tbl_invoiceSupplier, 'Supplier.Id = InvoiceSupplier.SupplierId', 'left');
        $this->db->where('InvoiceSupplier.Id', $invoiceId);
        $this->db->where('Supplier.BusinessId', $businessId);
        $this->db->from($this->tbl_supplier);
        return $this->db->get();
    }

    function getInvoice($invoiceId, $businessId){
        $this->db->where('Id', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_invoice);
    }

    function getInvoiceS($invoiceId, $businessId){
        $this->db->where('Id', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_invoiceSupplier);
    }
    
    function searchInvoice($invoiceNumber, $businessId){
        $this->db->like('InvoiceNumber', $invoiceNumber);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_invoice);
        return $this->db->get();
    }

    function searchInvoiceByRemark($remark, $businessId){
        $this->db->where('ImportPaymentRemark', $remark);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_invoice);
    }

    function searchInvoiceSByRemark($remark, $businessId){
        $this->db->where('ImportPaymentRemark', $remark);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_invoiceSupplier);
    }

    function searchInvoiceSupplier($invoiceNumber, $businessId){
        $this->db->like('InvoiceNumber', $invoiceNumber);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_invoiceSupplier);
        return $this->db->get();
    }

    function searchBetterInvoiceSupplier($invoiceNumber, $businessId){
        $this->db->or_like('InvoiceNumber', substr($invoiceNumber, 0, 5), 'after', true);
        $this->db->or_like('InvoiceNumber', substr($invoiceNumber, -5), 'before', true);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_invoiceSupplier);
        return $this->db->get();
    }

    function updateInvoiceSupplier($invoiceId, $data) {
        $this->db->where('Id', $invoiceId);
        $this->db->update($this->tbl_invoiceSupplier, $data);
    }

}