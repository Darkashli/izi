<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* @brief Splits an address string containing a street, number and number addition
*
* @param $streetStr string An address string containing a street, number (optional) and number addition (optional)
*
* @return array Data array with the following keys: street, number and numberAddition.
*/
function split_street($streetStr) {
    
    $aMatch         = array();
    $pattern        = '#^([\w[:punct:] ]+) ([0-9]{1,5})([\w[:punct:]\-/]*)$#';
    $matchResult    = preg_match($pattern, $streetStr, $aMatch);
     
    $street         = (isset($aMatch[1])) ? $aMatch[1] : '';
    $number         = (isset($aMatch[2])) ? $aMatch[2] : '';
    $numberAddition = (isset($aMatch[3])) ? $aMatch[3] : '';
    
    if ($street == '' && $number == '' && $numberAddition == '') {
        return array('street' => $streetStr, 'number' => null, 'numberAddition' => null);
    }
    else{
        return array('street' => $street, 'number' => $number, 'numberAddition' => $numberAddition);
    }
  
}
