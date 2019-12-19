<?php

class SalesOrder_model extends CI_Model {
    private $tbl_parent = 'SalesOrders';
    private $tbl_parentR = 'SalesOrderRules';
    private $tbl_parentC = 'SalesOrderCustomField';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

     function getAllOrders($businessId){
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getOpenOrders($customerId = 0, $businessId){
        $this->db->where('Invoiced', 0);
        if ($customerId != 0) {
            $this->db->where('customerId', $customerId);
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getOpenPrintedOrders($customerId = 0, $businessId){
        $this->db->where('Invoiced', 0);
        $this->db->where('Printed', 1);
        if ($customerId != 0) {
            $this->db->where('customerId', $customerId);
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getClosedOrders($customerId = 0, $businessId){
        $this->db->where('Invoiced', 1);
        if ($customerId != 0) {
            $this->db->where('customerId', $customerId);
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getSalesOrder($salesOrderId, $businessId){
        $this->db->where('Id', $salesOrderId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getAnonymousOrders(
        $businessId,
        $status = 'open',
        $year = null
    ){
        if ($status == 'closed') {
            $this->db->where('Invoiced', 1);
        }
        else {
            $this->db->where('Invoiced', 0);
        }
        if ($year != null) {
            $this->db->where("YEAR(OrderDate) = $year");
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->where('CustomerId', null);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getOrdersBetweenDateBySellerId($startDate, $endDate, $sellerId, $businessId){
        $this->db->where("OrderDate BETWEEN '$startDate' AND '$endDate'");
        if ($sellerId != 0) {
            $this->db->where('Seller_id', $sellerId);
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getSalesOrderRules($salesOrderId = 0, $businessId){
        if ($salesOrderId != 0) {
            $this->db->where('SalesOrderId', $salesOrderId);
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentR);
        return $this->db->get();
    }

    function getSalesRulesByProductArticleNum($articleNumber, $businessId){
        $this->db->where('ArticleC', $articleNumber);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentR);
        return $this->db->get();
    }

    function getOrderRule($SalesOrderRuleId, $businessId) {
        $this->db->where('OrderNumber', $SalesOrderRuleId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function insertSalesOrder($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

    function insertSalesOrderRule($data) {
        $this->db->insert($this->tbl_parentR, $data);
        return $this->db->insert_id();
    }

    function updateSalesOrder($SalesOrderId, $data) {
        $this->db->where('Id', $SalesOrderId);
        $this->db->update($this->tbl_parent, $data);
    }

    function updateSalesOrderRule($SalesOrderRuleId, $data) {
        $this->db->where('Id', $SalesOrderRuleId);
        $this->db->update($this->tbl_parentR, $data);
    }
    
    function deleteSalesOrderRule($SalesOrderRuleId)
    {
        $this->db->where('Id', $SalesOrderRuleId);
        $this->db->delete($this->tbl_parentR);
    }
    
    public function createCustomField($data)
    {
        return $this->db->insert($this->tbl_parentC, $data);
    }
    
    public function getCustomFields($SalesOrderId, $businessId)
    {
        $this->db->where('SalesOrderId', $SalesOrderId);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_parentC);
    }
}
