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
          
            return $this->db->get_where('user_profile', array('user_id' => $user_id));

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

}
