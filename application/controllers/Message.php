<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Message extends REST_Controller {
	public function __construct() {

		parent::__construct();
		$this->load->model('Message_model');
    
	}

	public function get_room_by_id_get()
    {
		$data = $this->Message_model->get_room_by_id($this->get('q'));
        $sum_unread=$unread=0;

		if(!empty($data)){
			foreach ($data as $value) {
				if($value->unread!=null){
					$sum_unread += $value->unread;
				}
			}

			$unread = $this->master->thousandsCurrencyFormat($sum_unread);
		}
               
		
		$resp = array(
			'status' => 200,
			'data' => $data,
			'sum_unread' =>$unread,
		);

		
        $this->response($resp, 200);
	}
	
	public function get_room_get()
    {
  
        $data = $this->Message_model->get_room($this->get('q'), $this->get('r'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
	}
	
	public function get_chat_get()
    {
        # code...
        $data = $this->Message_model->get_chat($this->get('q'));

        $this->Message_model->update('chat',array('room_id' => $this->get('q'),'chat_receiver_id' => $this->get('r')),array('is_read' => 'Y'));

		$this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
	}
	
	public function process_message_post()
    {

		date_default_timezone_set("Asia/Jakarta");
		
        try {
            
            $this->db->trans_begin();

            $id = $this->post('room_id');

            $dataexc = array(
                'chat_sender_id' => $this->post('chat_sender_id'),
                'chat_receiver_id' => $this->post('chat_receiver_id'),
                'chat_content' =>  $this->post('chat_content'),
                'is_read' => 'N',
                'created_date' => date('Y-m-d H:i:s')
            );

            //print_r($dataexc);die;

            if($id == 0){

                $dataroom = array(
                    'room_participant1_id' => $this->post('chat_sender_id'),
                    'room_participant2_id' => $this->post('chat_receiver_id'),
                    'created_date' => date('Y-m-d H:i:s'),
                );

                $roomId = $this->Message_model->save('room_chat',$dataroom);

                $dataexc['room_id'] = $roomId;
               
                /*save message */
                $newId = $this->Message_model->save('chat',$dataexc);

            }else{
                
                $roomId = $id;
                $dataexc['room_id'] = $roomId;

                /*save message */
                $newId = $this->Message_model->save('chat',$dataexc);

                /*update room */
                $this->Message_model->update('room_chat', array('room_id' => $roomId), array('updated_date' => date('Y-m-d H:i:s')) );
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
               $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
            }
            else
            {
                $this->db->trans_commit();
                $data = $this->Message_model->get_chat($roomId);
               $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $data),200);
            }


        } catch (Exception $e) {
         
           $this->response( $e->getMessage() ,500);

        }
    }


}
?>