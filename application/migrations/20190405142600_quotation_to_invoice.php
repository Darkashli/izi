<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_quotation_to_invoice extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column("Quotation", array(
			"WorkArticleC" => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'WorkAmount'
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column("Quotation", "WorkArticleC");
	}
}
