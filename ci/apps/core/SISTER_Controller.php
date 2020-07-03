<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SISTER_Controller extends CI_Controller
{
    
    public function __construct($type = 'sister', $check = 'url')
    {
        parent::__construct();
        
        if(ENVIRONMENT == 'development'){
            $this->output->enable_profiler(TRUE);
        }
        $this->load->library('session');
        $this->load->helper(['url','cookie']);
        $this->load->model('SisterController_model', 'SISTER');
        
        if($type == 'sister'){
            $this->isSister($check);
        }if($type == 'HRMS'){
            $this->isHRMS($check);
        }
    }
    
    protected function checkAccess()
    {
        if(!empty($this->skipCheck) && in_array($this->router->method, $this->skipCheck)){
            return true;
            
        }else{
            if(is_array($this->SISTER->checkByURL($this->session->userdata('role_id'), $this->uri->uri_string()))){
                return true;
            }else{
                return false;
            }
        }
    }
    
    protected function isSister($check)
    {
        if(!$this->checkAccess()){
            $data['content']['page'] = 'Error';
            $data['content']['session'] = $this->session->userdata();
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
        
        if($this->session->isLoggedIn() && empty($this->session->userdata('menu'))){
            $this->session->set_userdata('menu', $this->SISTER->getMainMenu($this->session->userdata('role_id'),$this->session->userdata('site')));
        }
    }
    
    protected function isHRMS($check)
    {
        $this->config->load('config', TRUE);
        if(is_array($this->SISTER->checkByCode($this->session->userdata('role_id'), $this->config->item('hrms_addon', 'config'), 'isAdmin'))){
            $this->load->model('hrms/login_model');
            $this->load->library(['form_validation']);
            $this->load->helper(['form']);
            $this->load->model('hrms/admin_model');
            $this->load->model('hrms/global_model');
                // check notififation status by where
            $where = array('notify_me' => '1', 'view_status' => '2');
            // check email notification status
            $this->admin_model->_table_name = 'tbl_inbox';
            $this->admin_model->_order_by = 'inbox_id';
            $data['total_email_notification'] = count($this->admin_model->get_by($where, FALSE));
            $data['email_notification'] = $this->admin_model->get_by($where, FALSE);
            
            // check notice notification status
            $this->admin_model->_table_name = 'tbl_notice';
            $this->admin_model->_order_by = 'notice_id';
            $data['total_notice_notification'] = count($this->admin_model->get_by($where, FALSE));

            $data['notice_notification'] = $this->admin_model->get_by($where, FALSE);

            // check leave notification status
            $this->admin_model->_table_name = 'tbl_application_list';
            $this->admin_model->_order_by = 'application_list_id';
            $data['total_leave_notifation'] = count($this->admin_model->get_by($where, FALSE));
            $data['leave_notification'] = $this->admin_model->get_emp_leave_info();
            // set all data into session 
            $_SESSION['notify'] = $data;
            
            // get all general settings info
            $this->admin_model->_table_name = "tbl_gsettings"; //table name
            $this->admin_model->_order_by = "id_gsettings";
            $info['genaral_info'] = $this->admin_model->get();
            $this->session->set_userdata($info);
        }else{
            exit('cannot access hrms');
        }
        

    }
    


}
