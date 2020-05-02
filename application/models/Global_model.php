<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_model extends CI_Model {

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
    
    public function get_user_by_id($id)
    {
        $result = $this->db->get_where('user',array('user_id' => $id))->row();

        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function get_dospem()
    {
        $result = $this->db->get_where('user',array('level_id' => '3', 'is_active' => 'Y'))->result();

        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function get_judul_detail($id)
    {
        $result = $this->db->order_by('created_date', 'desc')->get_where('judul_detail',array('judul_id' => $id))->result();

        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function get_judul_by_id($id)
    {
        $result = $this->db->get_where('judul',array('id' => $id))->row();

        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function get_jadwal_by_id($id,$level)
    {        
        $level = ($level==2)?'mahasiswa':'dospem';

        $qry = "SELECT a.*, b.fullname as dosen_string, c.fullname as mahasiswa_string, TIMEDIFF( NOW() , str_to_date(jadwal, '%Y-%m-%d %H:%i:%s')) as selisih FROM jadwal_bimbingan a
                LEFT JOIN user b ON a.dospem=b.user_id  
                LEFT JOIN user c ON a.mahasiswa=c.user_id 
                WHERE a.".$level." = ".$id." order by status ASC,abs(TIMEDIFF( NOW() , str_to_date(jadwal, '%Y-%m-%d %H:%i:%s')))";
        $jadwal = $this->db->query($qry)->result();
        
        if(!empty($jadwal)){
            return $jadwal;
        }else{
            return false;
        }
    }

    public function get_last_bimb($id,$level)
    {
        
        $where = ($level=='2')?'mahasiswa ='.$id : 'dospem ='.$id;

        $query ="SELECT * FROM jadwal_bimbingan AS a
        WHERE jadwal = (
            SELECT MAX(jadwal)
            FROM jadwal_bimbingan AS b
            WHERE b.".$where."
        ) AND a.".$where."";

        return $this->db->query($query)->row();

    }

    public function get_bimb_dekat($id,$level)
    {
        
        $where = ($level=='2')?'mahasiswa ='.$id : 'dospem ='.$id;

        $query ="SELECT * FROM jadwal_bimbingan
        WHERE ".$where." AND type ='bimbingan' AND jadwal BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 DAY) ORDER BY jadwal";

        $result = $this->db->query($query)->result();

        if($result){
            return $result;
        }else{
            return false;
        }

    }

    public function get_all_notif($id)
    {

        $unread = $this->db->get_where('notification',array('user_id' => $id, 'is_read' => 'N'))->num_rows();
        $result = $this->db->order_by('id','desc')->get_where('notification',array('user_id' => $id))->result();

        if($result){
            return [$result,$unread];
        }else{
            return false;
        }
    }

    public function get_file_upload($id)
    {

        $result = $this->db->get_where('files',array('judul_id' => $id))->result();

        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function check_last_bimb($id)
    {

        $result = $this->db->get_where('notification',array('user_id' => $id, 'is_read' => 'N', 'type' => 'last_bimb'))->row();

        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function check_bimb_first($id)
    {

        $result = $this->db->get_where('notification',array('jadwal_id' => $id))->row();

        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function checkSidang($user_id)
    {
        $result = $this->db->get_where('jadwal_bimbingan',array('type' => 'sidang', 'mahasiswa' => $user_id))->row();

        if($result){
            return $result;
        }else{
            return false;
        }
    }
    

}
