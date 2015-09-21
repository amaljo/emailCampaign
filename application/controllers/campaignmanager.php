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
        $this->load->model('subscriptions_model', 'subscriptions');
        $data['clients'] = $this->subscriptions->getClientLis();
        $this->load->view('manager/header');
        $this->load->view('manager/dashboard', $data);
        $this->load->view('manager/footer');
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
        $this->session->unset_userdata(array('name' => '', 'logged_in' => ''));
        redirect('campaignmanager/login', 'refresh');
    }

    function subscriptions($clientId = 0) {
        $this->checkLogin();
        $data = array();
        $this->load->model('subscriptions_model', 'subscriptions');
        $data['subscribers'] = $this->subscriptions->getAll('subscriptions', $clientId);
        $data['clientDetails'] = $this->subscriptions->getData($clientId, 'clients');
        $this->load->view('manager/header');
        $this->load->view('manager/subscriptions', $data);
        $this->load->view('manager/footer');
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

    function remove($id = 0) {
        $this->load->model('messages_model', 'messages');
        $this->load->library('user_agent');
        $this->messages->remove($id);
        redirect($this->agent->referrer());
    }

}
