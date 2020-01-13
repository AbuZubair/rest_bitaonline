<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_model extends CI_Model {

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
		
		return $this->db->insert_id();
	}

    public function get_all_data($n,$q)
    {
       
        $this->db->from('quotation');
        // $this->db->where('is_active','Y');
        if($q!='')$this->db->like('quotation_no',$q);
        if($q!='')$this->db->like('company_name',$q);
        if($q!='')$this->db->like('company_address',$q);
        if($q!='')$this->db->like('valid_date',$q);
        if($q!='')$this->db->like('total_amount',$q);
        $this->db->order_by('id','desc');
        $this->db->limit($n);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_detail($n)
    {
       
        $this->db->from('quotation');
        $this->db->where('id',$n);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_detail_products($n)
    {
       
        $this->db->from('quotation_detail');
        $this->db->where('quotation_no',$n);
        $query = $this->db->get();
        return $query->result();

    }

}
