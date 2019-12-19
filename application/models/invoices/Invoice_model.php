<?php

class Invoice_model extends CI_Model {

    private $tbl_parent = 'Invoice';
    private $tbl_parentR = 'InvoiceRules';
    private $tbl_parentP = 'InvoicePayments';
    private $tbl_parentS = 'InvoiceSupplier';
    private $tbl_parentRS = 'InvoiceRulesSupplier';
    private $tbl_parentPS = 'InvoiceSupplierPayments';
    private $tbl_reminder = 'InvoiceReminders';
    private $tbl_parentC = 'InvoiceCustomField';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function insertInvoice($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

    function insertInvoiceRule($data) {
        $this->db->insert($this->tbl_parentR, $data);
        return $this->db->insert_id();
    }

    function insertInvoicePayment($data) {
        $this->db->insert($this->tbl_parentP, $data);
        return $this->db->insert_id();
    }
    
    function insertInvoiceSupplierPayment($data) {
        $this->db->insert($this->tbl_parentPS, $data);
        return $this->db->insert_id();
    }

    function addReminder($data){
        $this->db->insert($this->tbl_reminder, $data);
    }

    function updateInvoiceRule($invoiceRuleId, $data) {
        $this->db->where('Id', $invoiceRuleId);
        $this->db->update($this->tbl_parentR, $data);
    }

    function updateInvoice($invoiceId, $data) {
        $this->db->where('Id', $invoiceId);
        $this->db->update($this->tbl_parent, $data);
    }

    function getInvoiceRule($invoiceNumber, $businessId) {
        $this->db->where('InvoiceNumber', $invoiceNumber);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentR);
        return $this->db->get();
    }
    
    function getInvoiceRuleById($invoiceId, $businessId){
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentR);
        return $this->db->get();
    }

    function getInvoicePayment($invoicePaymentId, $businessId){
        $this->db->where('Id', $invoicePaymentId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentP);
        return $this->db->get();
    }

    function GetInvoicePaymentsByInvoice($invoiceId, $businessId){
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->order_by('Date', 'ASC');
        $this->db->from($this->tbl_parentP);
        return $this->db->get();
    }

    function getSumInvoicePaymentAmount($invoiceId, $businessId){
        $this->db->select_sum('Amount', 'Amount');
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentP);
        return $this->db->get();
    }
    
    function getInvoiceSupplierPayment($invoicePaymentId, $businessId){
        $this->db->where('Id', $invoicePaymentId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentPS);
        return $this->db->get();
    }

    function GetInvoiceSupplierPaymentsByInvoice($invoiceId, $businessId){
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->order_by('Date', 'ASC');
        $this->db->from($this->tbl_parentPS);
        return $this->db->get();
    }
    
    function getSumInvoiceSupplierPaymentAmount($invoiceId, $businessId){
        $this->db->select_sum('Amount', 'Amount');
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentPS);
        return $this->db->get();
    }

    function getInvoiceRuleByArticleNumber($articleNum, $businessId){
        $this->db->where('ArticleC', $articleNum);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentR);
        return $this->db->get();
    }

    function GetAllReminders($invoiceId, $businessId){
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_reminder);
        return $this->db->get();
    }

    function updateInvoiceNumber($businessId, $data) {
        $this->db->where('Id', $businessId);
        $this->db->update('Business', $data);
    }

    function insertInvoiceSupplierRule($data) {
        $this->db->insert($this->tbl_parentRS, $data);
        return $this->db->insert_id();
    }

    function insertInvoiceSupplier($data) {
        $this->db->insert($this->tbl_parentS, $data);
        return $this->db->insert_id();
    }

    function updateInvoiceSupplierRule($invoiceRuleId, $data) {
        $this->db->where('Id', $invoiceRuleId);
        $this->db->update($this->tbl_parentRS, $data);
    }
    
    function updateInvoiceSupplier($invoiceId, $data) {
        $this->db->where('Id', $invoiceId);
        $this->db->update($this->tbl_parentS, $data);
    }

    function getAllInvoice($businessId){
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_parent);
    }

    function getAllInvoiceS($businessId){
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_parentS);
    }

    function getInvoiceByCustomerID($customerId, $businessId){
        $this->db->where('CustomerId', $customerId);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_parent);
    }

    function getInvoiceSBySuplierID($supplierId, $businessId){
        $this->db->where('SupplierId', $supplierId);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_parentS);
    }

    function getInvoiceSupplierRule($invoiceId, $businessId) {
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentRS);
        return $this->db->get();
    }

    function getInvoiceSupplierRuleByArticleNumber($articleNum, $businessId){
        $this->db->where('ArticleC', $articleNum);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentRS);
        return $this->db->get();
    }

    function getBetweenDateC($from, $to, $customerId, $BusinessId) {
        $from = strtotime($from);
        $to = strtotime($to);
        
        if($customerId != 0){
            $this->db->where('CustomerId', $customerId);
        }

        $this->db->where("InvoiceDate BETWEEN $from AND $to");
        $this->db->where('BusinessId', $BusinessId);
        
        $this->db->order_by('InvoiceDate', 'ASC');
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getBetweenDateS($from, $to, $supplierId, $businessId) {
        $from = strtotime($from);
        $to = strtotime($to);
        
        if($supplierId != 0){
            $this->db->where('SupplierId', $supplierId);
        }

        $this->db->where("InvoiceDate BETWEEN $from AND $to");
        $this->db->order_by('InvoiceDate', 'ASC');
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentS);
        return $this->db->get();
    }
    
    function getOpenInvoice($businessId){
        $this->db->where('PaymentDate', 0);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getOpenAnonymousInvoice($businessId){
        $this->db->where('PaymentDate', 0);
        $this->db->where('BusinessId', $businessId);
        $this->db->where('CustomerId', null);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getClosedAnonymousInvoice($businessId){
        $this->db->where('PaymentDate <> ', 0);
        $this->db->where('BusinessId', $businessId);
        $this->db->where('CustomerId', null);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getOpenAnonymousInvoiceS($businessId){
        $this->db->where('PaymentDate', 0);
        $this->db->where('BusinessId', $businessId);
        $this->db->where('SupplierId', null);
        $this->db->from($this->tbl_parentS);
        return $this->db->get();
    }

    function getClosedAnonymousInvoiceS($businessId){
        $this->db->where('PaymentDate <> ', 0);
        $this->db->where('BusinessId', $businessId);
        $this->db->where('SupplierId', null);
        $this->db->from($this->tbl_parentS);
        return $this->db->get();
    }
    
    function deleteInvoiceRule($invoiceRuleId)
    {
        $this->db->where('Id', $invoiceRuleId);
        $this->db->delete($this->tbl_parentR);
    }

    function deleteInvoiceSupplier($invoiceSupplierId){
        $this->db->where('Id', $invoiceSupplierId);
        $this->db->delete($this->tbl_parentS);
    }

    function deleteInvoiceSupplierRule($invoiceSupplierRuleId){
        $this->db->where('Id', $invoiceSupplierRuleId);
        $this->db->delete($this->tbl_parentRS);
    }

    function deleteInvoicePayment($invoicePaymentId){
        $this->db->where('Id', $invoicePaymentId);
        $this->db->delete($this->tbl_parentP);
    }
    
    function deleteInvoiceSupplierPayment($invoicePaymentId){
        $this->db->where('Id', $invoicePaymentId);
        $this->db->delete($this->tbl_parentPS);
    }
    
    public function createCustomField($data)
    {
        return $this->db->insert($this->tbl_parentC, $data);
    }
    
    public function getCustomFields($invoiceId, $businessId)
    {
        $this->db->where('InvoiceId', $invoiceId);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_parentC);
    }
}
