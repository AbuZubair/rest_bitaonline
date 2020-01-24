<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends REST_Controller {
	public function __construct() {

		parent::__construct();
		$this->load->model('User_model');
    
	}

	public function get_all_user_get()
    {
		// var_dump($_POST); die();
        $data = $this->User_model->get_all_user($this->get('q'));

        $i=0;
        foreach ($data as $value) {
            $kota_ = substr(strstr($value->name," "), 1);
            $kota = strtolower($kota_);
            $data[$i]->name = ucwords($kota);
            $i++;
        }

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }
    
    
	
	public function get_all_data_pending_get()
    {
		// var_dump($_POST); die();
        $data = $this->User_model->get_all_data_pending($this->get('q'));

        // $i=0;
        // foreach ($data as $value) {
        //     $kota_ = substr(strstr($value->name," "), 1);
        //     $kota = strtolower($kota_);
        //     $data[$i]->name = ucwords($kota);
        //     $i++;
        // }

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }

     
    public function process_approve_user_post(){
        log_message('debug','process_approve_user_post data : '.json_encode($this->post()));
        $id = $this->post('id');
        $user_id = $this->post('user_id');
        if($id =='' || $user_id = ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     
        else {
            try {
      
                $this->db->trans_begin();
    
                $update = $this->User_model->update('user', array('is_approved' =>'Y'), array('user_id' => $id));

        
                log_message('debug','process_approve_user_post submitdata : '.json_encode($update));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_approve_user_post submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();
                    log_message('debug','process_approve_user_post submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $id),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_approve_user_post submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }

    }


    public function process_reject_user_post(){
        log_message('debug','process_reject_user_post data : '.json_encode($this->post()));
        $id = $this->post('id');
        $user_id = $this->post('user_id');

        if($id =='' || $user_id = ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     
        else {
            try {
      
                $this->db->trans_begin();
    
                $update = $this->User_model->update('user', array('is_deleted' =>'Y'), array('user_id' => $id));

        
                log_message('debug','process_reject_user_post submitdata : '.json_encode($update));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_reject_user_post submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();
                    log_message('debug','process_reject_user_post submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $id),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_reject_user_post submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }

    }
}
?>