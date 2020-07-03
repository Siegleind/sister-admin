<?php

class Language
{

    public $menu_model;
    
    public function form_heading()
    {
        
        #$ginfo = $CI->session->userdata('genaral_info');
        $active_language = 'english';
        
        $CI = & get_instance();
        $CI->load->model('hrms/form_model');
        // get active language info
        $form_info = $CI->form_model->getAllForm();

        foreach ($form_info as $current_laguage) {
            $language[] = $current_laguage;
        }
        return $result = $this->active_language($language, $active_language);
    }

    public function active_language($language, $active_laguage) {

        foreach ($language as $v_language) {
            $lang[] = $v_language[$active_laguage];
        }
        return $lang;
    }

    public function from_body() {
        $CI = & get_instance();
        $CI->load->model('hrms/form_model');
        #$ginfo = $CI->session->userdata('genaral_info');
        $active_laguage = 'English';
        // get active language info
        $form_info = $CI->form_model->getAllForm();
        foreach ($form_info as $form_id) {
            $id[] = $form_id;
        }
        foreach ($id as $v_id) {
            $f_id = $v_id['form_id'];
            $user_menu = $CI->form_model->db->query("SELECT tbl_form.*,tbl_form_body.*
                                        FROM tbl_form_body
                                        INNER JOIN tbl_form
                                        ON tbl_form_body.form_id=tbl_form.form_id
                                        WHERE tbl_form_body.form_id=$f_id
                                        ORDER BY form_body_id;")->result_array();
                                        
                                        
            foreach ($user_menu as $form_data) {
                
                $label[$f_id][] = $form_data[$active_laguage];
            }
        }
        return $label;
    }

}
