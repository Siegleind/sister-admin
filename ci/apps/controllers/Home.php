<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends SISTER_Controller
{
    /**
    ==========================

    Dibawah Ini Publik Class

    ==========================
    **/

    public $skipCheck = array('index');

    public function __construct()
    {
        parent::__construct();
        $this->session->isLoggedIn(true);
        

    }
    
    public function index()
    {
        $this->load->helper('html','url');
        $this->load->model('HomeDashboard_model', 'hdm');
        $data['content']['dashboard'] = $this->hdm->getDashboard($this->state);
        $data['content']['session'] = $this->state;
        #print_r($this->db->last_query());
        $data['body']['page'] = 'Dashboard';
        $data['body']['session'] = $this->state;
        $data['body']['option']['stylesheet'][0] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.css';
        $data['body']['option']['js'][2] = base_url().'assets/scripts/portal/portal.js';
        $data['body']['content'] = $this->load->view('portal/contents/home/index', $data['content'], TRUE);
        $this->load->view('portal/templates/sufee/template', $data['body']);
    }
}