<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model 
{
    public $result;

    public function doLogin($input)
    {
        $query = $this->db->select('user_id, display_name AS user_name, role_name AS group, role_id, email, store_id AS outlet, user_password, gm_email, gm_name, gm_position')
        ->from('portal_user')
        ->join('user_role', 'role_id', 'left')
        ->join('staff_department', 'department_id', 'left')
        ->where('email', $input['ticket_email'])
        ->where('role_id !=', 9)
        ->where('user_status', 1);
        $this->result = $query->get()->row_array();
        if(password_verify($input['ticket_password'], $this->result['user_password'])){
            unset($this->result['user_password']);
            return $this->result;
        }else{
            /*
            #$this->db->update('portal_user', array('user_password' => password_hash($input['ticket_password'], PASSWORD_BCRYPT, array('cost' => 9))), array('user_id' => $this->result['user_id']));
            $q = $this->db->select('user_id, display_name AS user_name, role_name AS group, role_id, email, store_id AS outlet, user_password, gm_email, gm_name, gm_position')
            ->from('portal_user')
            ->join('user_role', 'role_id', 'left')
            ->join('staff_department', 'department_id', 'left')
            ->where('email', $input['ticket_email'])
            ->where('role_id !=', 9)
            ->where('user_status', 1)->get_compiled_select();            
            return $s;
            */
            return false;
        }
        
    }
    
    function getAllowedSite($input)
    {
        $query = $this->db->select('site_id')
        ->from('portal_permission pp')
        ->join('role_permission rp', 'rp.permission=pp.permission_id', 'left')
        ->join('portal_sites ps', 'ps.site_id=pp.permission_site', 'left')
        ->where('rp.role', $input)
        ->where('pp.permission_type', 1)
        ->where('pp.permission_code', 'siteAccess');
        return $query->get()->result_array();
        
    }
    
    public function showPaged($opt, $where=array(), $like=array())
    {
        $query = $this->db->select('user_id,email,display_name,IF(user_status = 1, "Active", "Disabled") AS status,role_name AS group_name,store_name')->from('portal_user')->join('user_role', 'role_id','left')->join('portal_store', 'store_id','left')->where($where)->like($like);
        foreach($opt['order'] as $name => $direction){
            $query->order_by($name, $direction);
        }
        if(isset($opt['page']) && $opt['page'] > 1){
            $query->limit($opt['result'],($opt['page']-1)*$opt['result']);
        }else{
            if(isset($opt['result'])) $query->limit($opt['result'], 0);
        }
        
        #foreach($query->get()->result_array() AS $row){
        #    $this->result['data'][] = array($row['user_id'], $row['email'], $row['display_name'], $row['status'], $row['group_name'], $row['store_name']);
        #}
        $this->result['data'] = $query->get()->result_array();
        if(count($this->result['data']) > 0){
            $this->result['total'] = $this->getUserCount($opt, $where, $like);
        }else{
            $this->result['total'] = 0 ;
        }
        
        
        return $this->result;
    }
    
    public function getUserCount($opt, $where=array(), $like=array())
    {
        $this->db->from('portal_user');
        $this->db->where($where);
        $this->db->like($like);
        foreach($opt['order'] as $name => $direction){
            $this->db->order_by($name, $direction);
        }
        return $this->db->count_all_results();
    }

    public function getUserInfo($where, $select='')
    {
        if(!empty($select)) $this->db->select($select);
        
        $this->db->from('portal_user')
        ->join('user_role', 'role_id', 'left')
        ->join('portal_store', 'store_id', 'left')
        ->where($where);
        $this->result =$this->db->get()->row_array();
        return true;
    }
    
    public function createUser($input)
    {
        $this->db->insert('portal_user', $input);
        return $this->db->affected_rows();
    }

    public function modifyUser($input)
    {
        $this->db->update('portal_user', $input['users'], $input['where']);

        if ($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    
}