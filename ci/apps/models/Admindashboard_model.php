<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admindashboard_model extends CI_Model 
{
    public $result;

    public function getContent()
    {
        $query = $this->db->select('"User" as content_type, COUNT(user_id) as total')->from('portal_user')->get();
        return($query->row_array());
        
    }
    
    
}