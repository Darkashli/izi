<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Remove_creatorid_from_quotation extends CI_Migration {
	
	public function up()
	{
		// Drop column CreatorId
		$this->dbforge->drop_column('Quotation', 'CreatorId');
		
		// Move BusinessId to the last place.
		$this->dbforge->modify_column('Quotation', array(
			'BusinessId' => array(
				'type' => 'INT', // Required but should not be changed.
				'after' => 'Template'
			)
		));
	}
	
	public function down()
	{
		// Move BusinessId back to its old place.
		$this->dbforge->modify_column('Quotation', array(
			'BusinessId' => array(
				'type' => 'INT', // Required but should not be changed.
				'after' => 'Id'
			)
		));
		
		// Recreate column CreatorId.
		$this->dbforge->add_column('Quotation', array(
			'CreatorId' => array(
				'type' => 'INT',
				'null' => true,
				'after' => 'BusinessId'
			)
		));
	}
}
