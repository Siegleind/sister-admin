<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SISTER_Controller extends CI_Controller
{
    public $state;
    public $current_uri;
    public function __construct()
    {
        parent::__construct();
        if(ENVIRONMENT == 'development'){
            $this->output->enable_profiler(TRUE);
        }
        $this->load->library('session');
        $this->state = $this->session->userdata();
        $this->load->helper(['url','cookie']);
        $this->load->model('SisterController_model', 'SISTER');
        if(!$this->checkAccess()){
            $data['content']['page'] = 'Error';
            $data['content']['session'] = $this->state;
            if(!empty($this->onUnauthorized) && is_array($this->onUnauthorized)){
                switch($this->onUnauthorized[0]){
                    case 'view':
                        $data['body']['content'] = $this->load->view($this->onUnauthorized[1], $data['content'], TRUE);
                        exit($this->load->view('portal/templates/sufee/template', $data['body'], TRUE));
                        break;
                    default:
                        redirect($this->onUnauthorized[1]);
                }
            }else{
                $data['body']['content'] = $this->load->view('errors/custom_unauthorized', $data['content'], TRUE);
                exit($this->load->view('portal/templates/sufee/template', $data['body'], TRUE));
            }
        }else{
            set_cookie('last_url', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '3600'); 
        }
        
        if($this->session->isLoggedIn() && empty($this->state['menu'])){
            $this->session->set_userdata('menu', $this->SISTER->getMainMenu($this->state['site']));
        }
    }
    
    protected function checkAccess()
    {
        if(!empty($this->skipCheck) && in_array($this->router->method, $this->skipCheck)){
            return true;
            
        }else{
            if(is_array($this->SISTER->check($this->state['role_id'], $this->uri->uri_string()))){
                
                return true;
            }else{
                return false;
            }
        }
    }
    


}
