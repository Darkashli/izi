<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_invoicesupplierpayments_table extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_field(array(
			'Id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'InvoiceId' => array(
				'type' => 'INT'
			),
			'Amount' => array(
				'type' => 'DECIMAL',
				'constraint' => '10, 2'
			),
			'Date' => array(
				'type' => 'INT'
			),
			'BusinessId' => array(
				'type' => 'INT'
			)
		));
		$this->dbforge->add_key('Id', true);
		$this->dbforge->create_table('InvoiceSupplierPayments', true);
	}
	
	public function down()
	{
		$this->dbforge->drop_table('InvoiceSupplierPayments', true);
	}
}
