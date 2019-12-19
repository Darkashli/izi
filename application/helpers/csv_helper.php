<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function csv_to_array($file_name) {
    $data = $header = array();
    $i = 0;
    $file = fopen($file_name, 'r');
    while (($line = fgetcsv($file, 1000, ',', '"')) !== FALSE) {
        if ($i == 0) {
            $header = $line;
        } else {
            $data[] = $line;
        }
        $i++;
    }
    fclose($file);
    foreach ($data as $key => $_value) {
        $new_item = array();
        $new_item[$header] = str_getcsv($key, ",", '"');
        /*foreach ($_value as $key => $value) {
            $new_item[$header[$key]] = explode(',',$value);
        }*/
        $_data[] = $new_item;
    }
    return $_data;
}

/**
 * Generates csv file of the current product stock.
 *
 * @param int $productgroup.    default: null. If defined, only products of a product group are included.
 * @param int $supplier.        default: null. If defined, only products of a suppler are included.
 * @return array                An array with the data that for the csv file.
 */
function stockList($businessId, $productgroupId = null, $supplierId = null){
    $ci = & get_instance();
  
    $ci->load->model('product/Product_model', '', TRUE);
    $ci->load->model('salesorders/SalesOrder_model', '', TRUE);
    $ci->load->helper('productgroup');
    $ci->load->helper('salesorder');

    // Create the first row with headers.
    $dataCsv = array(
        array(
            'PRODUCT_ACTIEF',           // #1
            'ARTIKELNUMMER',            // #2
            'EAN_CODE',                 // #3
            'OMSCHRIJVING',             // #4
            'LEVERANVIER_ID',           // #5
            'LEVERANCIER',              // #6
            'PRODUCTGROEP_ID',          // #7
            'PRODUCTGROEP',             // #8
            'INKOOPPRIJS_EX_BTW',       // #9
            'VERKOOPPRIJS_EX_BTW',      // #10
            'VVP',                      // #11
            'BTW',                      // #12
            'IS_VOORRAADPRODUCT',       // #13
            'SOORT_PRODUCT',            // #14
            'MAGAZIJNLOCATIE',          // #15
            'MAGAZIJN_ID',              // #16
            'MAGAZIJN',                 // #17
            'TECHNISCHE_VOORRAAD',      // #18
            'IN_ORDER',                 // #19
            'BESCHIKBARE_VOORRAAD'      // #20
        )
    );

    $products = $ci->Product_model->getAll($businessId)->result();

    foreach ($products as $product) {

        if ($productgroupId != null && $productgroupId != $product->ProductGroup) {
            continue;
        }
        if ($supplierId != null && $supplierId != $product->SupplierId) {
            continue;
        }

        $countBackOrder = 0;
        $salesOrderRules = $ci->SalesOrder_model->getSalesRulesByProductArticleNum($product->ArticleNumber, $businessId)->result();

        foreach ($salesOrderRules as $salesOrderRule) {
            $salesOrder = $ci->SalesOrder_model->getSalesOrder($salesOrderRule->SalesOrderId, $businessId)->row();
            if (!empty($salesOrder) && $salesOrder->Invoiced != 1) {
                $countBackOrder += $salesOrderRule->Amount;
            }
        }

        // Fill the array.
        $dataCsv[] = array(
            $product->Active,                                       // #1
            $product->ArticleNumber,                                // #2
            $product->EanCode ?? "",                                // #3
            $product->Description,                                  // #4
            $product->SupplierId ?? 0,                              // #5
            getSupplierName($product->SupplierId),                  // #6
            $product->ProductGroup,                                 // #7
            getProductGroupName($product->ProductGroup),            // #8
            number_format($product->PurchasePrice, 2, ',', '.'),    // #9
            number_format($product->SalesPrice, 2, ',', '.'),       // #10
            number_format($product->Vvp, 2, ',', '.'),              // #11
            $product->BTW,                                          // #12
            $product->ProductKind == 1 ? 1 : 0,                     // #13
            $product->Type,                                         // #14
            $product->WarehouseLocation,                            // #15
            $product->Warehouse,                                    // #16
            getWarehouseName($product->Warehouse),                  // #17
            $product->Stock,                                        // #18
            $countBackOrder,                                        // #19
            $product->Stock - $countBackOrder                       // #20
        );
    }

    return $dataCsv;

}
