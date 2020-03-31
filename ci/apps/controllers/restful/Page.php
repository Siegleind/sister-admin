<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller 
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
	
	public function get() 
	{
        $this->load->model('Page_model', 'pm');
        $this->load->helper('url');
        
        $data['content'] = $this->pm->get('page_id, page_title, page_content, page_group, page_type, page_status, "'.base_url().'page/view/" AS page_domain, page_url',['page_status !=' => '0'],['order' => ['page_title' => 'ASC']]);
        if($data['content']['total'] > 0){
            $this->output->set_content_type('application/json')->set_output(json_encode($data['content'],JSON_NUMERIC_CHECK));
        }
        
	}

    public function form()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('mode') == 'create') {
                $this->output->set_content_type('application/json')->set_output(json_encode($this->doCreate($_POST), JSON_NUMERIC_CHECK));
            } elseif ($this->input->post('mode') == 'modify') {
                $this->output->set_content_type('application/json')->set_output(json_encode($this->doModify($_POST), JSON_NUMERIC_CHECK));
            }
        }
    }

    private function doCreate($post)
    {
        $_POST = $post;
        $_POST['slug_url'] = $this->createSlug($_POST['title']);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('slug_url', 'Title', array('trim', 'required', 'max_length[100]', 'is_unique[portal_page.page_url]'));
        $this->form_validation->set_rules('title', 'Title', array('trim', 'required', 'alpha_numeric_spaces', 'max_length[50]'));
        $this->form_validation->set_rules('content', 'Content', array('required'));
        $this->form_validation->set_rules('group', 'Group', array('trim', 'required', 'numeric', 'max_length[6]'));
        $this->form_validation->set_rules('type', 'Type', array('trim', 'required', 'numeric', 'max_length[1]'));
        $this->form_validation->set_rules('status', 'Status', array('trim', 'required', 'numeric', 'max_length[1]'));
        if ($this->form_validation->run() == FALSE){
            $input['success'] = 0;
            $input['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('Page_model', 'pm');
            $data['form']['page_title'] = set_value('title');
            $data['form']['page_url'] = set_value('slug_url');
            $data['form']['page_content'] = base64_encode(set_value('content','', false));
            $data['form']['page_group'] = set_value('group');
            $data['form']['page_type'] = set_value('type');
            $data['form']['page_status'] = set_value('status');
            if($this->pm->create($data)){
                $input['success'] = 1;
                $input['form'] = array(
                    'page_id' => $this->pm->db->insert_id(),
                    'page_title' => set_value('title'),
                    'page_content' => set_value('content'),
                    'page_group' => 'Group',
                    'page_type' => 'Type',
                    'store_name' => 'Store'
                );
                #$this->User_model->getUserInfo(array('user_id' => $this->User_model->db->insert_id()),'user_id,email,display_name,IF(user_status = 1, "Active", "Disabled") AS status,role_name AS group_name,store_name');
            }else{
                $input['success'] = 0;
                if($this->pm->db->error()['code'] == 1062)  $input['message'] = 'Email sudah terdaftar';
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
        $output['success'] = 0;
        $this->load->library('form_validation');
        $this->load->model('Page_model', 'pm');
        
        $this->form_validation->set_rules('form_id', 'Data', array('trim', 'required', 'numeric', 'max_length[6]'));
        
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $_POST['slug_url'] = $this->createSlug($_POST['title']);
            $detail = $this->pm->detail(['page_id' => set_value('form_id')]);
            $this->form_validation->reset_validation();
            if(!empty($detail['page_url']) && $detail['page_url'] != $_POST['slug_url']){
                $this->form_validation->set_rules('slug_url', 'Title', array('trim', 'required', 'max_length[100]', 'is_unique[portal_page.page_url]'));
                $data['form']['page_title'] = set_value('slug_url');
            }
                
            
            $this->form_validation->set_rules('title', 'Title', array('trim', 'required', 'alpha_numeric_spaces', 'max_length[50]'));
            $this->form_validation->set_rules('content', 'Content', array('required'));
            $this->form_validation->set_rules('group', 'Group', array('trim', 'required', 'numeric', 'max_length[6]'));
            $this->form_validation->set_rules('type', 'Type', array('trim', 'required', 'numeric', 'max_length[1]'));
            $this->form_validation->set_rules('status', 'Status', array('trim', 'required', 'numeric', 'max_length[1]'));
            if ($this->form_validation->run() == FALSE){
                $output['success'] = 0;
                $output['error'] = $this->form_validation->error_array();
            }else{
                $data['form']['page_title'] = set_value('title');
                $data['form']['page_content'] = base64_encode(set_value('content','', false));
                $data['form']['page_group'] = set_value('group');
                $data['form']['page_type'] = set_value('type');
                $data['form']['page_status'] = set_value('status');
                $data['where']['page_id'] = set_value('form_id');
                if($this->pm->modify($data)){
                    $output['success'] = 1;
                }else{
                    if($this->pm->cerr['code'] != 0){
                        $output['error'] = $this->um->cerr;
                    }else{
                        $output['success'] = 1;
                    }
                    
                }
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
        $this->form_validation->set_rules('rid', 'Page', array('required', 'numeric', 'max_length[5]'));
        if ($this->form_validation->run() == FALSE){
            $output['success'] = 0;
            $output['error'] = $this->form_validation->error_array();
        }else{
            $this->load->model('Page_model', 'pm');
            $data['form']['page_id'] = set_value('rid');
            if($this->pm->detail($data['form'], 'page_id, page_title, page_content, page_group, page_type, page_status')){
                $output['success'] = 1;
                $output['data'] = $this->pm->result;
            }else{
                $output['success'] = 0;
                $output['message'] = 'Cannot fetch data';
            }
        }
        $output['response'] = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($output, JSON_NUMERIC_CHECK));

    }
    
    public static function createSlug($str, $delimiter = '-')
    {

        $unwanted_array = ['ś'=>'s', 'ą' => 'a', 'ć' => 'c', 'ç' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ź' => 'z', 'ż' => 'z',
            'Ś'=>'s', 'Ą' => 'a', 'Ć' => 'c', 'Ç' => 'c', 'Ę' => 'e', 'Ł' => 'l', 'Ń' => 'n', 'Ó' => 'o', 'Ź' => 'z', 'Ż' => 'z']; // Polish letters for example
        $str = strtr( $str, $unwanted_array );

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    }

}
