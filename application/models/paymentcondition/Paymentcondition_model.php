<?php

class Paymentcondition_model extends CI_Model {

    private $tbl_parent = 'PaymentConditions';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($BusinessId) {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getPaymentCondition($paymentConditionId, $businessId) {
        $this->db->where('Id', $paymentConditionId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }


    function searchPaymentConditionByName($value, $businessId)
    {
        $this->db->like('Name', $value);
        $this->db->where('BusinessId', $businessId);
        return $this->db->get($this->tbl_parent);
    }

    function updatePaymentCondition($paymentConditionId, $data) {
        $this->db->where('Id', $paymentConditionId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createPaymentCondition($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

}
