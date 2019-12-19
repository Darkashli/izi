<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function formatContactName($quotation)
{
    $name = array(
        'formal' => $quotation->ContactFirstName[0].'. '.$quotation->ContactInsertion.' '.$quotation->ContactLastName,
        'informal' => $quotation->ContactFirstName
    );

    return $name;
}

/**
 * Returns the table type of what the quotationrules table should be (table or inline).
 *
 * @param object $quotatoinRules The quotatoin rules.
 * @return string "table" or "inline".
 *
 */
function checkRecurringTableType($quotatoinRules)
{
    $type = 'inline';
    foreach ($quotatoinRules as $rule) {
        if ($rule->Amount > 1) {
            $type = 'table';
            break;
        }
    }
    return $type;
}

function getCosts($quotation)
{
    $ci = & get_instance();
    $businessId = $quotation->BusinessId;

    $quotationId = $quotation->Id;

    $products = getProductsFromDb($businessId, $quotationId, 1);
    $recurrings = getProductsFromDb($businessId, $quotationId, 2);

    $productsTotal = $quotation->WorkAmount;
    $recurringTotal = 0;
    if ($quotation->IsComparison)
    {
        $lowest = (($products[0]->SalesPrice * $products[0]->Amount));
        $highest = null;
        foreach ($products as $product)
        {
            $sum = (($product->SalesPrice * $product->Amount));
            $lowest = ($sum <= $lowest) ? $sum : $lowest;
            $highest = ($sum >= $highest) ? $sum : $highest;
        }
        $lowest += $productsTotal;
        $highest += $productsTotal;
        $lowestPrice = number_format($lowest, 2, '.', '');
        $lowestPrice = preg_replace('/\./', ',', $lowestPrice);
        $highestPrice = number_format($highest, 2, '.', '');
        $highestPrice = preg_replace('/\./', ',', $highestPrice);

        if ($lowestPrice == $highestPrice)
        {
            $totalPrice = '€ '.$highestPrice;
        }
        else
        {
            $totalPrice = '€ '.$lowestPrice.' - € '.$highestPrice;
        }
    }
    else
    {
        foreach ($products as $product)
        {
            $sum = (($product->SalesPrice * $product->Amount));
            $productsTotal += $sum;
        }

        $totalPrice = number_format($productsTotal, 2, '.', '');
        $totalPrice = '€ '.preg_replace('/\./', ',', $totalPrice);

    }

    foreach ($recurrings as $recurring)
    {
        $sum = (($recurring->SalesPrice * $recurring->Amount));

        $recurringTotal += $sum;
    }

    $totalRecurring = number_format($recurringTotal, 2, '.', '');
    $totalRecurring = '€ '.preg_replace('/\./', ',', $totalRecurring);

    $data = array(
        'costs' => $totalPrice,
        'recurring' => $totalRecurring
    );
    return $data;
}

function getProductsFromDb($BusinessId, $QuotationId, $type)
{
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $BusinessId);
    $ci->db->where('QuotationId', $QuotationId);
    $ci->db->where('type', $type);


    $query = $ci->db->get('QuotationRules');

    $products = $query->result();

    return $products;
}

/**
 * Get quotatoin status by status key.
 *
 * @param string $key The key, could be both the key of the defined constant and the ID in the database.
 * @param int $businessId The business ID.
 *
 */
function getQuotationStatus($key, $businessId)
{
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);
    $ci->db->where('Id', $key);
    $query = $ci->db->get('QuotationStatus');
    $statusDb = $query->row();

    if ($statusDb !== null) {
        return $statusDb->Description;
    }
    else{
        $statusses = unserialize(QUOTATIONSTATUSSES);

        if (!array_key_exists($key, $statusses)){
            return null;
        }
        else {
            return $statusses[$key];
        }

    }
}

/**
 * Get quotatoin statusses.
 *
 * @param int $businessId The business ID.
 *
 */
function getQuotationStatusses($businessId)
{
    $statusses = unserialize(QUOTATIONSTATUSSES);

    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);
    $ci->db->order_by('SortingOrder', 'ASC');
    $query = $ci->db->get('QuotationStatus');
    $statussesDb = $query->result();

    foreach ($statussesDb as $statusDb) {
        $statusses[$statusDb->Id] = $statusDb->Description;
    }

    return $statusses;
}

/**
 * Get the last quotatoin status in order.
 *
 * @param int $businessId The business ID.
 *
 */
function getLatestQiotationStatus($businessId)
{
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);
    $ci->db->order_by('SortingOrder', 'DESC');
    $ci->db->limit(1);
    $query = $ci->db->get('QuotationStatus');
    $statusDb = $query->row();

    if ($statusDb == null) {
        $statusses = unserialize(QUOTATIONSTATUSSES);
        $end = end($statusses);
        $return = array(
            'Key' => key($statusses),
            'Value' => $end
        );

        return (object) $return;
    }
    else{
        $return = array(
            'Key' => $statusDb->Id,
            'Value' => $statusDb->Description
        );

        return (object) $return;
    }
}

/**
 * Converts html into a single line string
 *
 */
function oneLiner($html)
{
    $toSpace = array( '<br>', '<br />', '&nbsp;' );

    // Replace above strings for spaces...
    $single = str_replace($toSpace, ' ', $html);
    // Remove html entities...
    $single = strip_tags($single);
    // Replace newlines for spaces...
    $single = str_replace(array("\n", "\r\n", "\r"), ' ', $single);
    // Remove multiple spaces...
    $single = preg_replace('/\s+/', ' ', $single);
    // Trim string.
    $single = trim($single);
    return $single;
}

function getQuotationName($quotationId)
{
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $quotationId);
    $quotation = $ci->db->get('Quotation')->row();

    if (!empty($quotation->CustomerName)) {
        return $quotation->CustomerName;
    }
    else {
        return "$quotation->ContactFirstName $quotation->ContactInsertion $quotation->ContactLastName";
    }
}
