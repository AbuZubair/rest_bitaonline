<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function save($table, $data)
	{
		/*insert masakindo_resep*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

    public function update($table,$where, $data)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}
 
    public function get_all_user($n)
    {
        $query = "SELECT user_profile.*,user.*,regencies.name
                    FROM user_profile
                    left join user on user.user_id=user_profile.user_id
                    LEFT JOIN regencies on user_profile.regency = regencies.id
                    WHERE user.is_active = 'Y' AND user.is_deleted = 'N' LIMIT ".$n."";
        return $this->db->query($query)->result();
    }
    

}
