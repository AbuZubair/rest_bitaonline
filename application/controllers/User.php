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
        $q = ($this->get('q'))?$this->get('q'):'';
        $n = $this->get('n');

		// var_dump($_POST); die();
        $data = $this->User_model->get_all_user($q, $n);

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }

    public function get_userlevel_get()
    {
        
        $data = $this->User_model->get_userlevel();

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }
    
    
    public function get_detail_get()
    {
        $n = $this->get('n');
        $data = $this->User_model->get_detail($n);
        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }
    
    public function process_update_bonus_post()
    {
        log_message('debug','process_update_bonus_post data : '.json_encode($this->post()));
        // var_dump($this->post());
        // // array(3) { ["user_id"]=> string(2) "15" ["bonus"]=> string(4) "3000" ["user_id_admin"]=> string(1) "1" }
        // die();

        $user_id = $this->post('user_id');
        $user_id_admin = $this->post('user_id_admin');
        
        $data = array(
            'bonus' => $this->post('bonus'),
        );
        // var_dump($user_id);
        // var_dump($data);
        // die();

        if($user_id == '' && $user_id_admin == ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     
        else {
            try {
      
                $this->db->trans_begin();
    

                $update = $this->User_model->update('user', $data, array('user_id' => $user_id));

        
                log_message('debug','process_update_bonus_post submitdata : '.json_encode($update));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_update_bonus_post submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();
                    log_message('debug','process_update_bonus_post submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $user_id),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_update_bonus_post submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }
    }


     
    public function process_update_rating_post()
    {
        log_message('debug','process_update_rating_post data : '.json_encode($this->post()));
        //  var_dump($this->post());
        // // array(3) { ["user_id"]=> string(2) "15" ["rating"]=> string(1) "3" ["user_id_admin"]=> string(1) "1" }
        //  die();

        $user_id = $this->post('user_id');
        $user_id_admin = $this->post('user_id_admin');
        
        $data = array(
            'rating' => $this->post('rating'),
        );
        // var_dump($user_id);
        // var_dump($data);
        // die();

        if($user_id == '' && $user_id_admin == ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     
        else {
            try {
      
                $this->db->trans_begin();
    

                $update = $this->User_model->update('user', $data, array('user_id' => $user_id));

        
                log_message('debug','process_update_rating_post submitdata : '.json_encode($update));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_update_rating_post submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();
                    log_message('debug','process_update_rating_post submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $user_id),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_update_rating_post submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }
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