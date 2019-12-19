<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('warehouse');
		$this->load->helper('productgroup');
		$this->load->helper('Ticket');
		$this->load->helper('cookie');
		$this->load->helper('account');
		$this->load->helper('invoice');
		$this->load->helper('product');
		$this->load->helper('webshop');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('woocommerce');
		$this->load->model('product/Product_model', '', TRUE);
		$this->load->model('product/ProductImage_model', '', TRUE);
		$this->load->model('webshop/Webshop_model', '', TRUE);
		$this->load->model('customers/Customers_priceagreementmodel', '', TRUE);
		$this->load->model('productgroup/Productgroup_model', '', TRUE);
		$this->load->model('warehouse/Warehouse_model', '', TRUE);
		$this->load->model('salesorders/SalesOrder_model', '', TRUE);
		$this->load->model('invoices/Invoice_model', '', TRUE);
		$this->load->model('business/Business_model', '', TRUE);
		$this->load->model('supplier/Supplier_model', '', TRUE);
	}

	public function index()
	{
		if ( ! isLogged())
		{
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$products = $this->Product_model->getAll($this->session->userdata('user')->BusinessId)->result();

		// TODO: Make a helper function for this calculation.
		foreach ($products as $product)
		{
			$salesOrderRules = $this->SalesOrder_model->getSalesRulesByProductArticleNum($product->ArticleNumber, $businessId)->result();
			$countBackOrder[$product->Id] = 0;
			if ($product->ProductKind == 1)
			{
				foreach ($salesOrderRules as $salesOrderRule)
				{
					$salesOrder = $this->SalesOrder_model->getSalesOrder($salesOrderRule->SalesOrderId, $businessId)->row();
					if ( ! empty($salesOrder) && $salesOrder->Invoiced != 1)
					{
						$countBackOrder[$product->Id] += $salesOrderRule->Amount;
					}
				}
			}
		}

		if ($this->session->tempdata('err_message'))
		{
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}
		$data['products'] = $products;
		$data['countBackOrder'] = @$countBackOrder;

		$this->load->view('product/default', $data);
	}

	public function create()
	{
		if ( ! isLogged())
		{
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();
		$warehouses = $this->Warehouse_model->getAll($businessId)->result();
		$productgroups = $this->Productgroup_model->getAll($businessId)->result();
		$webshop = $this->Webshop_model->getWebshop($businessId)->row();

		if ($productgroups == null)
		{
			$this->session->set_tempdata('err_message', 'Er zijn geen productgroepen aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("product");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$create_webshop_product = false;

			$data = array(
				'ArticleNumber' 				=> $_POST['articlenumber'],
				'Description'       			=> $_POST['description'],
				'SupplierId'    				=> $_POST['supplier'],
				'EanCode'       				=> $_POST['ean_code'],
				'PurchasePrice' 				=> $_POST['purchaseprice'],
				'SalesPrice'    				=> $_POST['salesprice'],
				'BTW'               			=> $_POST['btw'],
				'Vvp'           				=> $_POST['vvp'],
				'ProductGroup'  				=> $_POST['productgroup'],
				'Active'        				=> isset($_POST['active']) ? 1 : 0,
				'ProductKind' 					=> $_POST['product_kind'],
				'Type' 							=> $_POST['type'],
				'Stock' 						=> isset($_POST['stock']) ? $_POST['stock'] : 0,
				'StockReadonly' 				=> $_POST['stock_readonly'],
				'NatureOfWork' 					=> $_POST['natureofwork'],
				'WarehouseLocation' 			=> $_POST['warehouselocation'],
				'Warehouse' 					=> $_POST['warehouse'],
				'BulkLocation' 					=> $_POST['bulklocation'],
				'UserId' 						=> isset($_POST['userid']) ? $_POST['userid'] : 0,
				'BusinessId' 					=> $businessId,
				'isShop' 						=> isset($_POST['is_shop']) ? 1 : 0,
				'LongDescription'				=> $_POST['long_description']
			);
			if (hasWebshop() && isset($_POST['is_shop'])) {
				$data['Woocommerce_Description'] 	= $_POST['woocommerce_description'];
				$data['SoldIndividually'] 			= isset($_POST['sold_individually']) ? 1 : 0;
				$data['WoocommerceInSale'] 			= isset($_POST['woocommerce_in_sale']) ? 1 : 0;
				$data['SalePrice'] 					= isset($_POST['woocommerce_in_sale']) ? $_POST['sale_price'] : NULL;
				$data['SalePriceDatesFrom'] 		= (isset($_POST['woocommerce_in_sale']) && $_POST['sale_price_dates_from'] != '') ? $_POST['sale_price_dates_from'] : NULL;
				$data['SalePriceDatesTo'] 			= (isset($_POST['woocommerce_in_sale']) && $_POST['sale_price_dates_to'] != '') ? $_POST['sale_price_dates_to'] : NULL;
				$data['Weight'] 					= $_POST['weight'];
				$data['Height'] 					= $_POST['height'];
				$data['Length'] 					= $_POST['length'];
				$data['Width'] 						= $_POST['width'];
				$data['Upsells']					= isset($_POST['u_articleid']) ? serialize(array_unique($_POST['u_articleid'])) : null;
				$data['Crossells']					= isset($_POST['c_articleid']) ? serialize(array_unique($_POST['c_articleid'])) : null;
			}

			$productId = $this->Product_model->createProduct($data);
			
			if (hasWebshop() && isset($_POST['is_shop'])) {
				$pushToWoocommerce = $this->woocommerce->saveProduct($productId);
			}

			if ($webshop != NULL && $webshop->Active == 1 && isset($_POST['is_shop'])){
				if (!$pushToWoocommerce) {
					$this->session->set_tempdata('err_message', 'Het product is aangemaakt maar er is geen webshop product opgeslagen', 300);
					$this->session->set_tempdata('err_messagetype', 'warning', 300);
				}
				else{
					$this->session->set_tempdata('err_message', 'Het product is succesvol aangemaakt en het product is opgeslagen in de webshop', 300);
					$this->session->set_tempdata('err_messagetype', 'success', 300);
				}
			}
			else{
				$this->session->set_tempdata('err_message', 'Het product is succesvol aangemaakt', 300);
				$this->session->set_tempdata('err_messagetype', 'success', 300);
			}
			redirect('product');
		} else {
			$natureofWork = getNatureOfWorkDropdown($businessId);
			$users = getUserDropdown($businessId);

			$data['btwDropdown'] = unserialize(TAXDROPDOWN);
			$data['productgroupsDropdown'] = $productgroups;
			$data['natureOfWorkDropdown'] = $natureofWork;
			$data['warehousesDropdown'] = getWarehouseDropdown($warehouses);
			$data['userDropdown'] = $users;
			$data['webshop'] = $webshop;
			$data['suppliers'] = $this->Supplier_model->getAll($this->session->userdata('user')->BusinessId)->result();
			
			$product = (object) array(
				'ArticleNumber'     		=> '',
				'Description'       		=> '',
				'SupplierId'        		=> '',
				'EanCode'           		=> '',
				'PurchasePrice'     		=> '',
				'SalesPrice'        		=> '',
				'BTW'               		=> '',
				'ProductGroup'      		=> '',
				'Active'            		=> 1,
				'ProductKind'            	=> 0,
				'Type'              		=> 0,
				'Stock'             		=> '',
				'StockReadonly'     		=> 0,
				'NatureOfWork'      		=> '',
				'Vvp'               		=> '',
				'WarehouseLocation' 		=> '',
				'Warehouse'         		=> '',
				'BulkLocation'				=> '',
				'UserId'            		=> '',
				'isShop'					=> 0,
				'shopId'					=> 0,
				'BusinessId'        		=> $businessId,
				'Woocommerce_Description'   => '',
				'Image'             		=> '',
				'SoldIndividually'  		=> '',
				'WoocommerceInSale'			=> 0,
				'SalePrice'         		=> '',
				'SalePriceDatesFrom'		=> '',
				'SalePriceDatesTo'  		=> '',
				'Weight'            		=> '',
				'Height'            		=> '',
				'Length'            		=> '',
				'Width'             		=> '',
				'LongDescription'   		=> '',
				'Upsells'   				=> null,
				'Crossells'   				=> null
			);
			$data['product'] = $product;
			$this->load->view('product/edit', $data);
		}
	}

	public function edit()
	{
		if ( ! isLogged())
		{
			redirect('login');
		}

		if ($this->session->tempdata('err_message'))
		{
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$productId = $this->uri->segment(3);
		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();
		$warehouses = $this->Warehouse_model->getAll($businessId)->result();
		$productgroups = $this->Productgroup_model->getAll($businessId)->result();
		$webshop = $this->Webshop_model->getWebshop($businessId)->row();

		if ($productgroups == null)
		{
			$this->session->set_tempdata('err_message', 'Er zijn geen productgroepen aangemaakt', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("product");
		}

		$product = $this->Product_model->getProduct($productId, $businessId)->row();
		if ($product == null)
		{
			$this->session->set_tempdata('err_message', 'Dit product is niet gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("product");
		}

		$countBackOrder = 0;
		$salesOrderRules = $this->SalesOrder_model->getSalesRulesByProductArticleNum($product->ArticleNumber, $businessId)->result();
		foreach ($salesOrderRules as $salesOrderRule)
		{
			$salesOrder = $this->SalesOrder_model->getSalesOrder($salesOrderRule->SalesOrderId, $businessId)->row();
			if ($salesOrder->Invoiced != 1)
			{
				$countBackOrder += $salesOrderRule->Amount;
			}
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$update_webshop_product = false;
			$create_webshop_product = false;

			$data = array(
				'ArticleNumber' 				=> $_POST['articlenumber'],
				'Description'       			=> $_POST['description'],
				'SupplierId' 					=> $_POST['supplier'],
				'EanCode' 						=> $_POST['ean_code'],
				'PurchasePrice' 				=> $_POST['purchaseprice'],
				'SalesPrice' 					=> $_POST['salesprice'],
				'BTW'               			=> $_POST['btw'],
				'Vvp' 							=> $_POST['vvp'],
				'ProductGroup' 					=> $_POST['productgroup'],
				'Active' 						=> isset($_POST['active']) ? 1 : 0,
				'ProductKind'					=> $_POST['product_kind'],
				'Type' 							=> $_POST['type'],
				'Stock' 						=> isset($_POST['stock']) ? $_POST['stock'] : 0,
				'StockReadonly' 				=> $_POST['stock_readonly'],
				'NatureOfWork' 					=> $_POST['natureofwork'],
				'WarehouseLocation' 			=> $_POST['warehouselocation'],
				'Warehouse' 					=> $_POST['warehouse'],
				'BulkLocation' 					=> $_POST['bulklocation'],
				'UserId' 						=> isset($_POST['userid']) ? $_POST['userid'] : 0,
				'BusinessId' 					=> $businessId,
				'isShop' 						=> isset($_POST['is_shop']) ? 1 : 0,
				'LongDescription' 				=> $_POST['long_description']
			);
			
			if (hasWebshop() && isset($_POST['is_shop'])) {
				$data['Woocommerce_Description'] 	= $_POST['woocommerce_description'];
				$data['SoldIndividually'] 			= isset($_POST['sold_individually']) ? 1 : 0;
				$data['WoocommerceInSale'] 			= isset($_POST['woocommerce_in_sale']) ? 1 : 0;
				$data['SalePrice'] 					= isset($_POST['woocommerce_in_sale']) ? $_POST['sale_price'] : NULL;
				$data['SalePriceDatesFrom'] 		= (isset($_POST['woocommerce_in_sale']) && $_POST['sale_price_dates_from'] != '') ? $_POST['sale_price_dates_from'] : NULL;
				$data['SalePriceDatesTo'] 			= (isset($_POST['woocommerce_in_sale']) && $_POST['sale_price_dates_to'] != '') ? $_POST['sale_price_dates_to'] : NULL;
				$data['Weight'] 					= $_POST['weight'];
				$data['Height'] 					= $_POST['height'];
				$data['Length'] 					= $_POST['length'];
				$data['Width'] 						= $_POST['width'];
				$data['Upsells']					= isset($_POST['u_articleid']) ? serialize(array_unique($_POST['u_articleid'])) : null;
				$data['Crossells']					= isset($_POST['c_articleid']) ? serialize(array_unique($_POST['c_articleid'])) : null;
			}
			$this->Product_model->updateProduct($product->Id, $data);
			
			if (hasWebshop() && isset($_POST['is_shop'])){
				$pushToWoocommerce = $this->woocommerce->saveProduct($product->Id);
			}

			if (hasWebshop() && isset($_POST['is_shop'])){
				if (!$pushToWoocommerce) {
					$this->session->set_tempdata('err_message', 'Het product is aangepast en er is geen webshop product opgeslagen', 300);
					$this->session->set_tempdata('err_messagetype', 'warning', 300);
				}
				else{
					$this->session->set_tempdata('err_message', 'Het product is succesvol aangepast en het product is opgeslagen in de webshop', 300);
					$this->session->set_tempdata('err_messagetype', 'success', 300);
				}
			}
			else{
				$this->session->set_tempdata('err_message', 'Het product is succesvol aangepast', 300);
				$this->session->set_tempdata('err_messagetype', 'success', 300);
			}
			redirect('product');
		} else {
			$natureofWork = getNatureOfWorkDropdown($businessId);
			$users = getUserDropdown($businessId);

			$data['btwDropdown'] = unserialize(TAXDROPDOWN);
			$data['productgroupsDropdown'] = $productgroups;
			$data['natureOfWorkDropdown'] = $natureofWork;
			$data['warehousesDropdown'] = getWarehouseDropdown($warehouses);
			$data['userDropdown'] = $users;
			$data['countBackOrder'] = $countBackOrder;
			$data['product'] = $product;
			$data['business'] = $business;
			$data['webshop'] = $webshop;
			$data['suppliers'] = $this->Supplier_model->getAll($this->session->userdata('user')->BusinessId)->result();
 
			$this->load->view('product/edit', $data);
		}
	}

	public function history()
	{
		if ( ! isLogged())
		{
			redirect('login');
		}

		if ($this->session->tempdata('err_message'))
		{
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$productId = $this->uri->segment(3);
		$product = $this->Product_model->getProduct($productId, $businessId)->row();

		if ($product == null)
		{
			$this->session->set_tempdata('err_message', 'Dit product is niet gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("product");
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['from'] = date('Y-m-d', strtotime($_POST['dateFrom']));
			$data['to'] = date('Y-m-d', strtotime($_POST['dateTo']));
			$data['invoiceFilter'] = $_POST['invoiceFilter'];
		} else {
			$data['from'] = date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))));
			$data['to'] = date('Y-m-d');
			$data['invoiceFilter'] = 'both';
		}

		$data['invoiceRules'] = $this->Invoice_model->getInvoiceRuleByArticleNumber($product->ArticleNumber, $businessId)->result();
		$data['InvoiceRulesSupplier'] = $this->Invoice_model->getInvoiceSupplierRuleByArticleNumber($product->ArticleNumber, $businessId)->result();
		$this->load->view('product/history', $data);
	}
	
	public function images()
	{
		if ( ! isLogged())
		{
			redirect('login');
		}

		if ($this->session->tempdata('err_message'))
		{
			$data['tpl_msg'] = $this->session->tempdata('err_message');
			$data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
			$this->session->unset_tempdata('err_message');
			$this->session->unset_tempdata('err_messagetype');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$business = $this->Business_model->getBusiness($businessId)->row();
		$productId = $this->uri->segment(3);
		$product = $this->Product_model->getProduct($productId, $businessId)->row();

		if ($product == null)
		{
			$this->session->set_tempdata('err_message', 'Dit product is niet gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("product");
		}
		
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			switch ($_POST['action']) {
				case 'add':
					$imgNum = 1;
					$uploadPath = "./uploads/$business->DirectoryPrefix/products/$productId/";
					if (!file_exists($uploadPath)) {
						mkdir($uploadPath, 0777, true);
					}
					$fileExploded = explode('.', $_FILES['image']['name']);
					$fileExt = end($fileExploded);
					$fileName = "$imgNum.$fileExt";
					$config['upload_path'] = $uploadPath;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['max_size'] = 2097152;
					$config['max_width'] = 7680;
					$config['max_height'] = 4320;
					$config['encrypt_name'] = true; // Random string to prevent caching problems
					
					$this->load->library('upload', $config);
					
					$this->upload->do_upload('image');
					
					$errors = $this->upload->display_errors();
					if ($errors) {
						$this->session->set_tempdata('err_message', "Er is een fout opgetreden tijdens het uploaden van de afbeelding: $errors", 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("product/images/$productId");
					}
					
					$uploadData = $this->upload->data();
					
					$highestPos = $this->ProductImage_model->getHighestPos($productId, $businessId)->row();
					if ($highestPos != null) {
						$position = $highestPos->Position;
						$position++;
					}
					else {
						$position = 0;
					}
					
					$data = array(
						'ProductId' => $productId,
						'FileName' => $uploadData['file_name'],
						'Position' => $position,
						'BusinessId' => $businessId
					);
					$this->ProductImage_model->create($data);
					
					$this->woocommerce->saveProduct($productId);
					
					$this->session->set_tempdata('err_message', 'De afbeelding is succesvol toegevoegd', 300);
					$this->session->set_tempdata('err_messagetype', 'success', 300);
					redirect("product/images/$productId");
					
					break;
				case 'remove':
					$productImage = $this->ProductImage_model->get($_POST['imageId'], $businessId)->row();
					if ($productImage == null) {
						$this->session->set_tempdata('err_message', 'Deze productafbeelding bestaat niet', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("product/images/$productId");
					}
					
					// Delete image.
					$path = "./uploads/$business->DirectoryPrefix/products/$productId/$productImage->FileName";
					@unlink($path);
					
					// Delete from database.
					$this->ProductImage_model->delete($productImage->Id);
					
					reorderImages($productId);
					
					$this->woocommerce->saveProduct($productId);
					
					$this->session->set_tempdata('err_message', 'De afbeelding is succesvol verwijderd', 300);
					$this->session->set_tempdata('err_messagetype', 'success', 300);
					redirect("product/images/$productId");
					
					break;
				case 'set_as_main':
					$productImage = $this->ProductImage_model->get($_POST['imageId'], $businessId)->row();
					if ($productImage == null) {
						$this->session->set_tempdata('err_message', 'Deze productafbeelding bestaat niet', 300);
						$this->session->set_tempdata('err_messagetype', 'danger', 300);
						redirect("product/images/$productId");
					}
					
					reorderImages($productId, $_POST['imageId']);
					
					$this->woocommerce->saveProduct($productId);
					
					break;
			}
		}
		
		$data['productImages'] = $this->ProductImage_model->getFromProduct($productId, $businessId)->result();
		$data['business'] = $business;
		
		$this->load->view('product/images', $data);
	}
	
	/* Search functions */

	public function searchS()
	{ // (From sales)
		if ( ! isLogged())
		{
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$customerId =  $this->uri->segment(4, 0);
		$products = $this->Product_model->getAllS($this->session->userdata('user')->BusinessId)->result();
		$countBackOrder = array();

		foreach ($products as $product)
		{
			$salesOrderRules = $this->SalesOrder_model->getSalesRulesByProductArticleNum($product->ArticleNumber, $businessId)->result();
			$countBackOrder[$product->Id] = 0;
			if ($product->ProductKind == 1)
			{
				foreach ($salesOrderRules as $salesOrderRule)
				{
					$salesOrder = $this->SalesOrder_model->getSalesOrder($salesOrderRule->SalesOrderId, $businessId)->row();
					if ( ! empty($salesOrder) && $salesOrder->Invoiced != 1)
					{
						$countBackOrder[$product->Id] += $salesOrderRule->Amount;
					}
				}
			}
		}

		$data['customerId'] =  $customerId;
		$data['products'] = $products;
		$data['countBackOrder'] = $countBackOrder;
		$data['inkoopverkoop'] = '1';

		$this->load->view('product/search', $data);
	}

	public function searchP()
	{ // (From purchase)
		if ( ! isLogged())
		{
			redirect('login');
		}

		$supplierId =  $this->uri->segment(4, 0);
		$businessId = $this->session->userdata('user')->BusinessId;
		$products = $this->Product_model->getAllP($supplierId, $businessId)->result();
		$countBackOrder = array();

		foreach ($products as $product)
		{
			$salesOrderRules = $this->SalesOrder_model->getSalesRulesByProductArticleNum($product->ArticleNumber, $businessId)->result();
			$countBackOrder[$product->Id] = 0;
			if ($product->ProductKind == 1)
			{
				foreach ($salesOrderRules as $salesOrderRule)
				{
					$salesOrder = $this->SalesOrder_model->getSalesOrder($salesOrderRule->SalesOrderId, $businessId)->row();
					if ( ! empty($salesOrder) && $salesOrder->Invoiced != 1)
					{
						$countBackOrder[$product->Id] += $salesOrderRule->Amount;
					}
				}
			}
		}
		$data['products'] = $products;
		$data['supplierId'] = $supplierId;
		$data['countBackOrder'] = $countBackOrder;
		$data['inkoopverkoop'] = '2';

		$this->load->view('product/search', $data);
	}

	public function deleteWoocommerceProduct()
	{
		if ( ! isLogged())
		{
			redirect('login');
		}

		$businessId = $this->session->userdata('user')->BusinessId;
		$productId = $this->uri->segment(3);
		$product = $this->Product_model->getProduct($productId, $businessId)->row();

		if ($product == NULL) {
			$this->session->set_tempdata('err_message', 'Dit product is niet gevonden', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect("product");
		}
		
		if (!$this->woocommerce->deleteProduct($product->shopId)) {
			$this->session->set_tempdata('err_message', 'Het webshop product kon niet worden verwijderd', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
		}
		else {
			$this->session->set_tempdata('err_message', 'Het webshop product is succesvol verwijderd', 300);
			$this->session->set_tempdata('err_messagetype', 'success', 300);
		}
		redirect('product');
	}
	
	/* Search functions */
	
	public function searchProducts()
	{
		if ( ! isLogged())
		{
			redirect('login');
		}
		
		$value 			= str_replace('%20', ' ', $this->uri->segment(3)) ?? '';
		$field 			= $this->uri->segment(4) ?? 'ArticleNumber'; // 'ArticleNumber', 'EanCode' or 'Description'
		$type 			= $this->uri->segment(5) ?? 2;
		$productKind 	= $this->uri->segment(6) ?? 'all';
		$supplierId		= $this->uri->segment(7) ?? 'all';
		$isWebshop		= $this->uri->segment(8) ?? 2; // 0 = no, 1 = yes, 2 = all
		
		$businessId = $this->session->userdata('user')->BusinessId;
		$data = array();
		$products = array();
		$priceagreements = array();
		
		if ($field != 'ArticleNumber' && $field != 'EanCode' && $field != 'Description') {
			$data['error'] = "$field is en ongeldig zoekveld.";
			echo json_encode($data);
			exit;
		}
		
		$products = $this->Product_model->searchProducts($businessId, $value, $field, $type, $productKind, $supplierId)->result();
		// die($this->db->last_query());
		
		foreach ($products as $key => $product) {
			
			// Count backorders.
			$countBackOrder = 0;
			$salesOrderRules = $this->SalesOrder_model->getSalesRulesByProductArticleNum($product->ArticleNumber, $businessId)->result();
			foreach ($salesOrderRules as $salesOrderRule)
			{
				$salesOrder = $this->SalesOrder_model->getSalesOrder($salesOrderRule->SalesOrderId, $businessId)->row();
				if ($salesOrder->Invoiced != 1)
				{
					$countBackOrder += $salesOrderRule->Amount;
				}
			}
			$products[$key]->CountBackOrder = $product->Stock - $countBackOrder;
		}
		
		$data['products'] = $products;
		
		echo json_encode($data);
	}
}
