<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getSellerName($sellerId) {
    if ($sellerId != 0) {
        $ci = & get_instance();
        $ci->load->database();

        $ci->db->where('Seller_Id', $sellerId);

        $query = $ci->db->get('Seller');

        return $query->row()->Name;
    }
    return false;
}

function getProductbyArticleNumber($articleNumber){
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('ArticleNumber', $articleNumber);

    return $ci->db->get('Product')->row();
}

function getWarehouseName($nameId){
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $nameId);

    $query = $ci->db->get('Warehouse');

    $naam = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $naam = $row->Name;
    endforeach;

    return $naam;
}

function getTransporterName($transporterId) {
    if ($transporterId != 0) {
        $ci = & get_instance();
        $ci->load->database();

        $ci->db->where('Transporter_Id', $transporterId);

        $query = $ci->db->get('Transporter');

        return $query->row()->Name;
    }
    return false;
}

function getCountSalesorderRules($orderId)
{
    $ci = & get_instance();
    $ci->load->database();
    
    $ci->db->where('SalesOrderId', $orderId);
    
    return $ci->db->count_all_results('SalesOrderRules');
}
