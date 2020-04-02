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
    public $onUnauthorized = array('view', 'errors/custom_unauthorized');

    public function __construct() 
    {
        parent::__construct();
        $this->session->isLoggedIn(true);
    }
	
	public function index() 
	{
        $this->load->helper(array('html','url','form'));
        $this->load->model('Role_model','rm');
        $this->load->model('PortalSite_model','psm');
        $data['content']['role'] = $this->rm->getRole(0, 0, array('order' => array('role_name' => 'ASC')));
        $data['content']['page'] = 'Page Content Management';
        $data['content']['site_list'] = $this->psm->get(0, ['site_id !=' => 1], array('order' => array('site_name' => 'ASC')));
        $data['body']['session'] = $this->state;
        $data['body']['name'] = 'Manage User';
        $data['body']['option']['stylesheet'][0] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.css';
        $data['body']['option']['stylesheet'][1] = base_url().'assets/css/modal_elegant.css';
        $data['body']['option']['stylesheet'][2] = base_url()."assets/modules/sweetalert2/sweetalert2.min.css";
        $data['body']['option']['stylesheet'][3] = base_url().'assets/css/manage_user.css';
        $data['body']['option']['jstop'][0] = base_url().'assets/modules/DataTables/datatables.min.js';
        $data['body']['option']['jstop'][1] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.js';
        $data['body']['option']['js'][0] = base_url()."assets/modules/sweetalert2/sweetalert2.min.js";
        $data['body']['option']['js'][1] = base_url().'assets/modules/tinymce-5.1.2/tinymce.min.js';
        $data['body']['option']['js'][2] = base_url().'assets/modules/tinymce-5.1.2/jquery.tinymce.min.js';
        $data['body']['option']['js'][3] = base_url().'assets/scripts/portal/manage_page.js';
        
        $data['body']['content'] = $this->load->view('portal/contents/administrator/page/index', $data['content'], TRUE);
        $this->load->view('portal/templates/sufee/template', $data['body']);
	}
    

    public function register() 
    {
        if($this->session->isLoggedIn()) redirect($this->success_goto, 'refresh');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->registerProccess()));
        
    }
    /**
    ==========================

    Dibawah Ini Private Class

    ==========================
    **/

}
