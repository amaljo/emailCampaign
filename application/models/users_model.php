<?php

Class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get($userName = '', $password = '') {
        $this->db->where('userName', $userName);
        $this->db->where('password', $password);
        return $this->db->get('managers')->row();
    }

}
