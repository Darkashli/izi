<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_discount_to_quotation extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column('QuotationRules', array(
			'Discount' => array(
				'type' => 'DECIMAL',
				'constraint' => '11,2',
				'after' => 'SalesPrice'
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column('QuotationRules', 'Discount');
	}
}
