<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getProduct($articleC)
{
	$ci = & get_instance();


	if ($ci->session->userdata('user')) {
		$businessId = $ci->session->userdata('user')->BusinessId;
		$ci->db->where('BusinessId', $businessId);

	}
	else{
		// Coming soon...
	}

	$ci->db->where('ArticleNumber', $articleC);
	return $ci->db->get('Product')->row();
}



function getProductById($productId)
{
	$ci = & get_instance();
	$businessId = $ci->session->userdata('user')->BusinessId;

	$ci->db->where('Id', $productId);
	$ci->db->where('BusinessId', $businessId);
	return $ci->db->get('Product')->row();
}

/**
 * Make sure the position order is summed up correctly.
 *
 */
function reorderImages($productId, $mainImageId = 0)
{
	$ci = & get_instance();

	$pos = $mainImageId !== 0 ? 1 : 0;

	$ci->db->order_by('Position', 'asc');
	$productImages = $ci->db->get_where('ProductImage', array('ProductId' => $productId))->result();

	foreach ($productImages as $productImage) {
		if ($mainImageId !== 0 && $productImage->Id == $mainImageId) {
			$data['Position'] = 0;
		}
		else {
			$data['Position'] = $pos++;
		}

		$ci->db->where('Id', $productImage->Id);
		$ci->db->update('ProductImage', $data);
	}
}
