<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends SISTER_Controller
{
    /**
    ==========================

    Dibawah Ini Publik Class

    ==========================
    **/
    public $skipCheck = array();
    public $onUnauthorized = array('view', 'errors/custom_unauthorized');

    public function __construct()
    {
        parent::__construct();
        $this->session->isLoggedIn(true);
    }
    
    public function index()
    {
        $this->load->model('Admindashboard_model');
        $this->load->helper('html','url');
        $data['content']['dashboard'] = $this->Admindashboard_model->getContent();
        $data['body']['page'] = 'Dashboard Administrator';
        $data['body']['session'] = $this->session->userdata();
        $data['body']['content'] = $this->load->view('portal/contents/administrator/index', $data['content'], TRUE);
        $this->load->view('portal/templates/sufee/template', $data['body']);
    }
}