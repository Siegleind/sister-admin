<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PortalPermission_model extends CI_Model
{
    public $result;
    
    public function get($select = 0, $where = 0, $opt = 0)
    {
        $query = $this->db->from('portal_permission pp')
        ->join('portal_sites ps', 'ps.site_id=pp.permission_site', 'left');
        
        if($select){
            $query->select($select);
        }
        
        if(!empty($where)){
            $query->where($where);
        }
        
        if(!empty($opt['order'])){
            foreach($opt['order'] as $name => $direction){
                $query->order_by($name, $direction);
            }
        }
        
        return [
            'data' => $query->get()->result_array(),
            'total' => $this->db->count_all_results()
        ];
    }

    public function create($input)
    {
        $this->db->trans_start();
        $this->db->insert('portal_permission', $input['form']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }

    public function modify($input)
    {
        $this->db->trans_start();
        $this->db->update('portal_permission', $input['form'], $input['where']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return 0;
        }else{
            return 1;
        }
    }


    public function detail($where, $select='')
    {
        if(!empty($select)) $this->db->select($select);
        
        $this->db->from('portal_permission pp')
        ->join('portal_sites ps', 'ps.site_id=pp.permission_site', 'left')
        ->where($where);
        $this->result =$this->db->get()->row_array();
        return true;
    }
}