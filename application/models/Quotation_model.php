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
        log_message('debug','quotation update query : '.json_encode($this->db->last_query()));
        log_message('debug','quotation update affected_rows : '.json_encode($this->db->affected_rows()));
		return $this->db->affected_rows();
	}

 
    public function save($table, $data)
	{
		/*insert masakindo_resep*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();
	}

    public function get_all_data($id,$n,$q)
    {
       
        // $this->db->from('quotation');
        // // $this->db->where('is_active','Y');
        // if($q!='')$this->db->like('quotation_no',$q);
        // if($q!='')$this->db->like('company_name',$q);
        // if($q!='')$this->db->like('company_address',$q);
        // if($q!='')$this->db->like('valid_date',$q);
        // if($q!='')$this->db->like('total_amount',$q);
        // $this->db->where('customer_id',$id);
        // $this->db->where('customer_id is not null');
        // $this->db->order_by('id','desc');
        // $this->db->limit($n);
        // $query = $this->db->get();
        // return $query->result();


        $this->db->select('quotation.* , user.username, user.fullname, user.phone_no');
        $this->db->from('quotation');
        $this->db->join('user', 'user.user_id = quotation.customer_id', 'left');

        // $this->db->where('is_active','Y');
        if($q!='')$this->db->like('quotation.quotation_no',$q);
        if($q!='')$this->db->like('quotation.company_name',$q);
        if($q!='')$this->db->like('quotation.company_address',$q);
        if($q!='')$this->db->like('quotation.valid_date',$q);
        if($q!='')$this->db->like('quotation.total_amount',$q);
        $this->db->where('quotation.customer_id',$id);
        $this->db->where('quotation.customer_id is not null');
        $this->db->order_by('quotation.id','desc');
        $this->db->limit($n);
        $query = $this->db->get();
        return $query->result();

        
    }

    public function get_all_data_pending($id,$n,$q)
    {
       

        $this->db->select('quotation.* , user.username, user.fullname, user.phone_no');
        $this->db->from('quotation');
        $this->db->join('user', 'user.user_id = quotation.customer_id', 'left');

        // $this->db->where('is_active','Y');
        if($q!='')$this->db->like('quotation.quotation_no',$q);
        if($q!='')$this->db->like('quotation.company_name',$q);
        if($q!='')$this->db->like('quotation.company_address',$q);
        if($q!='')$this->db->like('quotation.valid_date',$q);
        if($q!='')$this->db->like('quotation.total_amount',$q);
        $this->db->where('quotation.status is null');
        $this->db->where('quotation.customer_id is not null');
        $this->db->order_by('quotation.id','desc');
        $this->db->limit($n);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_all_data_aprroved($id,$n,$q)
    {
       
 
        $this->db->select('quotation.* , user.username, user.fullname, user.phone_no');
        $this->db->from('quotation');
        $this->db->join('user', 'user.user_id = quotation.customer_id', 'left');

        // $this->db->where('is_active','Y');
        if($q!='')$this->db->like('quotation.quotation_no',$q);
        if($q!='')$this->db->like('quotation.company_name',$q);
        if($q!='')$this->db->like('quotation.company_address',$q);
        if($q!='')$this->db->like('quotation.valid_date',$q);
        if($q!='')$this->db->like('quotation.total_amount',$q);
        $this->db->where('quotation.status',1);
        $this->db->where('quotation.customer_id is not null');
        $this->db->order_by('quotation.id','desc');
        $this->db->limit($n);
        $query = $this->db->get();
        return $query->result();

    }
    

    public function get_detail($n)
    {
       
        // $this->db->from('quotation');
        // $this->db->where('id',$n);
        // $query = $this->db->get();

        $this->db->select('quotation.* , user.username, user.fullname, user.phone_no');
        $this->db->from('quotation');
        $this->db->join('user', 'user.user_id = quotation.customer_id', 'left');
        $this->db->where('quotation.id',$n);
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
