<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends REST_Controller {

   
	public function __construct() {

		parent::__construct();
		$this->load->model('Profile_model');
    
    }

	public function process_profile_user_post(){
        
        // form validation
        $fullname = $this->post('fullname');
        $pob = $this->post('pob');
        $dob = $this->post('dob');
        $address = $this->post('address');
        $phone = $this->post('phone');
        $gender = $this->post('gender');
        $user_id = $this->post('user_id');
        $path_photo = $this->post('path_photo');
        $jurusan = $this->post('jurusan');
        $angkatan = $this->post('angkatan');
        $nim = $this->post('nim');

        try {
    
            /*execution form*/
    
            $this->db->trans_begin();
    
            $dataexc = array(
                'fullname' => $this->regex->_genRegex($fullname,'RGXQSL'),
                'pob' => $this->regex->_genRegex($pob,'RGXQSL'),
                'dob' => $this->regex->_genRegex($dob,'RGXQSL'),
                'address' => $this->regex->_genRegex($address,'RGXQSL'),
                'phone' => $this->regex->_genRegex($phone,'RGXQSL'),
                'gender' => $this->regex->_genRegex($gender,'RGXAZ'),
                'path_photo' => $this->regex->_genRegex(trim($path_photo),'RGXQSL'),
                'jurusan' => $this->regex->_genRegex($jurusan,'RGXQSL'),
                'angkatan' => $this->regex->_genRegex($angkatan,'RGXQSL'),
                'nim' => $this->regex->_genRegex($nim,'RGXQSL'),
            );
    
            $user_profile = $this->db->get_where('user_profile',array('user_id' => $user_id ) )->num_rows();
    
            if($user_profile == 0){
                
                $dataexc['user_id'] =  $this->regex->_genRegex($user_id,'RGXINT');
                /*save post data*/
                $newId = $this->Profile_model->save('user_profile',$dataexc);
    
                $this->Profile_model->update('user', array('fullname' => $dataexc['fullname'],'phone_no' => $dataexc['phone']), array('user_id' => $user_id));
                $this->Profile_model->update('judul', array('dospem_string' => $dataexc['fullname']), array('dospem' => $user_id));
            }else{

                $dataexc['updated_date'] = date('Y-m-d H:i:s');
    
                $this->Profile_model->update_profile_user($dataexc, $user_id);
    
                $this->Profile_model->update('user', array('fullname' => $dataexc['fullname'],'phone_no' => $dataexc['phone']), array('user_id' => $user_id));
                $this->Profile_model->update('judul', array('dospem_string' => $dataexc['fullname']), array('dospem' => $user_id));
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

    public function get_profile_get()
    {
        
        $data = $this->Profile_model->get_profile_by_id($this->get('id'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }

	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}



    public function process_upload_header_post(){

        try {

            /*execution form*/

            $this->db->trans_begin();

            $dataexc = array(
                'path_photo_header' => trim($this->post('path_photo_header')),
                'updated_date' => date('Y-m-d H:i:s'),
            );

            $this->Profile_model->update_profile_user($dataexc,$this->post('user_id'));

            $user_profile = $this->Profile_model->get_profile_by_id($this->post('user_id'));
               
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan', 'data' => $data),301);
            }
            else
            {
                $this->db->trans_commit();
                $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $user_profile),200);
            }


        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);

        }

    }

    public function process_upload_ava_post(){

        try {


            $this->db->trans_begin();

            $dataexc = array(
                'path_photo' => trim($this->post('path_photo')),
                'updated_date' => date('Y-m-d H:i:s')
            );

            $this->Profile_model->update_profile_user($dataexc,$this->post('user_id'));

            $user_profile = $this->Profile_model->get_profile_by_id($this->post('user_id'));
               
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan', 'data' => $data),301);
            }
            else
            {
                $this->db->trans_commit();
                $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $user_profile),200);
            }


        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);

        }

    }

    public function deleteImage_post()
    {
        # code...
        $file_path='uploaded/images/photo/'.$this->post('file');
        //$file_path=$this->input->post('file');
        if (file_exists($file_path)) {
            unlink($file_path);
            $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'),200);
        } else {
            // File not found.
        }
    }


    public function get_token_admin_get()
    {
        
        $data = $this->Profile_model->get_token_admin();

        $this->response(array('status' => 200, 'message' => 'Sukses', 'token' => $data->token_fcm),200);
    }

}
?>









