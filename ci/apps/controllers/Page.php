<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends SISTER_Controller
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
    
    public function view($page='test_aja')
    {
        #exit(print_r($_SESSION));
        $this->load->helper('html','url');
        $this->load->model('Page_model', 'pm');
        if($this->pm->detail(['page_url' => $page, 'page_status' => 1])){
            $data['content']['body'] = $this->pm->result;
            $data['body']['page'] = 'Dashboard';
            $data['body']['session'] = $this->state;
            $data['body']['option']['stylesheet'][0] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.css';
            $data['body']['option']['js'][2] = base_url().'assets/scripts/portal/portal.js';
            $data['body']['content'] = $this->load->view('portal/contents/page/view', $data['content'], TRUE);
            $this->load->view('portal/templates/sufee/template', $data['body']);
        }else{
            echo $this->pm->db->last_query();
        }
    }
}