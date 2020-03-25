<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PortalPermission_model extends CI_Model
{
    public $result;
    
    public function selectPermission($d = [])
    {
        $query = $this->db->from('portal_permission');
        $query->where($d);
        $this->result['data'] = $query->get()->result_array();
        
        return $this->result;
    }
    
    public function showPaged($opt, $where=[], $like=[])
    {
        $query = $this->db->from('portal_permission');
        if(count($where) > 0){
            foreach($where as $w){
                $query->where($w);
            }
        }
        
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
            $this->result['total'] = $this->countSite($opt, $where, $like);
        }else{
            $this->result['total'] = 0 ;
        }
        
        
        return $this->result;
    }
    
    public function countSite($opt, $where=array(), $like=array())
    {
        $this->db->from('permission');
        $this->db->where($where);
        $this->db->like($like);
        foreach($opt['order'] as $name => $direction){
            $this->db->order_by($name, $direction);
        }
        return $this->db->count_all_results();
    }

    public function grantPermission($input)
    {
        $this->db->trans_start();
        $this->db->insert('portal_sites', $input['sites']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }

    public function modifySite($input)
    {
        $this->db->trans_start();
        $this->db->update('portal_sites', $input['sites'], $input['where']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }

    public function showSite($data)
    {
        $query = $this->db->from('portal_sites');
        $query->where($data['where']);

        #foreach($query->get()->result_array() AS $row){
        #    $this->result['data'][] = array($row['user_id'], $row['email'], $row['display_name'], $row['status'], $row['group_name'], $row['store_name']);
        #}
        $this->result = $query->get()->row_array();

        return $this->result;
    }
}