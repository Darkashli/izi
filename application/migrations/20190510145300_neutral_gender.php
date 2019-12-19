<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Neutral_gender extends CI_Migration {
	
	public function up()
	{
		$quotations = $this->db->get('Quotation')->result();
		
		$this->dbforge->drop_column('Quotation', 'ContactIsMale');
		
		$this->dbforge->add_column('Quotation', array(
			'ContactSex' => array(
				'type' => 'VARCHAR',
				'constraint' => 6,
				'null' => true,
				'after' => 'ContactLastName'
			)
		));
		
		foreach ($quotations as $quotation) {
			switch ($quotation->ContactIsMale) {
				case '1':
					$contactSex = 'male';
					break;
				default:
					$contactSex = 'female';
					break;
			}
			
			$data = array(
				'ContactSex' => $contactSex
			);
			$this->db->where('Id', $quotation->Id);
			$this->db->update('Quotation', $data);
		}
	}
	
	public function down()
	{
		$quotations = $this->db->get('Quotation')->result();
		
		$this->dbforge->drop_column('Quotation', 'ContactSex');
		
		$this->dbforge->add_column('Quotation', array(
			'ContactIsMale' => array(
				'type' => 'BOOL',
				'after' => 'ContactLastName'
			)
		));
		
		foreach ($quotations as $quotation) {
			switch ($quotation->ContactSex) {
				case 'female':
					$contactSex = 0;
					break;
				default:
					$contactSex = 1;
					break;
			}
			
			$data = array(
				'ContactIsMale' => $contactSex
			);
			$this->db->where('Id', $quotation->Id);
			$this->db->update('Quotation', $data);
		}
	}
}
