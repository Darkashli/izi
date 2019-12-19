<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function checkPerm($neededPermLevel) {
    $ci = & get_instance();

    $user = $ci->session->userdata('user');

    if ($user->Level < $neededPermLevel) {
        return false;
    }

    return true;
}

function checkModule($moduleName) {
    $ci = & get_instance();
    $user = $ci->session->userdata('user');

    $business = getBusiness($user->BusinessId);

    if ($business->{$moduleName}) {
        return true;
    }
    
    return false;
}

function isLogged() {
    $ci = & get_instance();

    if ($ci->session->userdata('user')) {
        return true;
    } else {
        if (!$ci->session->userdata('redirect_back')) {
            $ci->session->set_userdata('redirect_back', uri_string());
        }
        return false;
    }
}

function isFromBusiness($businessId) {
    $ci = & get_instance();

    if ($ci->session->userdata('user')->BusinessId == $businessId) {
        return true;
    }
    return false;
}

function getContactFirstName($accountId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $accountId);

    $query = $ci->db->get('Contacts');

    $naam = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $naam = $row->FirstName;
    endforeach;

    return $naam;
}

function getCustomer($customerId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $customerId);

    $query = $ci->db->get('Customers');

    $name = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $name = $row->Name;
    endforeach;

    return $name;
}

function getAccountName($accountId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $accountId);

    $query = $ci->db->get('User');

    $naam = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names

        if (empty($row->Insertion)) {
            $naam = $row->FirstName . ' ' . $row->LastName;
        } else {
            $naam = $row->FirstName . ' ' . $row->Insertion . ' ' . $row->LastName;
        }


    endforeach;

    return $naam;
}

function getAccountMail($accountId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $accountId);

    $query = $ci->db->get('User');

    $mail = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $mail = $row->Email;
    endforeach;

    return $mail;
}

function getContactName($contactId) {
    if ($contactId == 0) {
        return "";
    }

    $ci = & get_instance();
    $ci->db->cache_on();
    $ci->load->database();

    $ci->db->where('Id', $contactId);

    $query = $ci->db->get('Contacts');

    $naam = "";

    $row = $query->row();

    //get the full name by concatinating the first and last names

    if (empty($row->Insertion)) {
        $naam = $row->FirstName . ' ' . $row->LastName;
    } else {
        $naam = $row->FirstName . ' ' . $row->Insertion . ' ' . $row->LastName;
    }

    $ci->db->cache_off();
    return $naam;
}

function getContactMail($contactId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $contactId);

    $query = $ci->db->get('Contacts');

    $mail = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $mail = $row->Email;
    endforeach;

    return $mail;
}

function getCustomerName($clientId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $clientId);

    $query = $ci->db->get('Customers');

    $naam = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $naam = $row->Name;
    endforeach;

    return $naam;
}

function getSupplierName($supplierId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $supplierId);

    $query = $ci->db->get('Supplier');

    $naam = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $naam = $row->Name;
    endforeach;

    return $naam;
}

function getSellersName($sellerId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Seller_id', $sellerId);

    $query = $ci->db->get('Seller');

    $naam = "";
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $naam = $row->Name;
    endforeach;

    return $naam;
}

function getUserDropdown($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);
    $ci->db->order_by('Activated', 'ASC');
    $ci->db->order_by('FirstName', 'ASC');

    $query = $ci->db->get('User');

    $users = array();
    $disabled = array();

    foreach ($query->result() as $row):
        if ($row->Activated != 1) {
            $disabled[$row->Id] = $row->Id;
        }

        if (empty($row->Insertion)) {
            $users[$row->Id] = $row->FirstName . ' ' . $row->LastName;
        } else {
            $users[$row->Id] = $row->FirstName . ' ' . $row->Insertion . ' ' . $row->LastName;
        }
    endforeach;

    return array($users, $disabled);
}

function getContactDropdown($customerId, $businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('CustomerId', $customerId);
    $ci->db->where('BusinessId', $businessId);
    $ci->db->order_by('Employed', 'DESC');
    $ci->db->order_by('FirstName', 'ASC');


    $query = $ci->db->get('Contacts');

    $users = array();
    $disabled = array();

    foreach ($query->result() as $row):

        if ($row->Employed != 1) {
            $disabled[$row->Id] = $row->Id;
        }


        if (empty($row->Insertion)) {
            $users[$row->Id] = $row->FirstName . ' ' . $row->LastName;
        } else {
            $users[$row->Id] = $row->FirstName . ' ' . $row->Insertion . ' ' . $row->LastName;
        }
    endforeach;

    return array($users, $disabled);
}

function getContactSupplierDropdown($supplierId, $businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('SupplierId', $supplierId);
    $ci->db->where('BusinessId', $businessId);

    $query = $ci->db->get('ContactsS');

    $users = array();

    foreach ($query->result() as $row):
        if (empty($row->Insertion)) {
            $users[$row->Id] = $row->FirstName . ' ' . $row->LastName;
        } else {
            $users[$row->Id] = $row->FirstName . ' ' . $row->Insertion . ' ' . $row->LastName;
        }
    endforeach;

    return $users;
}

function getAddressFromZipCode($zipCode, $number) {
    $headers = array(
        'X-Api-Key: xmV8ih5rgH53s3sfDJTIQ9QFAixyBiyhets6xgf1'
    );
    $url = 'https://postcode-api.apiwise.nl/v2/addresses/?postcode=' . $zipCode . '&number=' . $number;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    $data = json_decode($response);

    curl_close($curl);

    if ($response != null) {

        $result = array(
            'Street' => $data->_embedded->addresses[0]->street,
            'City' => $data->_embedded->addresses[0]->city->label
        );
    } else {
        $result = array(
            'Street' => '',
            'City' => ''
        );
    }

    return $result;
}
