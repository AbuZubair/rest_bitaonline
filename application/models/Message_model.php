<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function update($table,$where, $data)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

 
    public function get_room_by_id($q)
    {
        # code...
        //return $this->db->order_by('resep_id', 'desc')->get_where('masakindo_resep',array('district_id' => $q))->result();

        $query = "SELECT a.*,b.fullname as user_1,b.path_photo as user_1_photo,c.fullname as user_2,c.path_photo as user_2_photo, x.unread  FROM room_chat a
                    LEFT JOIN user_profile b ON a.room_participant1_id=b.user_id
                    LEFT JOIN user_profile c ON a.room_participant2_id=c.user_id
                    left join (SELECT COUNT(chat_id) AS unread, room_id FROM chat WHERE is_read='N' AND chat_receiver_id=".$q." GROUP BY room_id) x on x.room_id=a.room_id
                    WHERE a.room_participant1_id=".$q." OR a.room_participant2_id=".$q." ";
        return $this->db->query($query)->result();
    }

    

}
