<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getDropdownImport($arrayI) {
    $ci = & get_instance();
    $ci->load->database();
    $arrayR = array();
    
    if($arrayI == null){
        return $arrayR;
    }

    foreach ($arrayI as $array) {
        $ci->db->where('Id', $array);

        $query = $ci->db->get('ImportType')->row();
        
        $arrayR[$array] = $query->Name;
    }
    
    return $arrayR;
}
