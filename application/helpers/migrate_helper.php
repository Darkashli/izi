<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getAccountIdByName($userName, $businessId) {

    if (is_numeric($userName)) {
        return $userName;
    }

    $ci = & get_instance();
    $ci->load->database();
    $ci->db->where('BusinessId', $businessId);

    $query = $ci->db->get('User');

    $naam = "";
    $accountId = 0;
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names

        if (empty($row->Insertion)) {
            $naam = $row->FirstName . ' ' . $row->LastName;
        } else {
            $naam = $row->FirstName . ' ' . $row->Insertion . ' ' . $row->LastName;
        }

        if (strtolower($userName) == strtolower($naam)) {
            $accountId = $row->Id;
        }


    endforeach;

    return $accountId;
}

function getProductGroupIdByName($productGroupName, $bussinessId){
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->where('Name', $productGroupName);
    $ci->db->where('BusinessId', $bussinessId);
    
    $query = $ci->db->get('Productgroup');
    
    foreach($query->result() as $row){
        $productGroupId = $row->Id;
    }
    
    return $productGroupId;
}

function getContactIdByName($contactName, $customerId) {

    if (is_numeric($contactName)) {
        return $contactName;
    }

    $ci = & get_instance();
    $ci->load->database();
    $ci->db->where('CustomerId', $customerId);

    $query = $ci->db->get('Contacts');

    $naam = "";
    $contactId = 0;
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names

        if (empty($row->Insertion)) {
            $naam = $row->FirstName . ' ' . $row->LastName;
        } else {
            $naam = $row->FirstName . ' ' . $row->Insertion . ' ' . $row->LastName;
        }

        if (strtolower($contactName) == strtolower($naam)) {
            $contactId = $row->Id;
        }
    endforeach;

    if ($contactId == 0) {
        foreach ($query->result() as $row):
            //get the full name by concatinating the first and last names
            $naam = $row->FirstName . ' ' . $row->LastName;

            if (strtolower($contactName) == strtolower($naam)) {
                $contactId = $row->Id;
            }
        endforeach;
    }

    return $contactId;
}

function getStatusIdByDescription($statusDescription, $businessId) {
    if (is_numeric($statusDescription)) {
        return $statusDescription;
    }

    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);

    $query = $ci->db->get('Status');

    $statusId = 0;
    foreach ($query->result() as $row):
        if (strtolower($statusDescription) == strtolower($row->Description)) {
            $statusId = $row->Id;
        }
    endforeach;

    return $statusId;
}

function getNatureOfWorkByDescription($description, $businessId) {
    if (is_numeric($description)) {
        return $description;
    }

    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);

    $query = $ci->db->get('NatureOfWork');

    $naturOfWorkId = 0;
    foreach ($query->result() as $row):
        if (strtolower($description) == strtolower($row->Description)) {
            $naturOfWorkId = $row->Id;
        }
    endforeach;

    return $naturOfWorkId;
}

function getContactMomentByDescription($description, $businessId){
    if (is_numeric($description)) {
        return $description;
    }

    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);

    $query = $ci->db->get('ContactMoment');

    $contactMomentId = 0;
    foreach ($query->result() as $row):
        if (strtolower($description) == strtolower($row->Description)) {
            $contactMomentId = $row->Id;
        }
    endforeach;

    return $contactMomentId;
}

function getProjectnumberByNumber($projectNumber, $businessId){
    if(is_numeric($projectNumber)){
        return $projectNumber;
    }
    
    $ci = & get_instance();
    $ci->load->database();
    
    $ci->db->where('ProjectNumber', $projectNumber);
    $ci->db->where('BusinessId', $businessId);
    
    $query = $ci->db->get('Projects')->row();
    
    $projectId = 0;
    
    if($query != null){
        $projectId = $query->Id;
    }
    
    return $projectId;
    
}
