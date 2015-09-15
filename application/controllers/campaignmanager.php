<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Camapaignmanager extends CI_Controller {

    var $data = array();

    function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth'));
        $this->load->model('subscriptions_model', 'subscriptions');
        $this->data['pageBodyClass'] = "camapaignmanager";
        $this->data['pageId'] = 'camapaignmanager';
        $this->load->library('form_validation');
    }

    public function index() {
        $this->data['menuList'] = $this->getMenuItems();
        $this->load->view('subscription_form');
    }

    function subscribe() {
        $response = array();
        if ($this->input->post('subscribe')) {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div>', '</div>');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('clientId', 'Client Id', 'trim|required');
            $data['clientId'] = $this->security->xss_clean($this->input->post('clientId'));
            $clientDetails = $this->subscriptions->getClientDetails($data['clientId']);

            //Validation if false then load the ADD/Edit category page again
            if ($this->form_validation->run() == FALSE) {
                $response['message'] = 'Try Again Later';
                $response['status'] = 0;
            } else {
                if (!empty($clientDetails)) {
                    $data['email'] = $this->security->xss_clean($this->input->post('email'));
                    $data['name'] = $this->security->xss_clean($this->input->post('name'));
                    $subcription = $this->subscriptions->searchmail($data['email'], $data['clientId']);
                    $response['status'] = 1;
                    if (empty($subcription)) {
                        $this->subscriptions->save($data);
                        $response['message'] = 'Subscribed Successfully';
                        $clientDetails = $this->subscriptions->getClientDetails($data['clientId']);
                        $this->load->library('email');
                        $this->email->initialize(array('mailtype' => 'html'));
                        $this->email->from($clientDetails->senderEmail, $clientDetails->clientsName);
                        $this->email->to($data['email']);
                        $this->email->bcc($this->config->item('admin_email'));
                        $this->email->subject('Thanks for Newsletter Subscription : ' . $clientDetails->clientsDomain);
                        $this->email->message($this->load->view('mailTemplates/newsletterSubscribe', array_merge($data, (array) $clientDetails), true));
                        $this->email->send();
                    } else {
                        $response['message'] = 'Already subscribed';
                    }
                } else {
                    $response['message'] = 'Client Id provided is not valid';
                    $response['status'] = 0;
                }
            }
        }
        echo json_encode($response);
    }

    function getMenuItems() {
        return array();
    }

}
