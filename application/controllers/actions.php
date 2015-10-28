<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Actions extends CI_Controller {

    var $data = array();
    var $decryptKey = 'am@lj0ant0ny';

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
                    $data['created'] = date("Y-m-d H:i:s");
                    $subcription = $this->subscriptions->searchmail($data['email'], $data['clientId']);
                    $response['status'] = 1;
                    if (empty($subcription)) {
                        $this->subscriptions->save($data);
                        $response['message'] = 'Subscribed Successfully';
                        $clientDetails = $this->subscriptions->getClientDetails($data['clientId']);
                        $data['clientMessage'] = $this->messages->getAll($data['clientId'], 2, 0, TRUE);
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

    function suiteUpBroadcast($clientId = 0, $type = 1, $messageId = 0, $from = 'local') {
        $clientDetails = $this->subscriptions->getClientDetails($this->security->xss_clean($clientId));
        if (empty($clientDetails))
            redirect('/?invalid Request');

        /* Start preparations */

        $this->load->library('encrypt');

        /* Get All Messsges */
        $totalRecipients = 0;
        $recordFetch = $messageId != 0 ? TRUE : FALSE;
        $messages = $this->messages->getAll($clientId, $type, $messageId);

        $currentTimeStamp = date("Y-m-d H:i:s");
        foreach ($messages as $message) {
            $recipients = array();
            $relationQuery = 'INSERT INTO `broadcasthistory` (`id`, `messageId`, `subscriberId`, `createdAt`) VALUES ';
            $sql = "SELECT id,email FROM `subscriptions` WHERE status=1 and TIMESTAMPDIFF(HOUR, created, ?) >= {$message->timeInterval} and id not in (SELECT subscriberId FROM `broadcasthistory` WHERE messageId=?)";
            $subscribers = $this->db->query($sql, array($currentTimeStamp, $message->id))->result();

            if (!empty($subscribers)) {
                $totalRecipients+=count($subscribers);
                foreach ($subscribers as $subscriber) {
                    $data['clientDetails'] = $clientDetails;
                    $data['message'] = $message;
                    $data['subscriber'] = $subscriber;
                    $textToEncode = 1 . '=' . $subscriber->id;
                    $data['unsubscribeKey'] = htmlentities(urlencode($this->encrypt->encode($textToEncode)));
                    $this->load->library('email');
                    $this->email->initialize(array('mailtype' => 'html'));
                    $this->email->from($clientDetails->senderEmail, $clientDetails->clientsName);
                    $this->email->to($subscriber->email);
                    $this->email->bcc($this->config->item('admin_email'));
                    $this->email->subject($message->subject);
                    $this->email->message($this->load->view('mailTemplates/newsletterFollowUp', $data, true));
                    $this->email->send();
                    $relationQuery.="(NULL, {$message->id}, {$subscriber->id}, CURRENT_TIMESTAMP),";
                }
                $relationQuery = rtrim($relationQuery, ',') . ';';
                $this->db->query($relationQuery);
            }
        }
        $msg = count($messages) . ' messages sent. Recipients : ' . $totalRecipients;
        if ($from == 'admin')
            redirect('/campaignmanager/messages/' . $clientId . '/' . $msg);
        else
            echo $msg;
    }

    function unsubscibe() {
        $this->load->library('encrypt');
        /* echo 'xss_clean : ' . $this->security->xss_clean($_REQUEST['proceed']);
          echo '<br>proceed : ' . $_REQUEST['proceed'];
          echo '<br>decoded : ' . $this->encrypt->decode($_REQUEST['proceed']);
         */
        $msgData = explode('=', $this->encrypt->decode($_REQUEST['proceed']));
        $data = array();
        $message = '';
        /* echo '<pre>';
          print_r($msgData);
          echo '</pre>';
          die(); */
        /* Confirm Subscription */
        if ($msgData[0] == 1) {
            $data['subscriber'] = $this->subscriptions->getData($msgData[1]);
            if (!empty($data['subscriber'])) {
                $data['clientDetails'] = $this->subscriptions->getClientDetails($data['subscriber']->clientId);
                $textToEncode = 2 . '=' . $data['subscriber']->id;
                $data['unsubscribeKey'] = htmlentities(urlencode($this->encrypt->encode($textToEncode)));
                $this->load->library('email');
                $this->email->initialize(array('mailtype' => 'html'));
                $this->email->from($data['clientDetails']->senderEmail, $data['clientDetails']->clientsName);
                $this->email->to($data['subscriber']->email);
                $this->email->bcc($this->config->item('admin_email'));
                $this->email->subject('Confirm Unsubscription : ' . $data['clientDetails']->clientsDomain);
                $this->email->message($this->load->view('mailTemplates/unsubscribeConfirmation', $data, true));
                $this->email->send();
                $message = 'Your request processed! Check your email for further informations';
            }
        } else if ($msgData[0] == 2) {
            $data['subscriber'] = $this->subscriptions->getData($msgData[1]);
            if (!empty($data['subscriber'])) {
                $this->subscriptions->remove($data['subscriber']->id);
                $this->subscriptions->clearHistory($data['subscriber']->id);
                $message = 'Your have successfully un subscribed';
            }
        } else
            redirect('/?invalid Request');

        $this->load->view('message', array('message' => $message));
    }

    function testEncode() {
        $this->load->library('encrypt');
        echo $this->encrypt->encode($_REQUEST['text']);
    }

    function testDecode() {
        $this->load->library('encrypt');
        echo $this->encrypt->decode($_REQUEST['text']);
    }

}
