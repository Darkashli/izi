<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_fix_column_name extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->modify_column("PurchaseOrderRules", array(
			"PurchaseOrderRules" => array(
				'name' => 'PurchaseOrderId',
				'type' => 'INT',
				'constraint' => 11
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->modify_column("PurchaseOrderRules", array(
			"PurchaseOrderId" => array(
				'name' => 'PurchaseOrderRules',
				'type' => 'INT',
				'constraint' => 11
			)
		));
	}
}
