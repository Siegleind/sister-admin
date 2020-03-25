<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model
{
    public $result;

    public function getRolePaged($opt, $where=array(), $like=array())
    {
        $query = $this->db->from('user_role');
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
            $this->result['total'] = $this->getRoleCount($opt, $where, $like);
        }else{
            $this->result['total'] = 0 ;
        }
        
        
        return $this->result;
    }
    
    public function getRole($select = 0, $where = 0, $opt = 0)
    {
        $query = $this->db->from('user_role');
        
        if($select){
            $query->select($select);
        }
        
        if(is_array($where)){
            foreach($where as $name => $value){
                $query->where($name, $value);
            }
        }
        
        if(isset($opt['order'])){
            foreach($opt['order'] as $name => $direction){
                $query->order_by($name, $direction);
            }
        }
        
        
        return $query->get()->result_array();
    }
    
    
    public function getRoleCount($opt, $where=array(), $like=array())
    {
        $this->db->from('user_role');
        $this->db->where($where);
        $this->db->like($like);
        foreach($opt['order'] as $name => $direction){
            $this->db->order_by($name, $direction);
        }
        return $this->db->count_all_results();
    }

    public function createRole($input)
    {
        $this->db->trans_start();
        $this->db->insert('user_role', $input['roles']);
        $id = $this->db->insert_id();
        foreach($input['permission'] as $permission){
            $this->db->insert('role_permission', array('role' => $id, 'permission' => $permission));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }

    public function modifyRole($input)
    {
        $this->db->trans_start();
        $this->db->update('user_role', $input['roles'], $input['where']);
        $query2 = $this->db->where($input['where_role']);
        $query2->delete('role_permission');
        if(is_array($input['permission'])){
            foreach($input['permission'] as $permission){
                $this->db->insert('role_permission', array('role' => $input['where_role']['role'], 'permission' => $permission));
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }


    public function getRoleAccess($gid)
    {
        $query = $this->db->select('pp.permission_id,pp.permission_site,ps.site_name,ps.site_icon,pp.permission_order,pp.permission_name,pp.permission_url')
            ->from('role_permission rp')
            ->join('portal_permission pp', 'rp.permission=pp.permission_id')
            ->join('portal_sites ps', 'ps.site_id=pp.permission_site ')
            ->where('rp.role', $gid)
            ->order_by('pp.permission_site,pp.permission_order,pp.permission_name', 'ASC')->get();
        $this->result = $query->result_array();
        return $this->result;

    }

    public function getAccessList()
    {
        $query = $this->db->select('pp.permission_id,pp.permission_site,pp.permission_site AS site_id, ps.site_name,ps.site_icon,pp.permission_order,pp.permission_name,pp.permission_description')
            #->from('role_permission rp')
            #->join('portal_permission pp', 'rp.permission=pp.permission_id')
            ->from('portal_permission pp')
            ->join('portal_sites ps', 'ps.site_id=pp.permission_site')
            ->order_by('pp.permission_site,pp.permission_order,pp.permission_name', 'ASC')->get();
        $this->result = $query->result_array();
        return $this->result;
    }

    public function getRoleBy($data)
    {
        $query = $this->db->from('user_role');
        $query->where($data['where']);

        #foreach($query->get()->result_array() AS $row){
        #    $this->result['data'][] = array($row['user_id'], $row['email'], $row['display_name'], $row['status'], $row['group_name'], $row['store_name']);
        #}
        $this->result = $query->get()->row_array();

        return $this->result;
    }
    
    public function getAccessListID($data)
    {
        $query = $this->db->select('permission')
            #->from('role_permission rp')
            #->join('portal_permission pp', 'rp.permission=pp.permission_id')
            ->from('role_permission')
            ->where($data['where'])->get();
        return $query->result_array();
    }
}