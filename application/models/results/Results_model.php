<?php

class Results_model extends CI_Model {

    private $tbl_parent = 'Invoice';
    private $tbl_parentS = 'InvoiceSupplier';
    private $tbl_parentP = 'InvoicePayments';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function getSold($businessId, $startMonth, $endMonth) {
        $this->db->where('InvoiceDate >=', strtotime($startMonth));
        $this->db->where('InvoiceDate <=', strtotime($endMonth));
        $this->db->where('BusinessId', $businessId);
        $this->db->select_sum('TotalEx');
        $this->db->select_sum('TotalIn');
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    public function getBought($businessId, $startMonth, $endMonth) {
        $this->db->where('InvoiceDate >=', strtotime($startMonth));
        $this->db->where('InvoiceDate <=', strtotime($endMonth));
        $this->db->where('BusinessId', $businessId);
        $this->db->select_sum('TotalEx');
        $this->db->select_sum('TotalIn');
        $this->db->from($this->tbl_parentS);
        return $this->db->get();
    }

    public function getRevenueMonth($startMonth, $BusinessId) {
        $this->db->where('InvoiceDate >=', strtotime($startMonth));
        $this->db->where('InvoiceDate <=', strtotime($startMonth . '+1 month'));
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    public function getSoldPayment($businessId, $startMonth, $endMonth) {
        $this->db->where('Date >=', strtotime($startMonth));
        $this->db->where('Date <=', strtotime($endMonth));
        $this->db->where('BusinessId', $businessId);
        $this->db->select_sum('Amount');
        $this->db->from($this->tbl_parentP);
        return $this->db->get();
    }

}
