<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends SISTER_Controller
{
    /**
    ==========================

    Dibawah Ini Publik Class

    ==========================
     **/
    public $skipCheck = [];
    public $onUnauthorized = array('view', 'errors/custom_unauthorized');

    public function __construct()
    {
        parent::__construct();
        $this->session->isLoggedIn(true);
    }

    public function index()
    {
        $this->output->delete_cache();
        $this->load->helper(array('html','url','form'));
        $this->load->model('Role_model', 'rm');
        #$this->output->enable_profiler(TRUE);
        $data['content']['list_access'] = $this->rm->getAccessList();
        $data['body']['session'] = $this->state;
        $data['body']['name'] = 'Manage Role';
        #exit(print_r($data['content']['list_access']));
        $data['body']['option']['stylesheet'][0] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.css';
        $data['body']['option']['stylesheet'][1] = base_url().'assets/css/modal_elegant.css';
        $data['body']['option']['stylesheet'][2] = base_url().'assets/css/manage_user.css';
        $data['body']['option']['js'][0] = base_url().'assets/modules/DataTables/datatables.min.js';
        $data['body']['option']['js'][1] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.js';
        $data['body']['option']['js'][2] = base_url().'assets/scripts/portal/manage_role.js';
        $data['body']['content'] = $this->load->view('portal/contents/administrator/roles/index', $data['content'], TRUE);
        $this->load->view('portal/templates/sufee/template', $data['body']);
    }


    /**
    ==========================

    Dibawah Ini Private Class

    ==========================
     **/

}
