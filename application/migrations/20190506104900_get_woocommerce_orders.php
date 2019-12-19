<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Get_woocommerce_orders extends CI_Migration {
	
	public function up()
	{
		
		// Store quotation data into a variable.
		$quotations = $this->db->get('Quotation')->result();
		
		// Apparently i have to re-add columns that i want to be nullable.
		$this->dbforge->drop_column('Quotation', 'CustomerId');
		$this->dbforge->drop_column('Quotation', 'CustomerHousenumberAddition');
		$this->dbforge->drop_column('Quotation', 'ContactId');
		$this->dbforge->drop_column('Quotation', 'ContactInsertion');
		$this->dbforge->drop_column('Quotation', 'DeliveryTime');
		$this->dbforge->drop_column('Quotation', 'PaymentTerm');
		$this->dbforge->drop_column('Quotation', 'Creator');
		
		$this->dbforge->add_column('Quotation', array(
			'CustomerId' => array(
				'type' => 'INT',
				'null' => true,
				'after' => 'BusinessId'
			),
			'CustomerHousenumberAddition' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'CustomerHousenumber'
			),
			'CreatorId' => array(
				'type' => 'INT',
				'null' => true,
				'after' => 'BusinessId'
			),
			'CreatorName' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'CreatorId'
			),
			'ContactId' => array(
				'type' => 'INT',
				'null' => true,
				'after' => 'CreatedDate'
			),
			'ContactInsertion' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'ContactFirstName'
			),
			'DeliveryTime' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'ValidDays'
			),
			'PaymentTerm' => array(
				'type' => 'INT',
				'null' => true,
				'after' => 'PaymentConditionId'
			)
		));
		
		// Replace the user ID with the user name.
		foreach ($quotations as $quotation) {
			if ($quotation->Creator != 0) {
				$user = $this->db->get_where('User', array('Id' => $quotation->Creator))->row();
				// Remove double white spaces from string.
				$creatorName = preg_replace('/\s+/', ' ', ($user->FirstName.' '.$user->Insertion.' '.$user->LastName));
			}
			else {
				$creatorName = '';
			}
		
			$data = array(
				'CustomerId' => $quotation->CustomerId,
				'CustomerHousenumberAddition' => $quotation->CustomerHousenumberAddition,
				'CreatorId' => $quotation->Creator,
				'CreatorName' => $creatorName,
				'ContactId' => $quotation->ContactId,
				'ContactInsertion' => $quotation->ContactInsertion,
				'DeliveryTime' => $quotation->DeliveryTime,
				'PaymentTerm' => $quotation->PaymentTerm
			);
		
			$this->db->where('Id', $quotation->Id);
			$this->db->update('Quotation', $data);
		}
		
		// Add extra options for webshop.
		$this->dbforge->add_column('Webshop', array(
			'OrderFormat' => array(
				'type' => 'VARCHAR',
				'constraint' => 10,
				'null' => true,
				'default' => null,
				'after' => 'Secret'
			)
		));
	}
	
	public function down()
	{
		// Store quotation data into a variable.
		$quotations = $this->db->get('Quotation')->result();
		
		$this->dbforge->drop_column('Quotation', 'CreatorId');
		$this->dbforge->drop_column('Quotation', 'CreatorName');
		$this->dbforge->drop_column('Quotation', 'CustomerId');
		$this->dbforge->drop_column('Quotation', 'CustomerHousenumberAddition');
		$this->dbforge->drop_column('Quotation', 'ContactId');
		$this->dbforge->drop_column('Quotation', 'ContactInsertion');
		$this->dbforge->drop_column('Quotation', 'DeliveryTime');
		$this->dbforge->drop_column('Quotation', 'PaymentTerm');
		
		$this->dbforge->add_column('Quotation', array(
			'CustomerId' => array(
				'type' => 'INT',
				'null' => false,
				'after' => 'BusinessId'
			),
			'CustomerHousenumberAddition' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => false,
				'after' => 'CustomerHousenumber'
			),
			'ContactId' => array(
				'type' => 'INT',
				'null' => false,
				'after' => 'CreatedDate'
			),
			'ContactInsertion' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => false,
				'after' => 'ContactFirstName'
			),
			'DeliveryTime' => array(
				'type' => 'VARCHAR',
				'constraint' => 25,
				'null' => false,
				'after' => 'ValidDays'
			),
			'PaymentTerm' => array(
				'type' => 'INT',
				'null' => false,
				'after' => 'PaymentConditionId'
			),
			'Creator' => array(
				'type' => 'INT',
				'null' => false,
				'after' => 'PaymentTerm'
			)
		));
		
		foreach ($quotations as $quotation) {
			$data = array(
				'CustomerId' => $quotation->CustomerId,
				'CustomerHousenumberAddition' => $quotation->CustomerHousenumberAddition,
				'ContactId' => $quotation->ContactId,
				'ContactInsertion' => $quotation->ContactInsertion,
				'DeliveryTime' => $quotation->DeliveryTime,
				'PaymentTerm' => $quotation->PaymentTerm,
				'Creator' => $quotation->CreatorId
			);
		
			$this->db->where('Id', $quotation->Id);
			$this->db->update('Quotation', $data);
		}
		
		$this->dbforge->drop_column('Webshop', 'OrderFormat');
	}
}
