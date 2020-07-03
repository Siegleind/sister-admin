<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Controller 
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
	
	public function list_paged($id) 
    {
        $this->load->model('PortalPermission_model', 'ppm');
        $this->load->helper('url');
        
        $data['option'] = array(
            #'result' => 10,
            #'page' => $_POST['page'],
            'order' => array('display_name' => 'ASC')
        );
        $data['content'] = $this->ppm->showPaged($data['option'], ['permission_site' => $id]);
        if($data['content']['total'] > 0){
            $this->output->set_content_type('application/json')->set_output(json_encode($data['content'],JSON_NUMERIC_CHECK));
        }
        
	}

    public function form()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('action_mode') == 'create') {
                $this->output->set_content_type('application/json')->set_output(json_encode($this->doCreate($_POST), JSON_NUMERIC_CHECK));
            } elseif ($this->input->post('action_mode') == 'modify') {
                $this->output->set_content_type('application/json')->set_output(json_encode($this->doModify($_POST), JSON_NUMERIC_CHECK));
            }
        }
    }

    public function doCreate($post)
    {
            $_POST = $post;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', array('trim', 'required', 'valid_email', 'max_length[50]', 'is_unique[portal_user.email]'));
            $this->form_validation->set_rules('display_name', 'Display Name', array('trim', 'required', 'alpha_numeric_spaces', 'max_length[50]'));
            $this->form_validation->set_rules('new_password', 'Password', array('required'));
            $this->form_validation->set_rules('confirm_password', 'Password Confirmation', array('required','matches[new_password]'));
            $this->form_validation->set_rules('group', 'User Type', array('trim', 'required', 'numeric', 'max_length[3]'));
            $this->form_validation->set_rules('status', 'User Status', array('trim', 'required', 'numeric', 'max_length[1]'));
            $this->form_validation->set_rules('outlet', 'Store Name', array('trim', 'required', 'numeric', 'max_length[3]'));
            if ($this->form_validation->run() == FALSE){
                $input['success'] = 0;
                $input['error'] = $this->form_validation->error_array();
            }else{
                $this->load->model('User_model');
                $data['form']['email'] = set_value('email');
                $data['form']['user_name'] = set_value('email');
                $data['form']['display_name'] = set_value('display_name');
                $data['form']['user_password'] = password_hash(set_value('new_password'), PASSWORD_BCRYPT, array('cost' => 9));
                $data['form']['role_id'] = set_value('group');
                $data['form']['user_status'] = set_value('status');
                $data['form']['store_id'] = set_value('outlet');
                if($this->User_model->createUser($data['form']) > 0){
                    $input['success'] = 1;
                    $input['form'] = array(
                        'user_id' => $this->User_model->db->insert_id(),
                        'email' => set_value('email'),
                        'display_name' => set_value('display_name'),
                        'status' => 'Active',
                        'group_name' => 'Group',
                        'store_name' => 'Store'
                    );
                    #$this->User_model->getUserInfo(array('user_id' => $this->User_model->db->insert_id()),'user_id,email,display_name,IF(user_status = 1, "Active", "Disabled") AS status,role_name AS group_name,store_name');
                }else{
                    $input['success'] = 0;
                    if($this->db->error()['code'] == 1062)  $input['message'] = 'Email sudah terdaftar';
                }
            }
            $input['response'] = array(
                'csrfName' => $this->security->get_csrf_token_name(),
                'csrfHash' => $this->security->get_csrf_hash()
            );

            return $input;
    }

    private function doModify($post)
    {
        $_POST = $post;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User', array('required', 'numeric', 'max_length[6]'));
        $this->form_validation->set_rules('email', 'Email', array('trim', 'required', 'valid_email', 'max_length[50]'));
        $this->form_validation->set_rules('display_name', 'Display Name', array('trim', 'required', 'alpha_numeric_spaces', 'max_length[50]'));
        if($this->input->post('new_password')){
            $this->form_validation->set_rules('new_password', 'Password', array('required'));
            $this->form_validation->set_rules('confirm_password', 'Password Confirmation', array('required','matches[new_password]'));
        }
        $this->form_validation->set_rules('group', 'User Type', array('trim', 'required', 'numeric', 'max_length[3]'));
        $this->form_validation->set_rules('status', 'User Status', array('trim', 'required', 'numeric', 'max_length[1]'));
        $this->form_validation->set_rules('outlet', 'Store Name', array('trim', 'required', 'numeric', 'max_length[3]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('User_model', 'um');
            $data['form']['users']['email'] = set_value('email');
            $data['form']['users']['user_name'] = set_value('email');
            $data['form']['users']['display_name'] = set_value('display_name');
            if($this->input->post('new_password')){
                $data['form']['users']['user_password'] = password_hash(set_value('new_password'), PASSWORD_BCRYPT, array('cost' => 9));
            }
            $data['form']['users']['role_id'] = set_value('group');
            $data['form']['users']['user_status'] = set_value('status');
            $data['form']['users']['store_id'] = set_value('outlet');
            $data['form']['where']['user_id'] = set_value('user_id');
            if($this->um->modifyUser($data['form']) > 0){
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

    public function detail($rid)
    {
        $this->load->library('form_validation');
        $_POST['rid'] = $rid;
        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('rid', 'User', array('required', 'numeric', 'max_length[5]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('User_model', 'um');
            $data['form']['user_id'] = set_value('rid');
            if($this->um->getUserInfo($data['form'], 'portal_user.store_id, email, user_id, display_name, role_id, user_status')){
                $output['success'] = 1;
                $output['data'] = $this->um->result;
            }else{
                $output['success'] = 0;
                $output['message'] = 'Failed to modify user';
            }
        }
        $output['response'] = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($output, JSON_NUMERIC_CHECK));

    }


}
