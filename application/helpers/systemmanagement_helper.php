<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getKindHardware($kind) {
    return unserialize(HARDWAREKIND)[$kind];
}

function systemManagementUserDropdown($customerId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('CustomerId', $customerId);

    $query = $ci->db->get('SystemManagementGroup');

    $groups = array();

    foreach ($query->result() as $row):
        $groups[$row->Id] = $row->Name;
    endforeach;

    return $groups;
}
