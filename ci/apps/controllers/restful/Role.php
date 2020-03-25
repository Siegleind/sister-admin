<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller
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
        $this->load->model('Role_model');
        $this->load->helper('url');
        
        $data['option'] = array(
            #'result' => 10,
            #'page' => $_POST['page'],
            'order' => array('role_name' => 'ASC')
        );
        $data['content'] = $this->Role_model->getRolePaged($data['option']);
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
    public function detail($rid)
    {
        $this->load->library('form_validation');
        $_POST['rid'] = $rid;
        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('rid', 'Role', array('required', 'numeric', 'max_length[3]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('Role_model', 'rm');
            $data['form']['where']['role_id'] = set_value('rid');
            $data['role_list']['where']['role'] = set_value('rid');
            if($this->rm->getRoleBy($data['form']) > 0){
                $output['success'] = 1;
                $output['data']['role'] = $this->rm->result;
                $output['data']['access'] = $this->rm->getAccessListID($data['role_list']);
            }else{
                $output['success'] = 0;
                $output['message'] = 'Failed to create user';
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
        $this->form_validation->set_rules('role_name', 'Role Name', array('trim', 'required', 'alpha_numeric_spaces', 'max_length[50]'));
        $this->form_validation->set_rules('permission_number[]', 'Permission', array('numeric', 'max_length[3]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('Role_model');
            $data['form']['roles']['role_name'] = set_value('role_name');
            $data['form']['permission'] = set_value('permission_number');
            if($this->Role_model->createRole($data['form']) > 0){
                $output['success'] = 1;
            }else{
                $output['success'] = 0;
                $output['message'] = 'Failed to create user';
            }
        }
        $output['response'] = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        return $output;

    }

    private function doModify($post)
    {
        $_POST = $post;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_id', 'Role Identifier', array('required', 'numeric', 'max_length[3]'));
        $this->form_validation->set_rules('role_name', 'Role Name', array('trim', 'required', 'alpha_numeric_spaces', 'max_length[50]'));
        $this->form_validation->set_rules('permission_number[]', 'Permission', array('numeric', 'max_length[3]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('Role_model');
            $data['form']['roles']['role_name'] = set_value('role_name');
            $data['form']['where']['role_id'] = set_value('role_id');
            $data['form']['where_role']['role'] = set_value('role_id');
            $data['form']['permission'] = set_value('permission_number');
            if($this->Role_model->modifyRole($data['form']) > 0){
                $output['success'] = 1;
            }else{
                $output['success'] = 0;
                $output['message'] = 'Failed to modify user';
            }
        }
        $output['response'] = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        return $output;

    }

}
