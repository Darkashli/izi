<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getUser($userId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $userId);
    return $ci->db->get_where('User')->row();
}

function getUserFullName($userId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $userId);
    $user = $ci->db->get('User')->row();
    
    $userFullName = $user->FirstName.' '.$user->Insertion.' '.$user->LastName;
    
    // Remove double spaces.
    $userFullName = preg_replace('/\s+/', ' ', $userFullName);
    
    $userFullName = trim($userFullName);
    
    return $userFullName;
}
