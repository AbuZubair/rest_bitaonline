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

    public function update($table,$data, $where)
	{

        $query = $this->db->update($table, $data, $where);
        $result = $this->db->affected_rows();
        log_message('debug','Products_model save query : '.json_encode($this->db->last_query()));
        log_message('debug','Products_model save insert_id : '.json_encode($result));

		return $result;
	}
 
    public function get_all_user($q,$n)
    {
        $this->db->from('user');
        $this->db->where('is_active','Y');
        $this->db->where('is_deleted','N');
        if($q!='')$this->db->like('fullname',$q);
        $this->db->limit($n);
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



        // $this->db->select('product.* , category.category as categoryname, brand.brand as brandname');
        // $this->db->from('product');
        // $this->db->join('category', 'product.category = category.id', 'left');
        // $this->db->join('brand', 'product.brand = brand.id', 'left');

        // $this->db->where('product.id',$n);
        // $query = $this->db->get();
        // return $query->result();

    }

    public function get_all_data_pending()
    {
        // $query = "SELECT user_profile.*,user.*,regencies.name
        //             FROM user_profile
        //             left join user on user.user_id=user_profile.user_id
        //             LEFT JOIN regencies on user_profile.regency = regencies.id
        //             WHERE user.is_approved = 'N'";
        $query = "SELECT *
        FROM user 
        WHERE is_approved = 'N' and is_deleted='N' order by created_date desc";
        $result = $this->db->query($query)->result();    
        log_message('debug','get_all_data_pending query : '.json_encode($this->db->last_query()));
        return $result;
        
    }

}
