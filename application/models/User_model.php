<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function save($table, $data)
	{
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

    public function update($table,$data, $where)
	{

        $query = $this->db->update($table, $data, $where);
        $result = $this->db->affected_rows();
      
		return $result;
	}
 
    public function get_all_user($id)
    {
        $this->db->select('user.* , user_profile.path_photo');
        $this->db->from('user');
        $this->db->join('user_profile', 'user.user_id = user_profile.user_id', 'left');
        $this->db->where('is_deleted','N');
        $this->db->where("user.user_id != ".$id." ");
        $query = $this->db->get();
        return $query->result();
    }    

    public function get_userlevel()
    {
        return $this->db->order_by('level_id', 'asc')->get_where('level_user')->result();
    }

    public function get_detail($n)
    {
       
        $this->db->from('user');
        $this->db->where('user_id',$n);
        $query = $this->db->get();
        return $query->result();

    }


}
