<?php

class Breadcrumbs {

    public function build_breadcrumbs() {

        $CI = & get_instance();
        $id = $CI->session->userdata('menu_active_id');
        $breadcrumbs = "";
        if(is_array($id)){
            $menu_id=  array_reverse($id);            
            $CI->load->model('hrms/menu_model');
            foreach ($menu_id as $v_id) {
                
                foreach($CI->menu_model->getBreadcrumb($v_id) AS $item){
                   
                    $breadcrumbs .= "<li>\n  <a href='" . base_url() . $items['link'] . "'>" . $items['label'] . "</a>\n</li> \n";

                }
            }
        }
        
        return $breadcrumbs;
    }

}
