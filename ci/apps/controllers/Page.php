<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends SISTER_Controller
{
    /**
    ==========================

    Dibawah Ini Publik Class

    ==========================
    **/

    public $skipCheck = [];

    public function __construct()
    {
        parent::__construct();
        $this->session->isLoggedIn(true);
    }
    
    public function view($page='test_aja')
    {
        $this->load->helper('html','url');
        $this->load->model('Page_model', 'pm');
        $data['body']['session'] = $this->state;
        if($this->pm->detail(['page_url' => $page, 'page_status' => 1])){
            $data['content']['body'] = $this->pm->result;
            $data['body']['option']['stylesheet'][0] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.css';
            $data['body']['option']['stylesheet'][1] = base_url().'assets/css/fix-iframe.css';
            $data['body']['option']['js'][2] = base_url().'assets/scripts/portal/portal.js';
            $data['body']['content'] = $this->load->view('portal/contents/page/view', $data['content'], TRUE);
        }else{
            $data['content']['page'] = 'Error';
            $data['body']['content'] = $this->load->view('errors/custom_unauthorized', $data['content'], TRUE);
        }
        $this->load->view('portal/templates/sufee/template', $data['body']);
        
    }
}