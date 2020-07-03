<?php

class Form_Model extends SISTER_Model
{
    public $_table_name;
    public $_order_by;
    public $_primary_key;
   
    public function getAllForm($lang = 'english')
    {
        $q = $this->db->from('tbl_form');
        $q->select("form_id,{$lang}")->order_by('form_id', 'ASC');
        return $q->get()->result_array();
    }
   
    
}