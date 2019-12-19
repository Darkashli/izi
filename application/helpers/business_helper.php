<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getBusinessInvoiceNumber($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $businessId);

    $query = $ci->db->get('Business');

    $business = $query->row();

    return $business->InvoiceNumber;
}

function getBusinessSalesOrderNumber($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $businessId);

    $query = $ci->db->get('Business');

    $business = $query->row();

    return $business->SalesOrderNumber;
}

function getBusinessPurchaseOrderNumber($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $businessId);

    $query = $ci->db->get('Business');

    $business = $query->row();

    return $business->PurchaseOrderNumber;
}

function getBusinessPurchaseNumber($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $businessId);

    $query = $ci->db->get('Business');

    $business = $query->row();

    return $business->PurchaseNumber;
}

function getHeadCustomerId($customerId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $customerId);

    $query = $ci->db->get('Customers');

    $customer = $query->row();

    return $customer->HeadCustomerId;
}

function getBusiness($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $businessId);

    $query = $ci->db->get('Business');

    $business = $query->row();
    return $business;
}
