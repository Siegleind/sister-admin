<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SISTER_Session extends CI_SESSION
{
    public function isLoggedIn($redirect = false, $goto = 'user/login')
    {
        if(empty($this->userdata('logged_in')) || $this->userdata('logged_in') == 0)
        {
            if($redirect){
                header("Location: ". config_item('base_url'). $goto);
                exit();
            }

            return $this->userdata('logged_in');
        }else{
            return true;
        }    
    }

}
