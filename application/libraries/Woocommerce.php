<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use Automattic\WooCommerce\Client;

class Woocommerce {
	private $ci;
	private $webshop;
	private $client;
	private $tbl_webshop = 'Webshop';

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->database();
		$this->ci->load->model('webshop/Webshop_model');
		$this->ci->load->model('product/Product_model');
		$this->ci->load->model('product/ProductImage_model');
		$this->ci->load->model('productgroup/Productgroup_model');
		$this->ci->load->model('paymentcondition/Paymentcondition_model');
		$this->ci->load->model('salesorders/SalesOrder_model');
		$this->ci->load->model('business/Business_model');
		$this->ci->load->model('invoices/Invoice_model');
		$this->ci->load->model('customers/Customers_invoicesmodel');
		$this->ci->load->model('quotations/Quotation_model');
		$this->ci->load->model('webshop/WoocommerceOrderImport_model');
		$this->ci->load->helper('address');
		$this->ci->load->helper('name');
		
		$businessId = $this->ci->session->userdata('user')->BusinessId;
		
		$this->webshop = $this->ci->Webshop_model->getWebshop($businessId)->row();
		
		if ($this->webshop != null && $this->webshop->Active == 1) {
			$this->client = new Client(
				$this->webshop->Url, // Your store URL
				$this->webshop->ApiKey, // Your consumer key
				$this->webshop->Secret, // Your consumer secret
				[
					'wp_api' => true, // Enable the WP REST API integration
					'version' => 'wc/v3' // WooCommerce WP REST API version
				]
			);
		}
	}
	
	public function testConn()
	{
		try {
			$this->client->get('');
		} catch (\Exception $e) {
			return false;
		}
		return true;
	}
	
	public function test()
	{
		if ($this->client == null) {
			return null;
		}
		
		return $this->client->get('products/34');
	}
	
	public function saveProduct($productId)
	{
		if ($this->client == null) {
			return false;
		}
		
		$businessId = $this->ci->session->userdata('user')->BusinessId;
		$business = $this->ci->Business_model->getBusiness($businessId)->row();
		$product = $this->ci->Product_model->getProduct($productId, $businessId)->row();
		$productGroup = $this->ci->Productgroup_model->getProductgroup($product->ProductGroup, $businessId)->row();
		
		if ($product == NULL) {
			return false;
		}
		
		// Get products for upsells.
		$upsellShopIds = array();
		$upsellIds = unserialize($product->Upsells);
		if ($upsellIds != false) {
			foreach ($upsellIds as $upsellId) {
				$upsell = $this->ci->Product_model->getProduct($upsellId, $businessId)->row();
				// Check if the product is active, a webshop product and has a shop id.
				if ($upsell->Active == 1 && $upsell->isShop == true && $upsell->shopId != 0) {
					$upsellShopIds[] = $upsell->shopId;
				}
			}
		}
		
		// Get products for crossells.
		$crossellShopIds = array();
		$crossellIds = unserialize($product->Crossells);
		if ($crossellIds != false) {
			foreach ($crossellIds as $crossellId) {
				$crossell = $this->ci->Product_model->getProduct($crossellId, $businessId)->row();
				// Check if the product is active, a webshop product and has a shop id.
				if ($crossell->Active == 1 && $crossell->isShop == true && $crossell->shopId != 0) {
					$crossellShopIds[] = $crossell->shopId;
				}
			}
		}
		
		// Prepare product data for the Woocommerce product.
		$data = array(
			'name' => $product->Description,
			'type' => 'simple',
			'regular_price' => $product->SalesPrice,
			'description' => $product->LongDescription,
			'short_description' => $product->Woocommerce_Description,
			'status' => ($product->Active == 1 && $product->isShop == 1) ? "publish" : "draft",
			'sku' => $product->ArticleNumber,
			'weight' => $product->Weight,
			'dimensions' => array(
				'length' => $product->Length,
				'width' => $product->Width,
				'height' => $product->Height
			),
			'sold_individually' => $product->SoldIndividually,
			'upsell_ids' => $upsellShopIds,
			'cross_sell_ids' => $crossellShopIds
		);
		
		// Add a sale price if the product is in sale.
		if ($product->WoocommerceInSale == 1) {
			$data['sale_price'] 		= $product->SalePrice;
			$data['date_on_sale_from'] 	= $product->SalePriceDatesFrom;
			$data['date_on_sale_to'] 	= $product->SalePriceDatesTo;
		}
		else{
			$data['sale_price'] 		= "";
			$data['date_on_sale_from'] 	= "";
			$data['date_on_sale_to'] 	= "";
		}
		
		// Add stock if the product is a stock product.
		if ($product->ProductKind == 1) {
			$data['manage_stock'] = true;
			$data['stock_quantity'] = $product->Stock;
		}
		else {
			$data['manage_stock'] = false;
		}
		
		// Link the product category if the product is linked to a productgroup.
		if ($productGroup->IsShop ==  1 && $productGroup->ShopId != null) {
			$data['categories'] = array(
				array(
					'id' => $productGroup->ShopId
				)
			);
		}

		// Add images to the product.
		if (!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) { // Image uploading does not work on localhost.
			$data['images'] = array();
			
			$productImages = $this->ci->ProductImage_model->getFromProduct($productId, $businessId)->result();
			foreach ($productImages as $productImage) {
				$data['images'][] = array(
					'src' => base_url("uploads/$business->DirectoryPrefix/products/$productId/$productImage->FileName"),
					'position' => $productImage->Position
				);
			}
		}

		// Create Woocommerce product if not exists. Otherwise, update the product.
		if ($product->shopId != 0) {
			try {
				$this->client->post('products/'.$product->shopId, $data);
			} catch (\Exception $e) {
				return false;
			}
		}
		else {
			try {
				$woocommerceProduct = $this->client->post('products', $data);
			} catch (\Exception $e) {
				return false;
			}
			
			// Add the webshop product id to the iziAccount product id.
			$data = array('shopId' => $woocommerceProduct->id);
			$this->ci->Product_model->updateProduct($product->Id, $data);
		}
		
		return true;
	}
	
	public function deleteProduct($woocommerceProductId)
	{
		if ($this->client == null) {
			return false;
		}
		
		$data = array('delete' => array($woocommerceProductId));

		try {
			// $this->client->delete('products/'.$product->shopId, ['force' => true]);
			
			// This is a workaround if the ->delete() function does not work.
			$this->client->post('products/batch', $data);
		} catch (\Exception $e) {
			// echo "<pre>";
			// print_r($e);
			// echo "</pre>";
			// die();
			return false;
		}
		
		$product = $this->ci->Product_model->getProductByWebshopId($woocommerceProductId)->row();
		if ($product != null) {
			$data = array('shopId' => 0);
			$this->ci->Product_model->updateProduct($product->Id, $data);
		}
		
		return true;
	}
	
	public function saveProductgroup($productGroupId)
	{
		if ($this->client == null) {
			throw new \Exception("Er kan geen verbinding met de webshop tot stand worden gebracht", 1);
		}
		
		$businessId = $this->ci->session->userdata('user')->BusinessId;
		$business = $this->ci->Business_model->getBusiness($businessId)->row();
		$productGroup = $this->ci->Productgroup_model->getProductgroup($productGroupId, $businessId)->row();
		
		if ($productGroup == null) {
			throw new \Exception("De productgroep kan niet worden gevonden", 1);
		}
		
		$wData = array(
			'name' => $productGroup->Name,
			'description' => $productGroup->Description,
			'parent' => 0
		);
		
		// Set parent id.
		if (!empty($productGroup->ParentId)) {
			$parentProductgroup = $this->ci->Productgroup_model->getProductgroup($productGroup->ParentId, $businessId)->row();
			if (!empty($parentProductgroup->ShopId)) {
				$wData['parent'] = $parentProductgroup->ShopId;
			}
		}
		
		// Set product category image.
		if (!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) { // Image uploading does not work on localhost.
			if ($productGroup->Image != NULL) {
				$wData['image'] = array(
					'src' => base_url("uploads/$business->DirectoryPrefix/productgroups/$productGroup->Id/$productGroup->Image")
				);
			}
		}
		
		if ($productGroup->ShopId == null) {
			try {
				$categoryId = $this->client->post('products/categories', $wData)->id;
			} catch (\Exception $e) {
				throw new \Exception('Caught exception: ' .  $e->getMessage() . "\n", 1);
				
			}
			
			// Save the shop id.
			$data = array(
				'ShopId' => $categoryId,
			);
			$this->ci->Productgroup_model->updateProductgroup($productGroup->Id, $data);
		}
		else {
			try {
				$this->client->post("products/categories/$productGroup->ShopId", $wData);
			} catch (\Exception $e) {
				throw new \Exception('Caught exception: ' .  $e->getMessage() . "\n", 1);
			}
		}
		
		return true;
	}
	
	public function deleteProductgroup($productGroupId)
	{
		if ($this->client == null) {
			return false;
		}
		
		$businessId = $this->ci->session->userdata('user')->BusinessId;
		$productGroup = $this->ci->Productgroup_model->getProductgroup($productGroupId, $businessId)->row();
		
		if ($productGroup == null) {
			throw new \Exception("Productgroep niet gevonden", 1);
		}
		
		if ($productGroup->ShopId != null) {
			try {
				$this->client->delete("products/categories/$productGroup->ShopId", ['force' => true]);
			} catch (\Exception $e) {
				throw new \Exception('Caught exception: ' .  $e->getMessage() . "\n", 1);
				
			}
		}
		
		$data = array('ShopId' => null);
		$this->ci->Productgroup_model->updateProductgroup($productGroupId, $data);
		
		return true;
	}
	
	public function getAllProductsExclude($excludeIds)
	{
		$params = array(
			'per_page' => 99,
			'exclude' => $excludeIds
		);
		
		return $this->client->get('products', $params);
	}
	
	/**
	 * Imports orders from Woocommerec as an invoice, quotation or order.
	 *
	 * To import custom product options from the order the webshop requires the "Product Options for WooCommerce" plugin.
	 * (https://nl.wordpress.org/plugins/product-options-for-woocommerce/)
	 *
	 */
	public function syncOrdersToIzi()
	{
		if ($this->client == null) {
			return null;
		}
		
		if ($this->webshop->OrderFormat == null) {
			return null;
		}
		
		$businessId = $this->ci->session->userdata('user')->BusinessId;
		$business = $this->ci->Business_model->getBusiness($businessId)->row();
		
		$woocommerceOrderImport = $this->ci->WoocommerceOrderImport_model->getLatest($this->webshop->Id)->row();
		
		$params = array(
			'order' => 'asc'
		);
		// If a previous import has been runned, get only orders after that date.
		if ($woocommerceOrderImport != null) {
			$params['after'] = date('c', strtotime($woocommerceOrderImport->ImportDate));
		}
		
		$woocommerceOrders = $this->client->get('orders', $params);
		$importIds = array();
		
		$this->ci->db->trans_start();
		
		foreach ($woocommerceOrders as $woocommerceOrder) {
			
			// Check if payment condition exists in iziAccount and add payment condition if not.
			if ($woocommerceOrder->payment_method != '') {
				$paymentConditions = $this->ci->Paymentcondition_model->getAll($businessId)->result();
				$paymentConditionExists = false;
				// TODO: Maybe search by name in a model?
				foreach ($paymentConditions as $paymentCondition) {
					if ($paymentCondition->Name == $woocommerceOrder->payment_method_title) {
						$paymentConditionExists = true;
						break;
					}
				}
				if ($paymentConditionExists == false) {
					$dataPaymentCondition = array(
						'Name' => $woocommerceOrder->payment_method_title,
						'BusinessId' => $businessId
					);
					$this->ci->Paymentcondition_model->createPaymentCondition($dataPaymentCondition);
				}
			}
			
			$address = split_street($woocommerceOrder->billing->address_1);
			
			// Check whether the Woocommerce order must be imported as an order, invoice or quotation.
			switch ($this->webshop->OrderFormat) {
				case 'order':
					$a = array();
					
					$orderNumber 	= 'VKO' . $businessId . sprintf('%05d', getBusinessSalesOrderNumber($businessId) + 1);
					$totalEx 		= 0;
					$totalIn 		= 0;
					$totalTax21 	= 0;
					$totalExTax21 	= 0;
					$totalTax6 		= 0;
					$totalExTax6 	= 0;
					$totalExTax0 	= 0;
					$totalIn21 		= 0;
					$totalIn6 		= 0;
					
					foreach ($woocommerceOrder->line_items as $orderItem) {
						// Check if the webshop product id is present in iziAccount.
						$product = $this->ci->Product_model->getProductByWebshopId($orderItem->product_id)->row();
						
						// If the product is not present in iziAccount or the the order is not a webshop porduct, no orderrule will be created.
						if ($product != NULL && $product->isShop == 1) {
							
							$total = $orderItem->quantity * $orderItem->total;
							
							switch ($product->BTW) {
								case 21:
									$totalTax21 	= $totalTax21 + ($total * (21 / 100));
									$totalEx 		= $totalEx + $total;
									$totalExTax21 	= $totalExTax21 + $total;
									$totalIn 		= $totalIn + ($total * 1.21);
									$totalIn21 		= $totalIn21 + ($total * 1.21);
									break;
								case 9:
									$totalTax6 		= $totalTax6 + ($total * (9 / 100));
									$totalEx 		= $totalEx + $total;
									$totalExTax6 	= $totalExTax6 + $total;
									$totalIn 		= $totalIn + ($total * 1.09);
									$totalIn6 		= $totalIn6 + ($total * 1.09);
									break;
								default :
									$totalEx 		= $totalEx + $total;
									$totalExTax0 	= $totalExTax0 + $total;
									$totalIn 		= $totalIn + $total;
									break;
							}
							
							$dataRule = array(
								'OrderNumber' 		=> $orderNumber,
								'ArticleC' 			=> $product->ArticleNumber,
								'EanCode' 			=> $product->EanCode,
								'Amount' 			=> $orderItem->quantity,
								'Description' 		=> $product->Description,
								'Price' 			=> $total,
								'Discount' 			=> '0',
								'Tax' 				=> $product->BTW,
								'Total' 			=> $total,
								'CustomerId' 		=> NULL,
								'BusinessId' 		=> $businessId
							);
							
							// Add meta data from the order.
							if (isset($orderItem->meta_data)) {
								$metaData = array();
								foreach ($orderItem->meta_data as $meta_data) {
									$metaData[] = array(
										'key' => $meta_data->key,
										'value' => $meta_data->value
									);
								}
								$dataRule['MetaData'] = serialize($metaData);
							}
							
							$salesOrderRuleId = $this->ci->SalesOrder_model->insertSalesOrderRule($dataRule);
							$a[] = $salesOrderRuleId;
						}
					}
					
					$lastnameSplitted = splitLastname($woocommerceOrder->billing->last_name);
					
					$dataSalesOrder = array(
						'OrderNumber' 		=> $orderNumber,
						'TotalEx' 			=> $totalEx,
						'TotalIn' 			=> $totalIn,
						'TotalTax21' 		=> $totalTax21,
						'TotalExTax21' 		=> $totalExTax21,
						'TotalTax6' 		=> $totalTax6,
						'TotalExTax6' 		=> $totalExTax6,
						'TotalExTax0' 		=> $totalExTax0,
						'TotalIn21' 		=> $totalIn21,
						'TotalIn6' 			=> $totalIn6,
						'OrderDate' 		=> date('Y-m-d', strtotime($woocommerceOrder->date_created)),
						'Note' 				=> nl2br($woocommerceOrder->customer_note),
						'CustomerId' 		=> NULL,
						'CompanyName' 		=> $woocommerceOrder->billing->company 		!= NULL ? $woocommerceOrder->billing->company 		: NULL,
						'FrontName' 		=> $woocommerceOrder->billing->first_name 	!= NULL ? $woocommerceOrder->billing->first_name	: NULL,
						'Insertion'			=> $lastnameSplitted[0],
						'LastName' 			=> $lastnameSplitted[1],
						'Address' 			=> $address['street'] ?? null,
						'AddressNumber' 	=> $address['number'] ?? null,
						'AddressAddition' 	=> $address['numberAddition'] ?? null,
						'ZipCode' 			=> $woocommerceOrder->billing->postcode 	!= NULL ? $woocommerceOrder->billing->postcode 		: NULL,
						'City' 				=> $woocommerceOrder->billing->city 		!= NULL ? $woocommerceOrder->billing->city 			: NULL,
						'Country' 			=> $woocommerceOrder->billing->country 		!= NULL ? $woocommerceOrder->billing->country 		: NULL,
						'MailAddress' 		=> $woocommerceOrder->billing->email 		!= NULL ? $woocommerceOrder->billing->email 		: NULL,
						'PaymentCondition' 	=> $woocommerceOrder->payment_method 		!= NULL ? $woocommerceOrder->payment_method_title 	: NULL,
						'TermOfPayment'		=> 14,
						'Seller_id' 		=> 0,
						'Transport_id' 		=> 0,
						'Reference' 		=> NULL,
						'Colli' 			=> NULL,
						'BusinessId' 		=> $businessId
					);
					$salesOrderId = $this->ci->SalesOrder_model->insertSalesOrder($dataSalesOrder);
					$importIds[] = $salesOrderId;
					
					$dataB = array(
						'SalesOrderNumber' => getBusinessSalesOrderNumber($businessId) + 1
					);
					$this->ci->Business_model->updateBusiness($dataB, $businessId);
					
					foreach ($a as $SalesOrderRuleId) {
						$data = array(
							'SalesOrderId' => $salesOrderId
						);
						$this->ci->SalesOrder_model->updateSalesOrderRule($SalesOrderRuleId, $data);
					}
					
					// Import custom fields.
					foreach ($woocommerceOrder->meta_data as $woocommerceMetaData) {
						if (substr($woocommerceMetaData->key, 0, 4) != 'izi_') {
							continue;
						}
						
						$dataC = array(
							'SalesOrderId' => $salesOrderId,
							'Key' => $woocommerceMetaData->key,
							'Value' => $woocommerceMetaData->value,
							'BusinessId' => $businessId
						);
						$this->ci->SalesOrder_model->createCustomField($dataC);
					}
					
					break;
				case 'invoice':
					
					$a = array();
		
					$invoiceNumber = $businessId . sprintf('%05d', getBusinessInvoiceNumber($businessId) + 1);
					$invoiceDate = date('Y-m-d', strtotime($woocommerceOrder->date_created));
					$paymentCondition = $woocommerceOrder->payment_method != NULL ? $woocommerceOrder->payment_method_title : NULL;
					$termOfPayment = 14;
					$expirationDate = strtotime("+ " . $termOfPayment . " day", strtotime($invoiceDate));
					$totalEx = 0;
					$totalIn = 0;
					$totalTax21 = 0;
					$totalExTax21 = 0;
					$totalTax6 = 0;
					$totalExTax6 = 0;
					$totalExTax0 = 0;
					$totalIn21 = 0;
					$totalIn6 = 0;
		
					foreach ($woocommerceOrder->line_items as $orderItem) {
						// Check if the webshop product id is present in iziAccount.
						$product = $this->ci->Product_model->getProductByWebshopId($orderItem->product_id)->row();
						
						// If the product is not present in iziAccount or the the order is not a webshop porduct, no orderrule will be created.
						if ($product != NULL && $product->isShop == 1) {
							
							$total = $orderItem->quantity * $orderItem->total;
							
							$articlenumber = $product->ArticleNumber;
							$articledescription = $product->Description;
							$amount = $orderItem->quantity;
							$salesprice = $total;
							$discount = '0';
			
							$tax = $product->BTW;
			
							switch ($tax) {
								case 21:
									$totalTax21 = $totalTax21 + ($total * (21 / 100));
									$totalEx = $totalEx + $total;
									$totalExTax21 = $totalExTax21 + $total;
									$totalIn = $totalIn + ($total * 1.21);
									$totalIn21 = $totalIn21 + ($total * 1.21);
									break;
								case 9:
									$totalTax6 = $totalTax6 + ($total * (9 / 100));
									$totalEx = $totalEx + $total;
									$totalExTax6 = $totalExTax6 + $total;
									$totalIn = $totalIn + ($total * 1.09);
									$totalIn6 = $totalIn6 + ($total * 1.09);
									break;
								default :
									$totalEx = $totalEx + $total;
									$totalExTax0 = $totalExTax0 + $total;
									$totalIn = $totalIn + $total;
									break;
							}
			
							$dataRule = array(
								'InvoiceNumber' => $invoiceNumber,
								'ArticleC' => $articlenumber,
								'Amount' => $amount,
								'Description' => $articledescription,
								'Price' => $salesprice,
								'Discount' => $discount,
								'Tax' => $tax,
								'Total' => $total,
								'CustomerId' => null,
								'BusinessId' => $businessId
							);
							
							// Add meta data from the order.
							if (isset($orderItem->meta_data)) {
								$metaData = array();
								foreach ($orderItem->meta_data as $meta_data) {
									$metaData[] = array(
										'key' => $meta_data->key,
										'value' => $meta_data->value
									);
								}
								$dataRule['MetaData'] = serialize($metaData);
							}
							
							$invoiceRuleId = $this->ci->Invoice_model->insertInvoiceRule($dataRule);
							$a[] = $invoiceRuleId;
			
							// Decrement product stock.
							if ($product->ProductKind == 1) { // Product is stock
								$dataP = array(
									'Stock' => $product->Stock - $amount
								);
								$this->ci->Product_model->updateProduct($product->Id, $dataP);
							}
						}
					}
					
					$lastnameSplitted = splitLastname($woocommerceOrder->billing->last_name);
					
					$dataInvoice = array(
						'InvoiceNumber' 	=> $invoiceNumber,
						'TotalEx' 			=> $totalEx,
						'TotalIn' 			=> $totalIn,
						'TotalTax21' 		=> $totalTax21,
						'TotalExTax21' 		=> $totalExTax21,
						'TotalTax6' 		=> $totalTax6,
						'TotalExTax6' 		=> $totalExTax6,
						'TotalExTax0' 		=> $totalExTax0,
						'TotalIn21' 		=> $totalIn21,
						'TotalIn6' 			=> $totalIn6,
						'InvoiceDate' 		=> strtotime($invoiceDate),
						'ExpirationDate' 	=> $expirationDate,
						// 'ShortDescription' 	=> $woocommerceOrder->customer_note,
						// 'Description' 	=> 'Woocommerce order '.$woocommerceOrder->number,
						'Description' 		=> nl2br($woocommerceOrder->customer_note),
						'PaymentCondition' 	=> $paymentCondition,
						'TermOfPayment' 	=> $termOfPayment,
						'contact' 			=> null,
						'CustomerId' 		=> null,
						'CompanyName' 		=> $woocommerceOrder->billing->company 		!= NULL ? $woocommerceOrder->billing->company 		: NULL,
						'FrontName' 		=> $woocommerceOrder->billing->first_name 	!= NULL ? $woocommerceOrder->billing->first_name	: NULL,
						'Insertion'			=> $lastnameSplitted[0],
						'LastName' 			=> $lastnameSplitted[1],
						'Address' 			=> $address['street'] ?? null,
						'AddressNumber' 	=> $address['number'] ?? null,
						'AddressAddition' 	=> $address['numberAddition'] ?? null,
						'ZipCode' 			=> $woocommerceOrder->billing->postcode 	!= NULL ? $woocommerceOrder->billing->postcode 		: NULL,
						'City' 				=> $woocommerceOrder->billing->city 		!= NULL ? $woocommerceOrder->billing->city 			: NULL,
						'Country' 			=> $woocommerceOrder->billing->country 		!= NULL ? $woocommerceOrder->billing->country 		: NULL,
						'MailAddress' 		=> $woocommerceOrder->billing->email 		!= NULL ? $woocommerceOrder->billing->email 		: NULL,
						'SentPerMail'		=> 0,		
						'BusinessId' 		=> $businessId
					);
					$invoiceId = $this->ci->Invoice_model->insertInvoice($dataInvoice);
					$importIds[] = $invoiceId;
		
					$dataB = array(
						'InvoiceNumber' => getBusinessInvoiceNumber($businessId) + 1
					);
					$this->ci->Invoice_model->updateInvoiceNumber($businessId, $dataB);
		
					foreach ($a as $ruleId) {
						$data = array(
							'InvoiceId' => $invoiceId
						);
						$this->ci->Invoice_model->updateInvoiceRule($ruleId, $data);
					}
					
					// Import custom fields.
					foreach ($woocommerceOrder->meta_data as $woocommerceMetaData) {
						if (substr($woocommerceMetaData->key, 0, 4) != 'izi_') {
							continue;
						}
						
						$dataC = array(
							'InvoiceId' => $invoiceId,
							'Key' => $woocommerceMetaData->key,
							'Value' => $woocommerceMetaData->value,
							'BusinessId' => $businessId
						);
						$this->ci->Invoice_model->createCustomField($dataC);
					}
					
					break;
				case 'quotation':
					$reasonDescription = 'Uw aanvraag';
					$paymentConditionName = 'Vooruit betalen';
					
					// Create paymentcondition if not exist.
					$paymentCondition = $this->ci->Paymentcondition_model->searchPaymentConditionByName($paymentConditionName, $businessId)->row();
					if ($paymentCondition == null) {
						$dataPC = array(
							'Name' => $paymentConditionName,
							'BusinessId' => $businessId
						);
						$paymentConditionId = $this->ci->Paymentcondition_model->createPaymentCondition($dataPC);
					}
					else {
						$paymentConditionId = $paymentCondition->Id;
					}
					
					// Get fresh business data because the quotation number is incremented in this loop.
					$business = $this->ci->Business_model->getBusiness($businessId)->row();
					
					// Increment new quotation number by 1.
					$quotationNumber = $business->QuotationNumber + 1;
					$quotationCode = date("Y").'-'.sprintf('%04d', $quotationNumber);
					
					$lastnameSplitted = splitLastname($woocommerceOrder->billing->last_name);
					
					$data = array(
						'CreatorName' 					=> 'Webshop',
						'CustomerId' 					=> 0,
						'CustomerName' 					=> $woocommerceOrder->billing->company,
						'CustomerStreet' 				=> $address['street'] ?? '',
						'CustomerHousenumber' 			=> $address['number'] ?? '',
						'CustomerHousenumberAddition' 	=> $address['numberAddition'] ?? '',
						'CustomerZipCode' 				=> $woocommerceOrder->billing->postcode,
						'CustomerCity' 					=> $woocommerceOrder->billing->city,
						'CustomerCountry' 				=> $woocommerceOrder->billing->country,
						'CustomerMailaddress'			=> $woocommerceOrder->billing->email,
						'CreatedDate' 					=> date('Y-m-d'),
						'ContactId' 					=> null,
						'ContactFirstName' 				=> $woocommerceOrder->billing->first_name,
						'ContactInsertion' 				=> $lastnameSplitted[0],
						'ContactLastName' 				=> $lastnameSplitted[1],
						'ContactSalutation' 			=> 'informal',
						'QuotationNumber' 				=> 'O'.$quotationCode,
						'Subject' 						=> 'Aanvraag via website',
						'Reason' 						=> 'Uw Aanvraag',
						'ContactDate' 					=> date('Y-m-d', strtotime($woocommerceOrder->date_created)),
						'ProductDescription'			=> nl2br($woocommerceOrder->customer_note),
						'ValidDays' 					=> 14,
						'PaymentConditionId' 			=> $paymentConditionId,
						'IsComparison' 					=> 0,
						'Status' 						=> DEFAULTQUOTATIONCREATED,
						'BusinessId' 					=> $businessId
					);
					if ($business->DirectoryPrefix == 'commpro') {
						$data['Template'] = 'commpro'; // Default layout for Commpro.
					}
					$quotationId = $this->ci->Quotation_model->create($data);
					$importIds[] = $quotationId;
					
					// Save data for products.
					foreach ($woocommerceOrder->line_items as $orderItem) {
						// Check if the webshop product id is present in iziAccount.
						$product = $this->ci->Product_model->getProductByWebshopId($orderItem->product_id)->row();
						
						// If the product is not present in iziAccount or the the order is not a webshop porduct, no orderrule will be created.
						if ($product != NULL && $product->isShop == 1) {
							
							$total = $orderItem->quantity * $orderItem->total;
						
							$dataR = array(
								'BusinessId' => $businessId,
								'QuotationId' => $quotationId,
								'ArticleC' => $product->ArticleNumber,
								'EanCode' => $product->EanCode,
								'ArticleDescription' => $product->Description,
								'Amount' => $orderItem->quantity,
								'SalesPrice' => $total,
								'Tax' => $product->BTW,
								'Type' => 1 // = Product.
							);
							
							// Add meta data from the order.
							if (isset($orderItem->meta_data)) {
								$metaData = array();
								foreach ($orderItem->meta_data as $meta_data) {
									$metaData[] = array(
										'key' => $meta_data->key,
										'value' => $meta_data->value
									);
								}
								$dataR['MetaData'] = serialize($metaData);
							}
							
							$this->ci->Quotation_model->createRule($dataR);
						}
					}
					
					// Save new quotation number.
					$dataB = array(
						'QuotationNumber' => $quotationNumber
					);
					$this->ci->Business_model->updateBusiness($dataB, $businessId);
					
					// Import custom fields.
					foreach ($woocommerceOrder->meta_data as $woocommerceMetaData) {
						if (substr($woocommerceMetaData->key, 0, 4) != 'izi_') {
							continue;
						}
						
						$dataC = array(
							'QuotationId' => $quotationId,
							'Key' => $woocommerceMetaData->key,
							'Value' => $woocommerceMetaData->value,
							'BusinessId' => $businessId
						);
						$this->ci->Quotation_model->createCustomField($dataC);
					}
					
					break;
			}
		}
		
		// Log this import.
		$dataW = array(
			'WebshopId' => $this->webshop->Id
		);
		$this->ci->WoocommerceOrderImport_model->create($dataW);
		
		$this->ci->db->trans_complete();
		
		return $importIds;
	}
	
}
