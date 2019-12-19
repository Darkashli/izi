<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_woocommerce_custom_fields extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_field(array(
			'Id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'SalesOrderId' => array(
				'type' => 'INT'
			),
			'Key' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
			),
			'Value' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'BusinessId' => array(
				'type' => 'INT'
			)
		));
		$this->dbforge->add_key('Id');
		$this->dbforge->create_table('SalesOrderCustomField', true);
		
		$this->dbforge->add_field(array(
			'Id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'InvoiceId' => array(
				'type' => 'INT'
			),
			'Key' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
			),
			'Value' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'BusinessId' => array(
				'type' => 'INT'
			)
		));
		$this->dbforge->add_key('Id');
		$this->dbforge->create_table('InvoiceCustomField', true);
		
		$this->dbforge->add_field(array(
			'Id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'QuotationId' => array(
				'type' => 'INT'
			),
			'Key' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
			),
			'Value' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'BusinessId' => array(
				'type' => 'INT'
			)
		));
		$this->dbforge->add_key('Id');
		$this->dbforge->create_table('QuotationCustomField', true);
	}
	
	public function down()
	{
		$this->dbforge->drop_table('SalesOrderCustomField', true);
		$this->dbforge->drop_table('InvoiceCustomField', true);
		$this->dbforge->drop_table('QuotationCustomField', true);
	}
}
