<?php

class PurchaseOrder_model extends CI_Model {
    private $tbl_parent = 'PurchaseOrders';
    private $tbl_parentR = 'PurchaseOrderRules';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getOpenOrders($supplierId = 0, $businessId){
        $this->db->where('Invoiced', 0);
        if ($supplierId != 0) {
            $this->db->where('SupplierId', $supplierId);
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getClosedOrders($supplierId = 0, $businessId){
        $this->db->where('Invoiced', 1);
        if ($supplierId != 0) {
            $this->db->where('SupplierId', $supplierId);
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getPurchaseOrder($purchaseOrderId, $businessId){
        $this->db->where('Id', $purchaseOrderId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }


    function getPurchaseOrderRules($purchaseOrderId, $businessId){
        $this->db->where('PurchaseOrderId', $purchaseOrderId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentR);
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
        $this->db->where('SupplierId', null);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function insertPurchaseOrder($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

    function insertPurchaseOrderRule($data) {
        $this->db->insert($this->tbl_parentR, $data);
        return $this->db->insert_id();
    }

    function updatePurchaseOrder($purchaseOrderId, $data) {
        $this->db->where('Id', $purchaseOrderId);
        $this->db->update($this->tbl_parent, $data);
    }

    function updatePurchaseOrderRule($PurchaseOrderRuleId, $data) {
        $this->db->where('Id', $PurchaseOrderRuleId);
        $this->db->update($this->tbl_parentR, $data);
    }
    
    function deletePurchaseOrderRule($PurchaseOrderRuleId) {
        $this->db->where('Id', $PurchaseOrderRuleId);
        $this->db->delete($this->tbl_parentR);
    }
}
