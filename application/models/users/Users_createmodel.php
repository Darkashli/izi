<?php
class Users_createmodel extends CI_Model {
    
    private $tbl_parent= 'User';
    
    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
    
    function checkName($username){
        $this->db->where('Username', $username);
        $this->db->from($this->tbl_parent);

        $result = $this->db->count_all_results();

        if(empty($result)){
            return true;
        }else{
            return false;
        }
    }
    
    function createUser($data){
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
}