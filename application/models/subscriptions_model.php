<?php

Class Subscriptions_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getClients() {
        return $this->db->get('clients')->result();
    }

    function getClientDetails($id = 0) {
        $this->db->where('id', $id);
        return $this->db->get('clients')->row();
    }

    function save($data, $clientId = 1, $table = 'subscriptions') {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update($table, $data);
        } else {
            $this->db->insert($table, $data);
        }
    }

    function saveMulti($data, $ids = array()) {
        if (!empty($ids)) {
            $this->db->where_in('id', $ids);
            $this->db->update('subscriptions', $data);
        }
    }

    function getList($start = 0, $total = 200, $clientId = 1, $table = 'subscriptions') {
        $this->db->where('clientId', $clientId);
        $this->db->limit($total, $start);
        return $this->db->get($table)->result();
    }

    function getAll($table = 'subscriptions', $clientId = 1) {
        $this->db->where('clientId', $clientId);
        return $this->db->get($table)->result();
    }

    function getCount($clientId = 1, $table = 'subscriptions') {
        $this->db->where('clientId', $clientId);
        return $this->db->get($table)->num_rows();
    }

    function getData($id, $table = 'subscriptions') {
        $this->db->where('id', $id);
        return $this->db->get($table)->row();
    }

    function searchmail($email = '', $clientId = 1, $table = 'subscriptions') {
        $this->db->where('clientId', $clientId);
        if ($email != '') {
            $this->db->where('email', $email);
            return $this->db->get($table)->row();
        }
    }

    function remove($id, $table = 'subscriptions') {
        $this->db->where('id', $id);
        $this->db->delete($table);
    }

    function clearHistory($subscriberId = 0) {
        $this->db->where('subscriberId', $subscriberId);
        $this->db->delete('broadcasthistory');
    }

    function removeMulti($subscriberIDs = array()) {
        if (!empty($subscriberIDs)) {
            $this->db->where_in('id', $subscriberIDs);
            $this->db->delete('subscriptions');
        }
    }

    function clearHistoryMulti($subscriberIDs = array()) {
        if (!empty($subscriberIDs)) {
            $this->db->where_in('subscriberId', $subscriberIDs);
            $this->db->delete('broadcasthistory');
        }
    }

    function getClientLis() {
        return $this->db->get('clients')->result();
    }

}
