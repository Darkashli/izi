<?php

class Product_model extends CI_Model
{
    private $tbl_parent = 'Product';

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($BusinessId)
    {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getAllS($BusinessId)
    {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->group_start();
            $this->db->where('Type', 0);
            $this->db->or_where('Type', 2);
        $this->db->group_end();
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getAllP($supplierId = 0, $BusinessId)
    {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->group_start();
            $this->db->where('Type', 1);
            $this->db->or_where('Type', 2);
        $this->db->group_end();

        if ($supplierId != 0)
        {
            $this->db->group_start();
            $this->db->where('SupplierId', $supplierId);
            $this->db->or_where('SupplierId', NULL);
            $this->db->or_where('SupplierId', 0);
            $this->db->group_end();
        }
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getProduct($productId, $businessId)
    {
        $this->db->where('Id', $productId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getProductByArticleNumber($articleNumber, $businessId)
    {
        $this->db->where('ArticleNumber', $articleNumber);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getProductByWebshopId($webshopId)
    {
        $this->db->where('shopId', $webshopId);
        return $this->db->get($this->tbl_parent);
    }
    
    function getProductByUserNature($userId, $natureOfWork)
    {
        $this->db->where('UserId', $userId);
        $this->db->where('NatureOfWork', $natureOfWork);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getProductsSByEanCode($businessId, $searchValue)
    {
        $this->db->where('BusinessId', $businessId);
        $this->db->group_start();
        $this->db->where('Type', 0);
        $this->db->or_where('Type', 2);
        $this->db->group_end();
        $this->db->where('EanCode', $searchValue);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function searchProducts(
        $businessId,
        $value = '',
        $field = 'ArticleNumber',
        $type = 2, // Both sales and purchase
        $productKind = 'all',
        $supplierId = 0,
        $isWebshop = 2 // 0 = no, 1 = yes, 2 = all
    )
    {
        $this->db->where('BusinessId', $businessId);
        $this->db->like($field, $value);
        if ($type != 2) {
            $this->db->group_start();
                $this->db->where('Type', 2);
                $this->db->or_where('Type', $type);
            $this->db->group_end();
        }
        if ($productKind != 0) {
            $this->db->where('ProductKind', $productKind);
        }
        if ($supplierId != 0) {
            $this->db->where('SupplierId', $supplierId);
        }
        if ($isWebshop == 0) {
            $this->db->where('isShop', 0);
        }
        elseif ($isWebshop == 1) {
            $this->db->where('isShop', 1);
        }
        return $this->db->get($this->tbl_parent);
    }

    function updateProduct($productId, $data)
    {
        $this->db->where('Id', $productId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createProduct($data)
    {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
}
