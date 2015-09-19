<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Camapaignmanager extends CI_Controller {

    var $data = array();

    function __construct() {
        parent::__construct();
        $this->output->enable_profiler(TRUE);
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->data['pageBodyClass'] = "camapaignmanager";
        $this->data['pageId'] = 'camapaignmanager';
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('camapaignmanager/login', 'refresh');
        }
    }

    function login() {

        $this->load->view('login');
    }

}
