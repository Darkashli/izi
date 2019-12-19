<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getWarehouseDropdown($warehouses) {

    if (!empty($warehouses)) {
    	foreach ($warehouses as $warehouse):

    	    $warehouseDrop[$warehouse->Id] = $warehouse->Name;

    	endforeach;

    	return $warehouseDrop;
    }
    else{
    	return array();
    }
}

/**
 * This function is for ordering arrays alphabetically.
 * Use this function with "usort" (http://nl1.php.net/manual/en/function.usort.php).
 */
function sortByWarehouseLocation($a, $b){
	return strcmp($a->WarehouseLocation, $b->WarehouseLocation);
}