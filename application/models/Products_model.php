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
    
   
    public function get_detail($n)
    {
       
        // $this->db->from('product');
        // $this->db->where('id',$n);
        // $query = $this->db->get();
        // return $query->result();



        $this->db->select('product.* , category.category as categoryname, brand.brand as brandname');
        $this->db->from('product');
        $this->db->join('category', 'product.category = category.id', 'left');
        $this->db->join('brand', 'product.brand = brand.id', 'left');
        $this->db->where('product.is_active','Y');
               
        $this->db->where('product.id',$n);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_brand()
    {
        return $this->db->order_by('brand', 'asc')->get_where('brand')->result();
    }

    public function get_category()
    {
        return $this->db->order_by('category', 'asc')->get_where('category')->result();
    }
}
