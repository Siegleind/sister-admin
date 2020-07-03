<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SISTER_Session extends CI_SESSION
{
    public $session_id;
    
    public function __construct()
    {
        $this->regenerateID();
        $time = time();
        $expired = (isset($_SESSION['remember']) && $_SESSION['remember'] == 1 ? 60*60*24*30 : 1200 );
        /* generate session time */
        
        if(isset($_SESSION['__last_generate']) && $time > $_SESSION['__last_generate']+$expired){
            $this->regenerateID(1);
        }else{
            $_SESSION['__last_generate'] = $time;
        }
    }
    
    public function userdata($name = false) {
        if(!$name){
            if(isset($_SESSION)) {
                return $_SESSION;
            }else{
                return 0;
            }
        }else{
            if(isset($_SESSION[$name])) {
                return $_SESSION[$name];
            }else{
                return 0;
            }
        }
    }
    
    public function set_userdata($name, $value = 0)
    {
        if(!$value && is_array($name)){
            foreach($name as $key => $val){
                $_SESSION[$key] = $val;
            }
        }else{
            if(is_array($value)){
                foreach($value as $key => $val){
                    $_SESSION[$name][$key] = $val;
                }
            }else{
                $_SESSION[$name] = $value;
            }
        }
        
    }
    
    public function unset_userdata($name)
    {
        if(is_array(isset($_SESSION[$name]))) {
            for($i=0; $i < count($name); $i++){
                unset($_SESSION[$name[$i]]);
            }
        }else{
            if(isset($_SESSION[$name])) unset($_SESSION[$name]);
        }
    }
    
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
    
    public function sess_destroy()
	{
		session_destroy();
	}
    
    function regenerateID($force = 0, $gc = 0, $lifetime = 0)
    {
        // Call session_create_id() while session is active to 
        // make sure collision free.
        if (session_status() !== PHP_SESSION_ACTIVE && $force === 0) {
            session_start();
        }
        if($force === 1 || session_status() === PHP_SESSION_NONE){
            
            // WARNING: Never use confidential strings for prefix!
            $time = time();
            $newid = hash('ripemd160', "portalsushitei2019-{$_SERVER['REMOTE_ADDR']}{$time}");
            $this->session_id = $newid;
            // Set deleted timestamp. Session data must not be deleted immediately for reasons.
            $_SESSION['__last_generate'] = $time;
            // Finish session
            session_commit();
            // Make sure to accept user defined session ID
            // NOTE: You must enable use_strict_mode for normal operations.
            ini_set('session.use_strict_mode', 0);
            // Set new custom session ID
            session_id($newid);
            // Start with custom session ID
            if($gc){
                ini_set('session.gc_maxlifetime', $lifetime);
                session_start(['cookie_lifetime' => $lifetime]);
            }else{
                session_start();
            }
        }
        
    }

}
