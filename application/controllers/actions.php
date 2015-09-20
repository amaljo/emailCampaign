<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Actions extends CI_Controller {

    var $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('subscriptions_model', 'subscriptions');
        $this->load->model('messages_model', 'messages');
        $this->data['pageBodyClass'] = "actions";
        $this->data['pageId'] = 'actions';
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
                        $data['clientMessage'] = $this->messages->getAll($clientId, 2, TRUE);
                        $this->load->library('email');
                        $this->email->initialize(array('mailtype' => 'html'));
                        $this->email->from($clientDetails->senderEmail, $clientDetails->clientsName);
                        $this->email->to($data['email']);
                        $this->email->bcc($this->config->item('admin_email'));
                        $this->email->subject($data['clientMessage']->subject);
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

    /* Crone function to send emails */

    function suiteUpBroadcast($clientId = 0) {
        $clientDetails = $this->subscriptions->getClientDetails($this->security->xss_clean($clientId));
        if (empty($clientDetails))
            redirect('/?invalid Request');

        /* Start preparations */

        $messages = $this->messages->getAll($clientId, 1);
        $currentTimeStamp = date("Y-m-d H:i:s");
        foreach ($messages as $message) {
            $recipients = array();
            $relationQuery = 'INSERT INTO `broadcasthistory` (`id`, `messageId`, `subscriberId`, `createdAt`) VALUES ';
            $sql = "SELECT id,email FROM `subscriptions` WHERE TIMESTAMPDIFF(HOUR, created, ?) >= {$message->timeInterval} and id not in (SELECT subscriberId FROM `broadcasthistory` WHERE messageId=?)";
            $subscribers = $this->db->query($sql, array($currentTimeStamp, $message->id))->result();
            if (!empty($subscribers)) {
                foreach ($subscribers as $subscriber) {
                    $recipients[] = $subscriber->email;
                    $relationQuery.="(NULL, {$message->id}, {$subscriber->id}, CURRENT_TIMESTAMP),";
                }
                $relationQuery = rtrim($relationQuery, ',') . ';';
                $this->db->query($relationQuery);
                $data['clientDetails'] = $clientDetails;
                $data['message'] = $message;
                $data['subscriber'] = $subscriber;
                $this->load->library('email');
                $this->email->initialize(array('mailtype' => 'html'));
                $this->email->from($clientDetails->senderEmail, $clientDetails->clientsName);
                $this->email->to($recipients);
                $this->email->bcc($this->config->item('admin_email'));
                $this->email->subject($message->subject);
                $this->email->message($this->load->view('mailTemplates/newsletterFollowUp', $data, true));
                $this->email->send();
            }
        }
    }

    function unsubscibe($step = 0, $subscriberId = 0) {
        if ($step > 0) {
            
        }
    }

}
