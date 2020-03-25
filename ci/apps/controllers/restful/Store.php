<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends CI_Controller
{
    /**
    ==========================

    Dibawah Ini Publik Class

    ==========================
    **/

    public $rules;

    public function __construct() 
    {
        parent::__construct();
        $this->load->library('session');
    }
	

	public function list_paged() 
	{
        $this->load->model('PortalStore_model', 'psm');
        $this->load->helper('url');
        
        $data['option'] = array(
            #'result' => 10,
            #'page' => $_POST['page'],
            'order' => array('store_type' => 'ASC')
        );
        $data['content'] = $this->psm->show($data['option']);
        if($data['content']['total'] > 0){
            $this->output->set_content_type('application/json')->set_output(json_encode($data['content'],JSON_NUMERIC_CHECK));
        }
        
	}
    

    public function do()
    {
        if ($this->input->is_ajax_request()) {
            $input = array();
            if ($this->input->post('action_mode') == 'create') {
                $this->output->set_content_type('application/json')->set_output(json_encode($this->doCreate($_POST), JSON_NUMERIC_CHECK));
            } elseif ($this->input->post('action_mode') == 'modify') {
                {
                    $this->output->set_content_type('application/json')->set_output(json_encode($this->doModify($_POST), JSON_NUMERIC_CHECK));
                }
            }
        }
    }


    public function detail($sid)
    {
        $this->load->library('form_validation');
        $_POST['sid'] = $sid;
        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('sid', 'Store ID', array('required', 'numeric', 'max_length[3]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('PortalStore_model', 'psm');
            $data['form']['where']['store_id'] = set_value('sid');
            if($this->psm->showStore($data['form']) > 0){
                $output['success'] = 1;
                $output['data']['store'] = $this->psm->result;
            }else{
                $output['success'] = 0;
                $output['message'] = 'Failed to fetch data';
            }
        }
        $output['response'] = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($output, JSON_NUMERIC_CHECK));

    }


    private function doCreate($post)
    {
        $_POST = $post;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('store_name', 'Store Name', array('trim', 'required', 'alpha_numeric_spaces', 'max_length[50]'));
        $this->form_validation->set_rules('store_email', 'Store Email', array('trim', 'required', 'valid_email', 'max_length[50]', 'is_unique[portal_store.store_email]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('PortalStore_model', 'psm');
            $data['form']['stores']['store_name'] = set_value('store_name');
            $data['form']['stores']['store_type'] = set_value('store_type');
            $data['form']['stores']['store_email'] = set_value('store_email');
            $data['form']['stores']['store_status'] = set_value('store_status');
            if($this->psm->create($data['form']) > 0){
                $output['success'] = 1;
            }else{
                $output['success'] = 0;
                $output['message'] = 'Failed to create Store';
            }
        }
        $output['response'] = array(
            'csrfName' => $this->security->get_csrf_token_name(), //for name
            'csrfHash' => $this->security->get_csrf_hash() //for value
        );
        return $output;

    }

    private function doModify($post)
    {
        $_POST = $post;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('site_name', 'Site Name', array('trim', 'required', 'alpha_numeric_spaces', 'max_length[50]'));
        $this->form_validation->set_rules('site_id', 'Site ID', array('required', 'numeric', 'max_length[6]'));
        $this->form_validation->set_rules('site_color', 'Site Color', array('trim', 'required', 'regex_match[/^[A-Z0-9 -]+$/i]', 'max_length[15]'));
        $this->form_validation->set_rules('site_icon', 'Site Icon', array('trim', 'required', 'regex_match[/^[A-Z0-9 -]+$/i]', 'max_length[30]'));
        $this->form_validation->set_rules('site_status', 'Site Status', array('required', 'numeric', 'max_length[1]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('PortalStore_model', 'psm');
            $data['form']['where']['site_id'] = set_value('site_id');
            $data['form']['sites']['site_name'] = set_value('site_name');
            $data['form']['sites']['site_color'] = set_value('site_color');
            $data['form']['sites']['site_icon'] = set_value('site_icon');
            $data['form']['sites']['site_status'] = set_value('site_status');
            if($this->psm->modify($data['form']) > 0){
                $output['success'] = 1;
            }else{
                $output['success'] = 0;
                $output['message'] = 'Failed to modify Store';
            }
        }
        $output['response'] = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        return $output;

    }

}
