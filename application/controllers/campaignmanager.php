<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Campaignmanager extends CI_Controller {

    var $data = array();

    function __construct() {
        parent::__construct();
        // $this->output->enable_profiler(TRUE);

        $this->data['pageBodyClass'] = "actions";
        $this->data['pageId'] = 'actions';
    }

    function checkLogin() {
        if (!$this->session->userdata('logged_in')) {
            // redirect them to the login page
            redirect('campaignmanager/login', 'refresh');
        }
    }

    public function index() {
        $this->checkLogin();
        $this->load->view('manager/dashboard');
    }

    function login() {
        $response = array();
        $response['errorLogin'] = '';
        if ($this->input->post('login')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('userName', 'User Name', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $userName = $this->security->xss_clean($this->input->post('userName'));
            $password = $this->security->xss_clean($this->input->post('password'));
            if ($this->form_validation->run() == TRUE) {
                $this->load->model('users_model', 'users');
                $userData = $this->users->get($userName, sha1($password));
                if (!empty($userData)) {
                    $logindata = array(
                        'name' => $userData->name,
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($logindata);
                    redirect('campaignmanager', 'refresh');
                } else {
                    $response['errorLogin'] = 'Invalid Username/Password!';
                }
            } else
                $response['errorLogin'].= 'Username/Password missing!';
        }
        $this->load->view('manager/header');
        $this->load->view('manager/login', $response);
        $this->load->view('manager/footer');
    }

    function logout() {
        $this->session->unset_userdata(array('name', 'logged_in'));
        redirect('campaignmanager/login', 'refresh');
    }

    function subscriptions() {
        $this->checkLogin();
        $data = array();
        $this->load->model('subscriptions_model', 'subscriptions');
        $data = $this->subscriptions->getAll();
        $this->load->view('campaignmanager/subscriptionlist', $data);
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

    function clients() {
        $this->checkLogin();
        $data = array();
        $this->load->model('subscriptions_model', 'subscriptions');
        $data['clients'] = $this->subscriptions->getClientLis();

        $this->load->view('manager/header');
        $this->load->view('manager/clients', $data);
        $this->load->view('manager/footer');
    }

    function messages($clientId = 1) {
        $this->checkLogin();
        $data = array();
        $this->load->model('subscriptions_model', 'subscriptions');
        $this->load->model('messages_model', 'messages');

        $data['messages'] = $this->messages->getAll($clientId, 0);
        $data['clientDetails'] = $this->subscriptions->getData($clientId, 'clients');

        $this->load->view('manager/header');
        $this->load->view('manager/messages', $data);
        $this->load->view('manager/footer');
    }

    function saveMessage($clientId = 0, $id = 0) {
        $this->checkLogin();
        $this->load->model('subscriptions_model', 'subscriptions');
        $this->load->model('messages_model', 'messages');
        $data['errorLogin'] = '';
        $data['subject'] = '';
        $data['clientId'] = $clientId;
        $data['message'] = '';
        $data['timeInterval'] = '';
        $data['type'] = 1;
        $data['id'] = $id;

        if ($this->input->post('save')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            $this->form_validation->set_rules('timeInterval', 'Broadcast time Period', 'trim|required');
            $this->form_validation->set_rules('type', 'Type', 'trim|required');

            if ($this->form_validation->run() == TRUE) {
                $toSave['id'] = $this->security->xss_clean($this->input->post('id'));
                $toSave['clientId'] = $this->security->xss_clean($this->input->post('clientId'));
                $toSave['subject'] = $this->security->xss_clean($this->input->post('subject'));
                $toSave['message'] = $this->security->xss_clean($this->input->post('message'));
                $toSave['timeInterval'] = $this->security->xss_clean($this->input->post('timeInterval'));
                $toSave['type'] = $this->security->xss_clean($this->input->post('type'));
                $this->messages->save($toSave);
                redirect('campaignmanager/messages/' . $toSave['clientId']);
            } else {
                $data['errorLogin'] = 'All Fields are required';
            }
        }

        if ($id != 0) {
            $record = $this->messages->getMessage($this->security->xss_clean($id));
            if (empty($record))
                redirect('campaignmanager/?invalid request');


            $data['subject'] = $record->subject;
            $data['clientId'] = $record->clientId;
            $data['message'] = $record->message;
            $data['timeInterval'] = $record->timeInterval;
            $data['type'] = $record->type;
            $data['id'] = $record->id;
        }
        $data['clientDetails'] = $this->subscriptions->getData($clientId, 'clients');

        $this->load->view('manager/header');
        $this->load->view('manager/messages_save', $data);
        $this->load->view('manager/footer');
    }

}
