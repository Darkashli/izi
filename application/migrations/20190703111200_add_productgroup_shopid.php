<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_productgroup_shopid extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column('Productgroup', array(
			'ParentId' => array(
				'type' => 'INT',
				'null' => true,
				'after' => 'Image'
			),
			'IsShop' => array(
				'type' => 'BOOL',
				'default' => '0',
				'after' => 'ParentId'
			),
			'ShopId' => array(
				'type' => 'INT',
				'null' => true,
				'after' => 'IsShop'
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column('Productgroup', 'ParentId');
		$this->dbforge->drop_column('Productgroup', 'IsShop');
		$this->dbforge->drop_column('Productgroup', 'ShopId');
	}
}
