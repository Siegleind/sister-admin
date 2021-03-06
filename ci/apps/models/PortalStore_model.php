<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PortalStore_model extends CI_Model
{
    public $result;

    public function show($opt, $where=array(), $like=array())
    {
        //$query = $this->db->from('portal_store');
        $query = $this->db->select('store_id,store_name,type_name AS store_type,store_email,store_status')->from('portal_store')->join('store_type', 'store_type.type_id=portal_store.store_type','left');
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
            $this->result['total'] = $this->countStore($opt, $where, $like);
        }else{
            $this->result['total'] = 0 ;
        }
        
        return $this->result;
    }
    
    public function countStore($opt, $where=array(), $like=array())
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
        $this->db->update('portal_store', $input['sites'], $input['where']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }

    public function showStore($data)
    {
        $query = $this->db->from('portal_store');
        $query->where($data['where']);

        #foreach($query->get()->result_array() AS $row){
        #    $this->result['data'][] = array($row['user_id'], $row['email'], $row['display_name'], $row['status'], $row['group_name'], $row['store_name']);
        #}
        $this->result = $query->get()->row_array();

        return $this->result;
    }
}