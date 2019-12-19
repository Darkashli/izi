<?php
class Users_searchmodel extends CI_Model {

    private $tbl_parent= 'User';
 
    function __construct()  {
        // Call the Model constructor
        parent::__construct();
    }
        
    function ParentModel(){
        parent::Model();
    }

    function count_all($id){
        $this->db->from($this->tbl_parent);
        return $this->db->count_all_results();
    }

    function count_all_search($searchparams, $id){
        $this->db->like($searchparams,'','after');
        $this->db->from($this->tbl_parent);		
        return $this->db->count_all_results();		
    }

    function get_paged_list($id, $limit = 10, $offset = 0){
        $this->db->order_by('Username','asc');
        return $this->db->get($this->tbl_parent, $limit, $offset);
    }

    function get_search_pagedlist($id, $searchparams,$limit = 10, $offset = 0){
        $this->db->like($searchparams,'','after');
        return $this->db->get($this->tbl_parent, $limit, $offset);
    }
}