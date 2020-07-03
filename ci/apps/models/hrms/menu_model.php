<?php

class Menu_Model extends SISTER_Model
{
    public $_table_name;
    public $_order_by;
    public $_primary_key;
   
    public function getAllMenu()
    {
        $q = $this->db->from('tbl_menu');
        $q->where('active', 1);
        return $q->get()->result_array();
    }
    
    public function getBreadcrumb($menu)
    {
        $q = $this->db->from('tbl_menu');
        $q->where('menu_id', $menu);
        return $q->get()->result_array();
    }
    
}