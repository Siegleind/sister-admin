<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends CI_Model
{
    public $result;

    public function getStorePaged($opt, $where=array(), $like=array())
    {
        $query = $this->db->from('portal_store');
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
            $this->result['total'] = $this->getStoreCount($opt, $where, $like);
        }else{
            $this->result['total'] = 0 ;
        }
        
        
        return $this->result;
    }
    
    public function getStore($select = 0, $where = 0, $opt = 0)
    {
        $query = $this->db->from('portal_store');
        
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
    
    
    public function getStoreCount($opt, $where=array(), $like=array())
    {
        $this->db->from('portal_store');
        $this->db->where($where);
        $this->db->like($like);
        foreach($opt['order'] as $name => $direction){
            $this->db->order_by($name, $direction);
        }
        return $this->db->count_all_results();
    }

    public function createStore($input)
    {
        $this->db->trans_start();
        $this->db->insert('portal_store', $input['stores']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }

    public function modifyStore($input)
    {
        $this->db->trans_start();
        $this->db->update('portal_store', $input['stores'], $input['where']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }


    public function getStoreBy($data)
    {
        $query = $this->db->from('portal_store');
        $query->where($data['where']);

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