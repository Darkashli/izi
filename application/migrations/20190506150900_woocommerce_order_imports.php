<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Woocommerce_order_imports extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_field(array(
			'Id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'WebshopId' => array(
				'type' => 'INT',
			),
			'ImportDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
		));
		$this->dbforge->add_key('Id', true);
		$this->dbforge->create_table('WoocommerceOrderImports', true);
	}
	
	public function down()
	{
		$this->dbforge->drop_table('WoocommerceOrderImports', true);
	}
}
