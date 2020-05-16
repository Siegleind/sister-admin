<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SisterController_model extends CI_Model
{
    public $result;

    public function check($gid, $uri)
    {
        $query = $this->db->select('rp.role, ps.site_name, pp.permission_url')
        ->from('portal_permission pp')
        ->join('role_permission rp', 'rp.permission=pp.permission_id')
        ->join('portal_sites ps', 'ps.site_id=pp.permission_site ')
        ->where('rp.role', $gid)
        ->where('pp.permission_type', 0)
        ->where('pp.permission_url', $uri)->get();
        $this->result = $query->row_array();
        return $this->result;
        
    }

    public function getMainMenu($input)
    {
        foreach($input as $key => $val){
            $s[] = "{$key}";
        }
        $query = $this->db->select('pp.permission_site,ps.site_name,ps.site_icon,ps.site_color,,pp.permission_order,pp.permission_name,pp.permission_url,ps.site_group')
            ->from('portal_permission pp')
            ->join('portal_sites ps', 'ps.site_id=pp.permission_site')
            ->where_in('pp.permission_site', $s)
            ->where('pp.show_on_menu', 1)
            ->order_by('ps.site_group,pp.permission_site,pp.permission_order,pp.permission_name', 'ASC')->get();
        $this->result = $query->result_array();
        return $this->result;

    }
}