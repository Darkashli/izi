<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Up_and_crossells extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column('Product', array(
			'Upsells' => array(
				'type' => 'MEDIUMBLOB',
				'null' => true
			),
			'Crossells' => array(
				'type' => 'MEDIUMBLOB',
				'null' => true
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column('Product', 'Upsells');
		$this->dbforge->drop_column('Product', 'Crossells');
	}
}
