<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function save($table, $data)
	{
		/*insert masakindo_resep*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();
	}

    public function update($table,$where, $data)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
    }
    
    public function create_room_by_id($q,$r)
    {
        $this->db->insert('room_chat',array('room_participant1_id'=>$q, 'room_participant2_id' => $r));

        return $this->db->insert_id();
    }
 
    public function get_room_by_id($q)
    {
       
        $query = "SELECT a.*,b.fullname as user_1,b.path_photo as user_1_photo,c.fullname as user_2,c.path_photo as user_2_photo, x.unread  FROM room_chat a
                    LEFT JOIN user_profile b ON a.room_participant1_id=b.user_id
                    LEFT JOIN user_profile c ON a.room_participant2_id=c.user_id
                    left join (SELECT COUNT(chat_id) AS unread, room_id FROM chat WHERE is_read='N' AND chat_receiver_id=".$q." GROUP BY room_id) x on x.room_id=a.room_id
                    WHERE a.room_participant1_id=".$q." OR a.room_participant2_id=".$q." ";
        return $this->db->query($query)->result();

    }

    public function get_room($q,$r)
    {
      
        $query = "SELECT a.*,b.fullname as user_1,c.fullname as user_2  FROM room_chat a
                    LEFT JOIN user_profile b ON a.room_participant1_id=b.user_id
                    LEFT JOIN user_profile c ON a.room_participant2_id=c.user_id
                    WHERE (a.room_participant1_id=".$q." OR a.room_participant1_id=".$r.") AND (a.room_participant2_id=".$q." OR a.room_participant2_id=".$r.") ";
        return $this->db->query($query)->row();

    }

    public function get_chat($q)
    {
      
        $query = "SELECT a.*,b.fullname as user_sender,c.fullname as user_receiver  FROM chat a
                    LEFT JOIN user_profile b ON a.chat_sender_id=b.user_id
                    LEFT JOIN user_profile c ON a.chat_receiver_id=c.user_id
                    WHERE a.room_id=".$q." order by created_date asc";
        return $this->db->query($query)->result();

    }
    
    public function get_contact_list($id)
    {
      
        $query = "SELECT a.*,b.fullname as mahasiswa, c.path_photo, c.jurusan, c.angkatan, x.unread FROM judul a
                    LEFT JOIN user b ON a.user_id=b.user_id
                    LEFT JOIN user_profile c ON a.user_id=c.user_id
                    LEFT JOIN (SELECT COUNT(chat_id) AS unread,chat_sender_id  FROM chat WHERE is_read='N' AND chat_receiver_id=".$id." GROUP BY room_id) x ON x.chat_sender_id=a.user_id
                    WHERE a.dospem=".$id." ";
        $result = $this->db->query($query)->result();

        if(!empty($result)){
            return $result;
        }else{
            return false;
        }

    }


}
