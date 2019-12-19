<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_change_date_field extends CI_Migration {
	
	public function up()
	{
		
		// Store all orders into a variable.
		$salesOrders = $this->db->get('SalesOrders')->result();
		$purchaseOrders = $this->db->get('PurchaseOrders')->result();
		
		// Modify column.
		$this->dbforge->drop_column('SalesOrders', 'OrderDate');
		$this->dbforge->add_column('SalesOrders', array(
			"OrderDate" => array(
				'type' => 'DATE',
				'after' => 'WorkOrder'
			)
		));
		$this->dbforge->drop_column('PurchaseOrders', 'OrderDate');
		$this->dbforge->add_column('PurchaseOrders', array(
			"OrderDate" => array(
				'type' => 'DATE',
				'after' => 'WorkOrder'
			)
		));
		
		// Loop all orders and convert the Unix timestamp to MYSQL TIMESTAMP.
		foreach ($salesOrders as $salesOrder) {
			$dataS = array(
				'OrderDate' => date('Y-m-d', $salesOrder->OrderDate)
			);
			$this->db->update('SalesOrders', $dataS, "Id = $salesOrder->Id");
		}
		foreach ($purchaseOrders as $purchaseOrder) {
			$dataP = array(
				'OrderDate' => date('Y-m-d', $purchaseOrder->OrderDate)
			);
			$this->db->update('PurchaseOrders', $dataP, "Id = $purchaseOrder->Id");
		}
		
	}
	
	public function down()
	{
		// Store all orders into a variable.
		$salesOrders = $this->db->get('SalesOrders')->result();
		$purchaseOrders = $this->db->get('PurchaseOrders')->result();
		
		// Modify column.
		$this->dbforge->drop_column('SalesOrders', 'OrderDate');
		$this->dbforge->add_column('SalesOrders', array(
			"OrderDate" => array(
				'type' => 'INT',
				'constraint' => 11,
				'after' => 'WorkOrder'
			)
		));
		$this->dbforge->drop_column('PurchaseOrders', 'OrderDate');
		$this->dbforge->add_column('PurchaseOrders', array(
			"OrderDate" => array(
				'type' => 'INT',
				'constraint' => 11,
				'after' => 'WorkOrder'
			)
		));
		
		// Loop all orders and convert the timestamp back to an integer.
		foreach ($salesOrders as $salesOrder) {
			$data = array(
				'OrderDate' => strtotime($salesOrder->OrderDate)
			);
			$this->db->update('SalesOrders', $data, "Id = $salesOrder->Id");
		}
		foreach ($purchaseOrders as $purchaseOrder) {
			$data = array(
				'OrderDate' => strtotime($purchaseOrder->OrderDate)
			);
			$this->db->update('PurchaseOrders', $data, "Id = $purchaseOrder->Id");
		}
	}
}
