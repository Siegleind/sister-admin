<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends SISTER_Controller 
{
    /**
    ==========================

    Dibawah Ini Publik Class

    ==========================
    **/

    public $skipCheck = array('login','logout');
    public $success_goto = '';
    public $loggedin = 0;

    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('url');
        $this->success_goto = base_url();
    }
	
	public function login($login = false) 
	{
        if($login == 'do'){
            $this->output->set_content_type('application/json')->set_output(json_encode($this->loginProccess()));
        }else{
            $this->load->helper('url');
            if($this->session->isLoggedIn()) redirect($this->success_goto, 'refresh');
            #echo password_hash("fangblade_99", PASSWORD_BCRYPT, array('cost' => 9));
            $data['session'] = $this->session->userdata();
            $this->load->helper(array('html','url','form'));
            $this->load->view('portal/contents/user/login', $data);
        }
        
	}

    public function logout() 
    {
        if($this->session->isLoggedIn(true)){
            $this->session->sess_destroy();
            $this->session->regenerateID();
            $this->load->helper('url');
            redirect('user/login', 'refresh');
        }
    }

    public function register() 
    {
        if($this->isLoggedIn()) redirect($this->success_goto, 'refresh');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->registerProccess()));
        
    }
    /**
    ==========================

    Dibawah Ini Private Class

    ==========================
    **/


    private function loginProccess()
    {
        $return = ['success' => 0];
        if(!$this->session->isLoggedIn()){
            $attempt = $this->session->userdata('attempt');
            $recaptcha = 1;
            if($attempt > 5){
                $opts = ['http' =>
                    [
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => http_build_query(
                            [
                                'secret' => '6LfvL5YUAAAAADiCFALDW1Fx1OmuN5yHe9BptoB-',
                                'response' => $this->input->post('g-recaptcha-response'),
                                'remoteip' => $_SERVER['REMOTE_ADDR']
                            ]
                        )
                    ]
                ];
                $context  = stream_context_create($opts);
                $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
                $result = json_decode($response);
                if (!$result->success) {
                    $recaptcha = 0;
                }
            }
            $attempt++;
            $return['attempt'] = $attempt;

            $this->session->set_userdata(array('attempt' => $attempt));

            if($this->input->post('ticket_email') && $this->input->post('ticket_password') && $recaptcha){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('ticket_email', 'Email', 'trim|required|min_length[4]|max_length[100]|valid_email');
                $this->form_validation->set_rules('ticket_password', 'Password', 'required');
                if ($this->form_validation->run() == false) {
                    $return['error'] = $this->form_validation->error_array();
                } else {
                    $this->load->model('User_model', '', TRUE);
                    $input = array(
                        'ticket_email' => $this->input->post('ticket_email'),
                        'ticket_password' => $this->input->post('ticket_password')
                    );

                    #$return['error']['ticket_email'] = $this->User_model->doLogin($input);
                    if(is_array($this->User_model->doLogin($input))){
                        $return['message'] = "Logged in Successfully.";
                        $return['success'] = true;
                        $ses = $this->User_model->result;
                        $ses['logged_in'] = true;
                        $ses['attempt'] = false;
                        
                        foreach($this->User_model->getAllowedSite($ses['role_id']) as $site){
                            $ses['site'][$site['site_id']] = 1;
                        }
                        
                        
                        if($this->input->post('remember')){
                            $to = 60*60*24*30;
                            $this->session->regenerateID(1, 1, $to);
                            $ses['remember'] = true;
                        }else{
                            $this->session->regenerateID(1);
                        }
                        $this->load->model('SisterController_model', 'SISTER');
                        $this->session->set_userdata($ses);
                        $this->session->set_userdata('menu', $this->SISTER->getMainMenu($this->session->userdata('site')));
                        session_write_close();
                    }else{
                        #$return['test'] = $this->User_model->doLogin($input);
                        $return['error']['ticket_email'] = 'Username or Password does not match.';
                    }
                    
                }
                
            
            } else {
                $return['data'] = $_POST;
            }
        
        }else{
            $return['already_login'] = $this->session->userdata();
            $return['success'] = true;
        }
        $return['url'] = base_url();
        $return['response'] = [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        ];
        session_write_close();
        return $return;
    }

}
