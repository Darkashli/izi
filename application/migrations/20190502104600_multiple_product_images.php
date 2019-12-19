<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Multiple_product_images extends CI_Migration {
	
	public function up()
	{
		// Store all product data in a variable.
		$this->db->where('Image IS NOT NULL', '', false);
		$products = $this->db->get('Product')->result();
		
		// Remove image column from product table.
		$this->dbforge->drop_column('Product', 'Image');
		
		// Create new product image table.
		$this->dbforge->add_field(array(
			'Id' => array(
				'type' => 'INT',
				'unisigned' => true,
				'auto_increment' => true
			),
			'ProductId' => array(
				'type' => 'INT'
			),
			'FileName' => array(
				'type' => 'VARCHAR',
				'constraint' => 2083 // Old length
			),
			'Position' => array(
				'type' => 'INT'
			),
			'BusinessId' => array(
				'type' => 'INT'
			)
		));
		$this->dbforge->add_key('Id', TRUE);
		$this->dbforge->create_table('ProductImage');
		
		// Fill new table with existing images.
		foreach ($products as $product) {
			$data = array(
				'ProductId' => $product->Id,
				'FileName' => $product->Image,
				'Position' => 0,
				'BusinessId' => $product->BusinessId
			);
			$this->db->insert('ProductImage', $data);
		}
	}
	
	public function down()
	{
		// Store all product image data in a variable.
		$productImages = $this->db->get('ProductImage')->result();
		
		// Remove the product image table.
		$this->dbforge->drop_table('ProductImage', true);
		
		// Re-add the image column to the product table.
		$this->dbforge->add_column('Product', array(
			'Image' => array(
				'type' => 'VARCHAR',
				'constraint' => 2083,
				'null' => true,
				'after' => 'LongDescription'
			)
		));
		
		foreach ($productImages as $productImage) {
			if ($productImage->Position == 0) {
				
				// Fill new table columns with the main product image (position 0).
				$data = array(
					'Image' => $productImage->FileName,
				);
				$this->db->where('Id', $productImage->ProductId);
				$this->db->update('Product', $data);
				
			}
			else {
				
				// Delete non-main image because products can no longer contain multiple images.
				$business = $this->db->get_where('Business', array('Id' => $productImage->BusinessId))->row();
				@unlink("./uploads/$business->DirectoryPrefix/products/$productImage->ProductId/$productImage->FileName");
				
			}
		}
		
	}
}
