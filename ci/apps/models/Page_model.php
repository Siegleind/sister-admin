<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends CI_Model
{
    public $result;

    public function paged($opt, $where=array(), $like=array())
    {
        $query = $this->db->from('portal_page');
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
            $this->result['total'] = $this->getCount($opt, $where, $like);
        }else{
            $this->result['total'] = 0 ;
        }
        
        
        return $this->result;
    }
    
    public function get($select = 0, $where = 0, $opt = 0)
    {
        $query = $this->db->from('portal_page')->join('portal_sites ps', 'ps.site_id=portal_page.page_group', 'left');
        
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
    
    
    public function getCount($opt, $where=array(), $like=array())
    {
        $this->db->from('portal_page');
        $this->db->where($where);
        $this->db->like($like);
        foreach($opt['order'] as $name => $direction){
            $this->db->order_by($name, $direction);
        }
        return $this->db->count_all_results();
    }

    public function create($input)
    {
        $this->db->trans_start();
        $this->db->insert('portal_page', $input['form']);
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
        $this->db->update('portal_page', $input['form'], $input['where']);
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
        
        $this->db->from('portal_page')
        ->join('portal_sites ps', 'ps.site_id=portal_page.page_group', 'left')
        ->where($where);
        $this->result =$this->db->get()->row_array();
        return true;
    }
}