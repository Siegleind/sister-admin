<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeDashboard_model extends CI_Model
{
    public $result;

    public function getDashboard($uid)
    {
        foreach($uid['site'] as $key => $val){
            $s[] = "{$key}";
        }
        $query = $this->db->select('ps.site_name, ps.site_color, ps.site_icon, ud.data_value, pp.permission_url, ps.site_group')
            ->from('user_data ud')
            ->join('portal_sites ps', 'ps.site_id=ud.data_site')
            ->join('portal_permission pp', 'pp.permission_site=ud.data_site')
            ->where('ud.data_user', $uid['user_id'])
            ->where('pp.permission_code', 'siteAccess')
            ->where_in('ud.data_site', $s)
            ->where('ud.data_type', 1)
            ->order_by('ps.site_group,ps.site_name', 'ASC')->get();
        $this->result = $query->result_array();
        return $this->result;

    }
    
    
}