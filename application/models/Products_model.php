<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function update($table,$where, $data)
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


    public function get_all_products($n,$q)
    {
       
        $this->db->from('product');
        $this->db->where('is_active','Y');
        if($q!='')$this->db->like('name',$q);
        $this->db->limit($n);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_rfu()
    {
        return $this->db->get_where('product',array('is_active' => 'Y', 'is_rfu' => 'Y'))->result();
    }
    
   
 

}
