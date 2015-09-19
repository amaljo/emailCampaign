<?php

Class Messages_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAll($clientId = 0, $type = 0, $single = FALSE) {
        $this->db->where('clientId', $clientId);
        $this->db->order_by('timeInterval');
        if ($type != 0)
            $this->db->where('type', $type);
        if ($single)
            return $this->db->get('messages')->row();
        else
            return $this->db->get('messages')->result();
    }

    function getMessage($id = 0) {
        $this->db->where('id', $id);
        return $this->db->get('messages')->row();
    }

    function save($data, $clientId = 1, $table = 'messages') {
        if ($data['id'] != 0) {
            $this->db->where('id', $data['id']);
            $this->db->update($table, $data);
        } else {
            $this->db->insert($table, $data);
        }
    }

    function getList($start = 0, $total = 200, $clientId = 1, $type = 0, $table = 'messages') {
        $this->db->where('clientId', $clientId);
        $this->db->where('type', $type);
        $this->db->limit($total, $start);
        return $this->db->get($table)->result();
    }

    function getCount($clientId = 1, $type = 0, $table = 'messages') {
        $this->db->where('clientId', $clientId);
        $this->db->where('type', $type);
        return $this->db->get($table)->num_rows();
    }

    function remove($id, $table = 'messages') {
        $this->db->where('id', $id);
        $this->db->delete($table);
    }

}
