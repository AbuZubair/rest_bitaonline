<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function update($table,$data,$where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

 
    public function save($table, $data)
	{
		/*insert masakindo_resep*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}


    public function update_profile_user($dataexc, $user_id){

        if( $this->db->update('user_profile', $dataexc, array('user_id' => $user_id)) ){
          
            return $this->db->get_where('user_profile', array('user_id' => $user_id))->row();

        }else{

            throw new Exception("Failed to update profile user");

        }

        
    }

    public function get_prov()
    {
       
        return $this->db->order_by('name', 'asc')->get_where('provinces',array())->result();

    }
   
    public function get_regency($q)
    {
      
        return $this->db->order_by('name', 'asc')->get_where('regencies',array('province_id' => $q))->result();
        

    }

    public function get_district($q)
    {
        
        return $this->db->order_by('name', 'asc')->get_where('districts',array('regency_id' => $q))->result();

    }

    public function get_village($q)
    {
        
        return $this->db->order_by('name', 'asc')->get_where('villages',array('district_id' => $q))->result();

    }

    public function get_profile($id)
    {
        
        return $this->db->get_where('user_profile', array('user_id' => $id))->row();

    }

    public function get_profile_by_id($id){

        $query = $this->db->get_where('user_profile', array('user_id' => $id));
        return $query->row();

    }

    public function get_by_id($id){

        $query = $this->db->select('user.user_id, user.username, user.password, user.last_logon, user.fullname, user.token_fcm, user.phone_no,user.level_id , user_profile.path_photo')
        ->join('user_profile','user_profile.user_id=user.user_id','left')
        ->get_where('user', array('user.user_id' => $id))->row();
        return $query;

    }

    public function get_token_admin(){

        $query = $this->db->get_where('user', array('user_id' => 1))->row();
        return $query;

    }

}
