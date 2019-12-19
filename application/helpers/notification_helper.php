<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getUnreadQuotation()
{
    $ci = get_instance();
    $ci->load->database();

    $businessId = $ci->session->userdata('user')->BusinessId;
    $userId = $ci->session->userdata('user')->Id;

    $ci->db->where('BusinessId', $businessId);
    $ci->db->where('UserId', $userId);
    $ci->db->where('Notification', 1);

    $query = $ci->db->get('Quotation');
    $quotations = $query->result();
    return $quotations;
}