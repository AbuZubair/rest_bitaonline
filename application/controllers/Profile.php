<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends REST_Controller {

	public function __construct() {

		parent::__construct();

		$this->load->model('Profile_model');
    
    }

	public function process_profile_user_post(){

        // print_r($this->post());die;

        // form validation
        $fullname = $this->post('fullname');
        $pob = $this->post('pob');
        $dob = $this->post('dob');
        $address = $this->post('address');
        $province = $this->post('province');
        $regency = $this->post('regency');
        $district = $this->post('district');
        $village = $this->post('village');
        $phone = $this->post('phone');
        $gender = $this->post('gender');
        $no_ktp = $this->post('no_ktp');
        $user_id = $this->post('user_id');
        $path_photo = $this->post('path_photo');
          
        try {
    
            /*execution form*/
    
            $this->db->trans_begin();
    
            $dataexc = array(
                'fullname' => $this->regex->_genRegex($fullname,'RGXQSL'),
                'pob' => $this->regex->_genRegex($pob,'RGXQSL'),
                'dob' => $this->regex->_genRegex($dob,'RGXQSL'),
                'address' => $this->regex->_genRegex($address,'RGXQSL'),
                'province' => $this->regex->_genRegex($province,'RGXINT'),
                'regency' => $this->regex->_genRegex($regency,'RGXINT'),
                'district' => $this->regex->_genRegex($district,'RGXINT'),
                'village' => $this->regex->_genRegex($village,'RGXINT'),
                'phone' => $this->regex->_genRegex($phone,'RGXQSL'),
                'gender' => $this->regex->_genRegex($gender,'RGXAZ'),
                'no_ktp' => $this->regex->_genRegex($no_ktp,'RGXQSL'),
                'path_photo' => $this->regex->_genRegex($path_photo,'RGXQSL'),
            );
    
            $user_profile = $this->db->get_where('user_profile',array('user_id' => $user_id ) )->num_rows();
    
            if($user_profile == 0){
                
                $dataexc['user_id'] =  $this->regex->_genRegex($user_id,'RGXINT');
                /*save post data*/
                $newId = $this->Profile_model->save('user_profile',$dataexc);
    
                $this->Profile_model->update('user', array('fullname' => $dataexc['fullname'],'phone_no' => $dataexc['phone']), array('user_id' => $user_id));
            }else{

                $dataexc['updated_date'] = date('Y-m-d H:i:s');
    
                $this->Profile_model->update_profile_user($dataexc, $user_id);
    
                $this->Profile_model->update('user', array('fullname' => $dataexc['fullname'],'phone_no' => $dataexc['phone']), array('user_id' => $user_id));
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

    public function get_prov_get()
    {
        
        $data = $this->Profile_model->get_prov();

        $resp = array(
            'status' => 200,
            'message' => 'Proses Berhasil Dilakukan',
            'data' => $data
        );

        $this->response($resp, 200);
    }

    public function get_regency_get()
    {

        $data = $this->Profile_model->get_regency($this->get('q'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }

    public function get_district_get()
    {
        
        $data = $this->Profile_model->get_district($this->get('q'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }

    public function get_village_get()
    {
        
        $data = $this->Profile_model->get_village($this->get('q'));

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


    public function process_register_byadmin_post(){
        $this->load->model('login_model');
        $this->load->library('bcrypt');

		$email = $this->post('email');
		$phone_number = $this->post('phone_number');
		$fullname = $this->post('fullname');
		$security_code = $this->post('security_code');
		$confirm_security_code = $this->post('confirm_security_code');
		$level_id = $this->post('level_id');

        //var_dump($_POST); die();
        // array(6) { ["fullname"]=> string(4) "budi" ["email"]=> string(13) "budi@budi.com" ["phone_number"]=> string(9) "123123123" ["level_id"]=> string(1) "1" ["security_code"]=> string(4) "budi" ["confirm_security_code"]=> string(4) "budi" }

		if($email =='' || $phone_number ==''|| $fullname ==''|| $security_code ==''|| $level_id ==''){
			log_message('debug','process_register_byadmin some data empty');
			echo json_encode(array('status' => 301, 'message' => 'Form data tidak lengkap'));
			exit();
		}

		//var_dump($_POST); die();

		$user = $this->login_model->get_by_email($email);

		if($user==0){

			$this->db->trans_begin();

			$dataexc = array(
				'username' => $this->regex->_genRegex($email,'RGXQSL'),
				'phone_no' => $this->regex->_genRegex($phone_number,'RGXQSL'),
				'password' => $this->bcrypt->hash_password($security_code),
				'fullname' => $this->regex->_genRegex($fullname,'RGXQSL'),
				'created_date' => date('Y-m-d H:i:s'),
				'security_code' => rand(9, 9999),
                'is_active' => 'Y',
                'is_approved' => 'Y'
			);

			log_message('debug','process_register_byadmin dataexc : '.json_encode($dataexc));

			if(isset($_POST['level_id']))$dataexc['level_id'] = $this->post('level_id');
				
			/*save post data*/
			$newId = $this->login_model->save_acc_register($dataexc);

			/*get new data register*/
			$newData = $this->login_model->get_by_id($newId);

			/*create key */
			$keyexec = array(
				'user_id' => $newId,
				'key' => sha1(date('mYd').$newData->username),
				'level' => 1,
				'ip_addresses' => $this->get_client_ip(),
				'date_created' => date('Y-m-d H:i:s')
			);

			log_message('debug','process_register_byadmin keyexec : '.json_encode($keyexec));

			$this->login_model->create_key($keyexec);
			
			// /*send notification by sms*/

			// $config_sms = array(
			//     'from' => 'Hydromart',
			//     'phone' => $newData->phone_no,
			//     'message' => '(no-reply) Hydromart : Kode Verifikasi anda '.$newData->security_code.'',
			//     );

			// $send_sms = $this->api->adsmedia_send_sms($config_sms);
			
			// /*end send notification by sms*/


			if ($this->db->trans_status() === FALSE)
			{
				log_message('debug','process_register_byadmin gagal');
				$this->db->trans_rollback();
				echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
			}
			else
			{
				log_message('debug','process_register_byadmin sukses');
				$this->db->trans_commit();
				echo json_encode(array('status' => 200, 'message' => 'Sukses registrasi'));
				// $hash_security_code = $this->bcrypt->hash_password($dataexc['security_code']);
				// echo json_encode(array('status' => 200, 'message' => 'Silahkan Verifikasi Code yang dikirimkan via SMS', 'verifikasi_code' => $dataexc['security_code'], 'uid' => $hash_security_code,'id' => $newId ));
			}
		}else{
			log_message('debug','process_register_byadmin email used');
			echo json_encode(array('status' => 301, 'message' => 'Maaf Email Sudah digunakan'));
		}

	}

    public function uploadImage()
    {
        header('Access-Control-Allow-Origin: *');
        # code...
        $random = rand(1,99);
        $unique_filename = $_FILES['path_photo']['name'] . $random ;

        $path = PATH_PHOTO_PROFILE_DEFAULT;
        $vfile_upload = $path . $_FILES['path_photo']['name'];
         
        if (move_uploaded_file($_FILES['path_photo']['tmp_name'], $vfile_upload)) {
            echo $_FILES['path_photo']['name'];
        } else {
        echo $target_path;
            echo "There was an error uploading the file, please try again!";
        }
    }

    public function deleteImage_post()
    {
        # code...
        $file_path='uploaded/images/photo/'.$this->post('file');
        //$file_path=$this->input->post('file');
        if (file_exists($file_path)) {
            unlink($file_path);
            echo json_encode(array('status' => 200, 'message' => 'Proses Delete Berhasil Dilakukan'));
        } else {
            // File not found.
        }
    }

}
?>









