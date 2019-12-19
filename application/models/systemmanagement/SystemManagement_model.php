<?php

class SystemManagement_model extends CI_Model {

    private $tbl_parent = 'SystemManagementNetworkInformation';
    private $tbl_internet = 'SystemManagementInternetData';
    private $tbl_hardware = "SystemManagementHardware";
    private $tbl_software = "SystemManagementSoftware";
    private $tbl_group = "SystemManagementGroup";
    private $tbl_user = "SystemManagementUser";
    private $tbl_share = "SystemManagementShare";
    private $tbl_logon = "SystemManagementLogonScript";

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getSystemManagementNetworkInformation($CustomerId) {
        $this->db->where('CustomerId', $CustomerId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function updateSystemManagementNetworkInformation($SystemManagementId, $data) {
        $this->db->where('Id', $SystemManagementId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createSystemManagementNetworkInformation($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

    function getInternetData($CustomerId) {
        $this->db->where('CustomerId', $CustomerId);
        $this->db->from($this->tbl_internet);
        return $this->db->get();
    }

    function updateInternetData($SystemManagementId, $data) {
        $this->db->where('Id', $SystemManagementId);
        $this->db->update($this->tbl_internet, $data);
    }

    function createInternetData($data) {
        $this->db->insert($this->tbl_internet, $data);
        return $this->db->insert_id();
    }

    function getAllHardware($customerId) {
        $this->db->where('CustomerId', $customerId);
        $this->db->from($this->tbl_hardware);
        return $this->db->get();
    }

    function getHardware($hardwareId) {
        $this->db->where('Id', $hardwareId);
        $this->db->from($this->tbl_hardware);
        return $this->db->get();
    }

    function createHardware($data) {
        $this->db->insert($this->tbl_hardware, $data);
        return $this->db->insert_id();
    }

    function updateHardware($hardwareId, $data) {
        $this->db->where('Id', $hardwareId);
        $this->db->update($this->tbl_hardware, $data);
    }

    function deleteHardware($hardwareId) {
        $this->db->where('Id', $hardwareId);
        $this->db->delete($this->tbl_hardware);
    }

    function getAllSoftware($customerId) {
        $this->db->where('CustomerId', $customerId);
        $this->db->from($this->tbl_software);
        return $this->db->get();
    }

    function createSoftware($data) {
        $this->db->insert($this->tbl_software, $data);
        return $this->db->insert_id();
    }

    function getSoftware($softwareId) {
        $this->db->where('Id', $softwareId);
        $this->db->from($this->tbl_software);
        return $this->db->get();
    }

    function deleteSoftware($softwareId) {
        $this->db->where('Id', $softwareId);
        $this->db->delete($this->tbl_software);
    }

    function updateSoftware($softwareId, $data) {
        $this->db->where('Id', $softwareId);
        $this->db->update($this->tbl_software, $data);
    }

    function getAllGroup($customerId) {
        $this->db->where('CustomerId', $customerId);
        $this->db->from($this->tbl_group);
        return $this->db->get();
    }

    function createGroup($data) {
        $this->db->insert($this->tbl_group, $data);
        return $this->db->insert_id();
    }

    function getGroup($groupId) {
        $this->db->where('Id', $groupId);
        $this->db->from($this->tbl_group);
        return $this->db->get();
    }

    function updateGroup($groupId, $data) {
        $this->db->where('Id', $groupId);
        $this->db->update($this->tbl_group, $data);
    }

    function deleteGroup($groupId) {
        $this->db->where('Id', $groupId);
        $this->db->delete($this->tbl_group);
    }

    function getAllUser($customerId) {
        $this->db->where('CustomerId', $customerId);
        $this->db->from($this->tbl_user);
        return $this->db->get();
    }

    function createUser($data) {
        $this->db->insert($this->tbl_user, $data);
        return $this->db->insert_id();
    }

    function getUser($userId) {
        $this->db->where('Id', $userId);
        $this->db->from($this->tbl_user);
        return $this->db->get();
    }

    function updateUser($userId, $data) {
        $this->db->where('Id', $userId);
        $this->db->update($this->tbl_user, $data);
    }

    function deleteUser($userId) {
        $this->db->where('Id', $userId);
        $this->db->delete($this->tbl_user);
    }

    function getAllShare($customerId) {
        $this->db->where('CustomerId', $customerId);
        $this->db->from($this->tbl_share);
        return $this->db->get();
    }

    function createShare($data) {
        $this->db->insert($this->tbl_share, $data);
        return $this->db->insert_id();
    }

    function getShare($userId) {
        $this->db->where('Id', $userId);
        $this->db->from($this->tbl_share);
        return $this->db->get();
    }

    function updateShare($shareId, $data) {
        $this->db->where('Id', $shareId);
        $this->db->update($this->tbl_share, $data);
    }

    function deleteShare($shareId) {
        $this->db->where('Id', $shareId);
        $this->db->delete($this->tbl_share);
    }

    function getAllLogon($customerId) {
        $this->db->where('CustomerId', $customerId);
        $this->db->from($this->tbl_logon);
        return $this->db->get();
    }

    function createLogon($data) {
        $this->db->insert($this->tbl_logon, $data);
        return $this->db->insert_id();
    }

    function getLogon($userId) {
        $this->db->where('Id', $userId);
        $this->db->from($this->tbl_logon);
        return $this->db->get();
    }

    function updateLogon($logonId, $data) {
        $this->db->where('Id', $logonId);
        $this->db->update($this->tbl_logon, $data);
    }

    function deleteLogonScript($logonId) {
        $this->db->where('Id', $logonId);
        $this->db->delete($this->tbl_logon);
    }

}
