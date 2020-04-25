<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class GlobalController extends REST_Controller {

	public function __construct() {

		parent::__construct();
        $this->load->model('Global_model');
        $this->load->model('Login_model','login_model');
    
	}

	public function get_dospem_get()
    {
                
        $data = $this->Global_model->get_dospem();

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }

    public function process_judul_post(){
        
        // form validation
        $judul = $this->post('judul');
        $description = $this->post('description');
        $dospem = $this->post('dospem');
        $user_id = $this->post('user_id');
        $judul_id = $this->post('judul_id');
        
        try {
    
            /*execution form*/
    
            $this->db->trans_begin();
    
            $dataexc = array(
                'judul' => $this->regex->_genRegex($judul,'RGXQSL'),
                'deskripsi' => $this->regex->_genRegex($description,'RGXQSL'),
                'dospem' => $this->regex->_genRegex($dospem,'RGXQSL'),
                'user_id' => $this->regex->_genRegex($user_id,'RGXINT'),
                'approval' => 0,
            );
        
            if($judul_id == 0){
                
                $dataexc['created_date'] =  date('Y-m-d H:i:s');
                /*save post data*/
                $newId = $this->Global_model->save('judul',$dataexc);
   
            }else{

                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $newId = $judul_id;
                $this->Global_model->update('judul', $dataexc, array('id' => $judul_id));

            }
    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                
                $resp = array(
                    'message' => 'Maaf Proses Gagal Dilakukan',
                );

                $this->response($resp, 301);
        
            }
            else
            {
                $this->db->trans_commit();

                $data_judul = $this->Global_model->get_judul_by_id($newId);

                $resp = array(
                    'status' => 200,
                    'message' => 'Proses Berhasil Dilakukan',
                    'data' => $data_judul
                );

                $this->response($resp, 200);
        
            }
    
    
        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);
    
        }
        
        
    }

    public function process_update_judul_post(){
        
        // form validation
        $status = $this->post('status');
        $judul_id = $this->post('judul_id');
        
        try {
    
            /*execution form*/
    
            $this->db->trans_begin();

            $this->Global_model->update('judul', array('approval' => $status, 'updated_date' => date('Y-m-d H:i:s')), array('id' => $judul_id));

                
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                
                $resp = array(
                    'message' => 'Maaf Proses Gagal Dilakukan',
                );

                $this->response($resp, 301);
        
            }
            else
            {
                $this->db->trans_commit();

                $data_judul = $this->login_model->get_judul_by_dosen($this->post('user_id'));

                $resp = array(
                    'status' => 200,
                    'message' => 'Proses Berhasil Dilakukan',
                    'data' => $data_judul
                );

                $this->response($resp, 200);
        
            }
    
    
        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);
    
        }
        
        
    }

    public function process_nilai_post(){
        
        // form validation
        $nilai = $this->post('nilai');
        $judul_id = $this->post('judul_id');
        
        try {
    
            /*execution form*/
    
            $this->db->trans_begin();

            $this->Global_model->update('judul', array('nilai_akhir' => $nilai, 'updated_date' => date('Y-m-d H:i:s')), array('id' => $judul_id));

            $judul_detail = $this->Global_model->get_judul_by_id($judul_id);

            $data_notif = array(
                'user_id' => $judul_detail->user_id,
                'type' => 'nilai_akhir',
                'msg' => 'Selamat, dosen kamu memberi nilai'.$nilai ,
                'is_read' => 'N',
                'created_date' => date("Y-m-d H:i:s")
            );

            $this->Global_model->save('notification', $data_notif);
                
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                
                $resp = array(
                    'message' => 'Maaf Proses Gagal Dilakukan',
                );

                $this->response($resp, 301);
        
            }
            else
            {
                $this->db->trans_commit();

                $data_judul = $this->login_model->get_judul_by_dosen($this->post('user_id'));

                $resp = array(
                    'status' => 200,
                    'message' => 'Proses Berhasil Dilakukan',
                    'data' => $data_judul
                );

                $this->response($resp, 200);
        
            }
    
    
        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);
    
        }
        
        
    }

    public function process_update_bimbingan_post(){
        
        // form validation
        $status = $this->post('status');
        $data = $this->post('data');

        try {
    
            /*execution form*/
    
            $this->db->trans_begin();

            $this->Global_model->update('jadwal_bimbingan', array('status' => $status, 'updated_date' => date('Y-m-d H:i:s')), array('id' => $data['id']));

                
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                
                $resp = array(
                    'message' => 'Maaf Proses Gagal Dilakukan',
                );

                $this->response($resp, 301);
        
            }
            else
            {
                $this->db->trans_commit();

                $data_bimbingan = $this->login_model->get_jadwalbimbingan($data['dospem'],3);

                $resp = array(
                    'status' => 200,
                    'message' => 'Proses Berhasil Dilakukan',
                    'data' => $data_bimbingan
                );

                $this->response($resp, 200);
        
            }
    
    
        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);
    
        }
        
        
    }

    public function process_jadwal_bimbingan_post(){
        
        // form validation
        $user_id = $this->post('user_id');
        $level = $this->post('level');
        $tgl = $this->post('tgl');
        $jam = $this->post('jam');
        $dospem = $this->post('dospem');
   
        $date = date('Y-m-d H:i:s', strtotime("$tgl $jam"));; 
     
                
        try {
    
            /*execution form*/
    
            $this->db->trans_begin();
    
            $dataexc = array(
                'dospem' => $this->regex->_genRegex($dospem,'RGXQSL'),
                'mahasiswa' => $this->regex->_genRegex($user_id,'RGXQSL'),
                'jadwal' => $date,
                'status' => 0,
                'type' => 'bimbingan',
                'created_date' => date('Y-m-d H:i:s')
            );

            $newId = $this->Global_model->save('jadwal_bimbingan',$dataexc);
    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                
                $resp = array(
                    'message' => 'Maaf Proses Gagal Dilakukan',
                );

                $this->response($resp, 301);
        
            }
            else
            {
                $this->db->trans_commit();

                $data_jadwal = $this->Global_model->get_jadwal_by_id($dospem, $level);

                $resp = array(
                    'status' => 200,
                    'message' => 'Proses Berhasil Dilakukan',
                    'data' => $data_jadwal
                );

                $this->response($resp, 200);
        
            }
    
    
        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);
    
        }
        
        
    }

    public function process_note_judul_post(){
        
        // form validation
        $judul_id = $this->post('judul_id');
        $note = $this->post('note');
                      
        try {
    
            /*execution form*/
    
            $this->db->trans_begin();
    
            $dataexc = array(
                'judul_id' => $this->regex->_genRegex($judul_id,'RGXINT'),
                'komen' => $this->regex->_genRegex($note,'RGXQSL'),
                'created_date' => date('Y-m-d H:i:s')
            );

            $newId = $this->Global_model->save('judul_detail',$dataexc);
            $data = $this->Global_model->get_judul_detail($judul_id);
    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                
                $resp = array(
                    'message' => 'Maaf Proses Gagal Dilakukan',
                );

                $this->response($resp, 301);
        
            }
            else
            {
                $this->db->trans_commit();

                $resp = array(
                    'status' => 200,
                    'message' => 'Proses Berhasil Dilakukan',
                    'data' => $data
                );

                $this->response($resp, 200);
        
            }
    
    
        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);
    
        }
        
        
    }

    public function get_judul_detail_get()
    {
        $data = $this->Global_model->get_judul_detail($this->get('id'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }

    public function send_link_post()
    {
        header("Access-Control-Allow-Origin: *");
        
        $email = $this->post('email');
        $qry = $this->db->get_where('user', array('email' => $email));
        $cek =  $qry->num_rows();
        $data =$qry->row();
        //print_r($cek);print_r($data);

        if($cek!=0)
        {
            $this->mailer_2->sendemail($data->username);
            
        } else {
            echo json_encode(array('status' => 301, 'message' => 'Maaf email tidak terdaftar !!'));
        }
    }

    public function get_notif_get()
    {
        $data = $this->Global_model->get_all_notif($this->get('user_id'));

        $this->Global_model->update('notification',array('is_read' => 'Y'),array('user_id' => $this->get('user_id')));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data[0]),200);
    }

    public function get_file_upload_get()
    {
        $data = $this->Global_model->get_file_upload($this->get('id'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }

    public function process_upload_post(){
        
        // form validation
        $judul_id = $this->post('judul_id');
        $filesName = $this->post('filesName');
                      
        try {
    
            /*execution form*/
    
            $this->db->trans_begin();
    
            $dataexc = array(
                'judul_id' => $this->regex->_genRegex($judul_id,'RGXINT'),
                'filename' => $this->regex->_genRegex($filesName,'RGXQSL'),
                'created_date' => date('Y-m-d H:i:s')
            );

            $newId = $this->Global_model->save('files',$dataexc);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                
                $resp = array(
                    'message' => 'Maaf Proses Gagal Dilakukan',
                );

                $this->response($resp, 301);
        
            }
            else
            {
                $this->db->trans_commit();

                $resp = array(
                    'status' => 200,
                    'message' => 'Proses Berhasil Dilakukan',
                    'data' => $dataexc
                );

                $this->response($resp, 200);
        
            }
    
    
        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);
    
        }
        
        
    }

    public function get_notif_post()
    {
        $user_id = $this->post('user_id');
        $level = $this->post('level');

        /*get last bimbingan */

        $last_bimb = $this->Global_model->get_last_bimb($user_id,$level);

        $onemonth = date("Y-m-d H:i:s", strtotime( date( "Y-m-d H:i:s", strtotime( date("Y-m-d H:i:s") ) ) . "-1 month" ) );
        $last_bimb_date = date("Y-m-d H:i:s",strtotime($last_bimb->jadwal));

        if($last_bimb_date <= $onemonth){
            if($last_bimb->status==0){
                $dataexec1 = array(
                    'user_id' => $user_id,
                    'type' => 'last_bimb',
                    'msg' => 'Sudah lebih dari satu bulan tidak ada bimbingan',
                    'created_date' => date("Y-m-d H:i:s"),
                    'is_read' => 'N'
                );

                $check_first = $this->Global_model->check_last_bimb($user_id);

                if(!$check_first)$this->Global_model->save('notification',$dataexec1);
            }
        }

        /*get bimbingan terdekat */

        $bimb_dekat = $this->Global_model->get_bimb_dekat($user_id,$level);

        if($bimb_dekat){
            foreach ($bimb_dekat as $key => $value) {
                if($value->status==0){
                    $dataexec2 = array(
                        'user_id' => $user_id,
                        'type' => 'reminder',
                        'msg' => 'Bimbingan akan dilakukan pada '.date('l, F d y h:i:s',strtotime($value->jadwal)),
                        'created_date' => date("Y-m-d H:i:s"),
                        'is_read' => 'N',
                        'jadwal_id' => $value->id
                    );

                    $check_bimb_first = $this->Global_model->check_bimb_first($value->id);
    
                    if(!$check_bimb_first)$this->Global_model->save('notification',$dataexec2);
                }
            }
        }

        $data = $this->Global_model->get_all_notif($user_id);

        $this->response(array('status' => 200, 'message' => 'Sukses', 'unread' => $data[1], 'data' => $data[0]),200);
    }
  
}
?>