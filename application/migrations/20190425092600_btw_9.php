<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Btw_9 extends CI_Migration {
	
	public function up()
	{
		// Update product tax to 9%.
		
		$this->db->where('BTW', '6');
		$products = $this->db->get('Product')->result();
		
		$this->dbforge->modify_column('Product', array(
			'BTW' => array(
				'type' => "ENUM('21', '9', '0')"
			)
		));
		
		$data = array('BTW' => '9');
		
		foreach ($products as $product) {
			$this->db->where('Id', $product->Id);
			$this->db->update('Product', $data);
		}
		
		// Update repeating invoicerules tax to 9%.
		
		$repeatingInvoices = $this->db->get('RepeatingInvoice')->result();
		foreach ($repeatingInvoices as $repeatingInvoice) {
			$invoiceRules = unserialize($repeatingInvoice->InvoiceRules);
			foreach ($invoiceRules as $key => $invoiceRule) {
				if ($invoiceRule->Tax == 6) {
					$invoiceRules[$key]->Tax = '9';
				}
			}
			$data = array('InvoiceRules' => serialize($invoiceRules));
			$this->db->where('Id', $repeatingInvoice->Id);
			$this->db->update('RepeatingInvoice', $data);
		}
	}
	
	public function down()
	{
		// Bring product tax back to 6%.
		
		$this->db->where('BTW', '9');
		$products = $this->db->get('Product')->result();
		
		$this->dbforge->modify_column('Product', array(
			'BTW' => array(
				'type' => "ENUM('21', '6', '0')"
			)
		));
		
		$data = array('BTW' => '6');
		
		foreach ($products as $product) {
			$this->db->where('Id', $product->Id);
			$this->db->update('Product', $data);
		}
		
		// Update repeating invoicerules tax back to 6%.
		
		$repeatingInvoices = $this->db->get('RepeatingInvoice')->result();
		foreach ($repeatingInvoices as $repeatingInvoice) {
			$invoiceRules = unserialize($repeatingInvoice->InvoiceRules);
			foreach ($invoiceRules as $key => $invoiceRule) {
				if ($invoiceRule->Tax == 9) {
					$invoiceRules[$key]->Tax = '6';
				}
			}
			$data = array('InvoiceRules' => serialize($invoiceRules));
			$this->db->where('Id', $repeatingInvoice->Id);
			$this->db->update('RepeatingInvoice', $data);
		}
	}
}
