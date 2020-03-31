<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SISTER_Controller extends CI_Controller
{
    public $state;
    public $current_uri;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->state = $this->session->userdata();
        
        $this->load->helper(array('url','cookie'));
        $this->load->model('SisterController_model', 'SISTER');
        if(!$this->checkAccess()){
            if(!empty($this->onUnauthorized) && is_array($this->onUnauthorized)){
                switch($this->onUnauthorized[0]){
                    case 'view':
                        $data['content']['page'] = 'Error';
                        $data['content']['session'] = $this->state;
                        $data['body']['content'] = $this->load->view($this->onUnauthorized[1], $data['content'], TRUE);
                        exit($this->load->view('portal/templates/sufee/template', $data['body'], TRUE));
                        break;
                    default:
                        redirect($this->onUnauthorized[1]);
                        exit;
                }
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
        if(!empty($this->skipCheck) && !in_array($this->router->fetch_method(), $this->skipCheck)){
            if(is_array($this->SISTER->check($this->state['role_id'], uri_string()))){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 1;
        }
    }
    


}
