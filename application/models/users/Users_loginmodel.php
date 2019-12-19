<?php

class Users_loginmodel extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function checkUsername($username) {
        $this->db->where('Username', $username);
        $this->db->from('User');
        $aantal = $this->db->count_all_results();
        if (empty($aantal)) {
            return false;
        } else {
            return true;
        }
    }

    function checkPassword($wachtwoord, $username) {
        $salt = hash("md5", $username);
        $pass = hash("sha256", $wachtwoord . $salt);

        $this->db->where('Password', $pass);
        $this->db->where('Username', $username);
        $this->db->from('User');
        $aantal = $this->db->count_all_results();
        if (empty($aantal)) {
            return false;
        } else {
            return true;
        }
    }

    function getInformation($naam) {
        $this->db->where('Username', $naam);
        $this->db->from('User');
        return $this->db->get();
    }

    function getBedrijf($businessId) {
        $this->db->where('Id', $businessId);
        $this->db->from('Business');
        return $this->db->get();
    }

}
