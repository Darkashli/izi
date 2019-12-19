<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getProductgroupDropdown($productgroups) {

    foreach ($productgroups as $productgroup):

        $productgroupDrop[$productgroup->Id] = $productgroup->Name;

    endforeach;

    return $productgroupDrop;
}

function getProductGroupName($productGroupId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $productGroupId);

    $query = $ci->db->get('Productgroup');
    $result = $query->row();

    if($result == null){
        return "";
    }else{
        return $result->Name;
    }
}

function getProductGroepId($productGroupName) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Name', $productGroupName);

    $query = $ci->db->get('Productgroup');
    $result = $query->row();
    
    if($result == null){
        return 0;
    }else{
        return $result->Id;
    }
}
