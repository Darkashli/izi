<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_order_meta_data extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column('InvoiceRules', array(
			'MetaData' => array(
				'type' => 'MEDIUMBLOB',
				'null' => true,
				'after' => 'Total'
			)
		));
		$this->dbforge->add_column('SalesOrderRules', array(
			'MetaData' => array(
				'type' => 'MEDIUMBLOB',
				'null' => true,
				'after' => 'Total'
			)
		));
		$this->dbforge->add_column('QuotationRules', array(
			'MetaData' => array(
				'type' => 'MEDIUMBLOB',
				'null' => true,
				'after' => 'Type'
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column('InvoiceRules', 'MetaData');
		$this->dbforge->drop_column('SalesOrderRules', 'MetaData');
		$this->dbforge->drop_column('QuotationRules', 'MetaData');
	}
}
