<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_last_name extends CI_Migration {
	
	public function __construct()
	{
		$this->load->helper('name');
	}
	
	public function up()
	{
		$invoices 			= $this->db->get('Invoice')->result();
		$invoicesSupplier 	= $this->db->get('InvoiceSupplier')->result();
		$salesOrders 		= $this->db->get('SalesOrders')->result();
		$purchaseOrders 	= $this->db->get('PurchaseOrders')->result();
		
		$this->dbforge->add_column('Invoice', array(
			'Insertion' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'FrontName'
			)
		));
		$this->dbforge->add_column('InvoiceSupplier', array(
			'Insertion' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'FrontName'
			)
		));
		$this->dbforge->add_column('SalesOrders', array(
			'Insertion' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'FrontName'
			)
		));
		$this->dbforge->add_column('PurchaseOrders', array(
			'Insertion' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'FrontName'
			)
		));
		
		foreach ($invoices as $invoice) {
			$lastname = splitLastname($invoice->LastName);
			$dataI = array(
				'Insertion' => $lastname[0],
				'LastName' => $lastname[1]
			);
			$this->db->where('Id', $invoice->Id);
			$this->db->update('Invoice', $dataI);
		}
		
		foreach ($invoicesSupplier as $invoice) {
			$lastname = splitLastname($invoice->LastName);
			$dataIs = array(
				'Insertion' => $lastname[0],
				'LastName' => $lastname[1]
			);
			$this->db->where('Id', $invoice->Id);
			$this->db->update('InvoiceSupplier', $dataIs);
		}
		
		foreach ($salesOrders as $order) {
			$lastname = splitLastname($order->LastName);
			$dataS = array(
				'Insertion' => $lastname[0],
				'LastName' => $lastname[1]
			);
			$this->db->where('Id', $order->Id);
			$this->db->update('SalesOrders', $dataS);
		}
		
		foreach ($purchaseOrders as $order) {
			$lastname = splitLastname($order->LastName);
			$dataP = array(
				'Insertion' => $lastname[0],
				'LastName' => $lastname[1]
			);
			$this->db->where('Id', $order->Id);
			$this->db->update('PurchaseOrders', $dataP);
		}
	}
	
	public function down()
	{
		$invoices 			= $this->db->get('Invoice')->result();
		$invoicesSupplier 	= $this->db->get('InvoiceSupplier')->result();
		$salesOrders 		= $this->db->get('SalesOrders')->result();
		$purchaseOrders 	= $this->db->get('PurchaseOrders')->result();
		
		$this->dbforge->drop_column('Invoice', 'Insertion');
		$this->dbforge->drop_column('InvoiceSupplier', 'Insertion');
		$this->dbforge->drop_column('SalesOrders', 'Insertion');
		$this->dbforge->drop_column('PurchaseOrders', 'Insertion');
		
		foreach ($invoices as $invoice) {
			$lastname = trim($invoice->Insertion.' '.$invoice->LastName);
			$dataI = array(
				'LastName' => $lastname
			);
			$this->db->where('Id', $invoice->Id);
			$this->db->update('Invoice', $dataI);
		}
		
		foreach ($invoicesSupplier as $invoice) {
			$lastname = trim($invoice->Insertion.' '.$invoice->LastName);
			$dataIs = array(
				'LastName' => $lastname
			);
			$this->db->where('Id', $invoice->Id);
			$this->db->update('InvoiceSupplier', $dataIs);
		}
		
		foreach ($salesOrders as $order) {
			$lastname = trim($order->Insertion.' '.$order->LastName);
			$dataS = array(
				'LastName' => $lastname
			);
			$this->db->where('Id', $order->Id);
			$this->db->update('SalesOrders', $dataS);
		}
		
		foreach ($purchaseOrders as $order) {
			$lastname = trim($order->Insertion.' '.$order->LastName);
			$dataP = array(
				'LastName' => $lastname
			);
			$this->db->where('Id', $order->Id);
			$this->db->update('PurchaseOrders', $dataP);
		}
		
	}
}
