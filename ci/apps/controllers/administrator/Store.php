<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends SISTER_Controller
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
        $data['body']['session'] = $this->session->userdata();
        $data['body']['name'] = 'Manage Store';
        $data['body']['type'] = $this->load->model('StoreType_model')->get();
        $data['body']['option']['stylesheet'][0] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.css';
        $data['body']['option']['stylesheet'][1] = base_url().'assets/css/modal_elegant.css';
        $data['body']['option']['stylesheet'][2] = base_url().'assets/css/manage_user.css';
        $data['body']['option']['js'][0] = base_url().'assets/modules/DataTables/datatables.min.js';
        $data['body']['option']['js'][1] = base_url().'assets/modules/DataTables/dataTables.bootstrap4.min.js';
        $data['body']['option']['js'][2] = base_url().'assets/scripts/portal/manage_store.js';
        $data['body']['content'] = $this->load->view('portal/contents/administrator/stores/index', array(), TRUE);
        $this->load->view('portal/templates/sufee/template', $data['body']);
    }

    /**
    ==========================

    Dibawah Ini Private Class

    ==========================
     **/

}
