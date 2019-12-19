<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Webshop extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('webshop');
		$this->load->helper('salesorder');
		$this->load->helper('invoice');
		$this->load->helper('quotation');
		$this->load->helper('productgroup');
		$this->load->model('customers/Customers_invoicesmodel', '', TRUE);
		$this->load->model('quotations/Quotation_model', '', TRUE);
		$this->load->model('salesorders/SalesOrder_model', '', TRUE);
		$this->load->model('product/Product_model', '', TRUE);
	}

	public function syncOrders() {
		if (!isLogged()) {
			redirect('login');
		}
		
		if (!hasWebshop()) {
			$this->session->set_tempdata('err_message', 'Er is geen actieve webshop', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}
		
		$this->load->library('Woocommerce');
		
		$importIds = $this->woocommerce->syncOrdersToIzi();
		arsort($importIds); // Sort the id's in descent order.
		$businessId = $this->session->userdata('user')->BusinessId;
		$webshop = $this->Webshop_model->getWebshop($businessId)->row();
		
		if ($webshop->OrderFormat == null) {
			$this->session->set_tempdata('err_message', 'Izi account is niet ingesteld om webshop bestellingen op te halen', 300);
			$this->session->set_tempdata('err_messagetype', 'warning', 300);
			redirect('dashboard');
		}
		
		if (!empty($salesOrderIds)) {
			$data['tpl_msg'] = 'De bestellingen zijn succesvol geïmporteerd';
			$data['tpl_msgtype'] = 'success';
		}
		
		switch ($webshop->OrderFormat) {
			case 'order':
				$data['orders'] = array();
				
				foreach ($importIds as $id) {
					$data['orders'][] = $this->SalesOrder_model->getSalesOrder($id, $businessId)->row();
				}
				
				$this->load->view('webshop/salesorders', $data);
				break;
			case 'invoice':
				$data['invoices'] = array();
				
				foreach ($importIds as $id) {
					$data['invoices'][] = $this->Customers_invoicesmodel->getInvoice($id, $businessId)->row();
				}
				
				$this->load->view('webshop/invoices', $data);
				break;
			case 'quotation':
				$data['quotations'] = array();
				
				foreach ($importIds as $id) {
					$data['quotations'][] = $this->Quotation_model->getQuotation($id, $businessId)->row();
				}
				
				$this->load->view('webshop/quotations', $data);
				break;
		}
	}
	
	public function syncProducts()
	{
		if (!isLogged()) {
			redirect('login');
		}
		
		if (!hasWebshop()) {
			$this->session->set_tempdata('err_message', 'Er is geen actieve webshop', 300);
			$this->session->set_tempdata('err_messagetype', 'danger', 300);
			redirect('dashboard');
		}
		
		$this->load->library('Woocommerce');
		
		$businessId = $this->session->userdata('user')->BusinessId;
		$products = $this->Product_model->getAll($businessId)->result();
		
		$woocommerceids = array();
		$data['syncedProducts'] = array();
		
		foreach ($products as $product) {
			// Skip products that are inactive or not a webshop product.
			if (!$product->Active || !$product->isShop) {
				continue;
			}
			
			$this->woocommerce->saveProduct($product->Id);
			$woocommerceids[] = $product->shopId;
			$data['syncedProducts'][] = $product;
		}
		
		$data['woocommerceProducts'] = $this->woocommerce->getAllProductsExclude($woocommerceids);
		
		$this->load->view('webshop/syncProducts', $data);
	}
	
	public function ajax_removeProduct()
	{
		if (!isLogged()) {
			$data = array(
				'msg' => 'Je inlogsessie is verlopen. Log A.U.B. opnieuw in.',
				'msgtype' => 'danger'
			);
			echo json_encode($data);
			return;
		}
		
		if (!hasWebshop()) {
			$data = array(
				'msg' => 'Er is geen actieve webshop',
				'msgtype' => 'danger'
			);
			echo json_encode($data);
			return;
		}
		
		$this->load->library('Woocommerce');
		
		$wProductId = $this->uri->segment(3);
		
		if (!$this->woocommerce->deleteProduct($wProductId)) {
			$data = array(
				'msg' => 'Het webshop product is niet verwijderd',
				'msgtype' => 'danger'
			);
			echo json_encode($data);
			return;
		}
		else {
			$data = array(
				'msg' => 'Het webshop product is succesvol verwijderd',
				'msgtype' => 'success'
			);
			echo json_encode($data);
			return;
		}
	}

}
