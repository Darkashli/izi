<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getTimePeriodFromDropdown($timePeriod) {

    $naam = "";
    foreach (unserialize(TIMEPERIOD) as $key => $value):
        
        if($key == $timePeriod){
            $naam = $value;
        }

    endforeach;

    return $naam;
}
