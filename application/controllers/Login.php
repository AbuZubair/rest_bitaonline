<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");

class Login extends CI_Controller {
	
	public $input = array();

	private $httpVersion = "HTTP/1.1";

	public function setHttpHeaders($statusCode, $contentType='application/json'){

		$statusMessage = $this -> getHttpStatusMessage($statusCode);

		@header($this->httpVersion. " ". $statusCode ." ". $statusMessage);
		@header("Content-Type:". $contentType);

	}

	public function getHttpStatusMessage($statusCode){
		$httpStatus = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported');
		return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $status[500];
	}

	public function readInput(){

		$this->input = $_POST;

		$body = file_get_contents('php://input');
		$params = json_decode($body);

		if ($body !== '' && json_last_error() == JSON_ERROR_NONE) {
			$params_json = (array) $params;
			$this->input = array_merge($params_json, $this->input);
		}

		if(is_array($this->input)) {
			$this->input = array_change_key_case($this->input);
		}

	}

	public function getInput($key){

		return array_key_exists($key, $this->input) ? $this->input[$key] : '';

	}


	public function __construct() {

		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');
		header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
		header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 100000');
		$this->setHttpHeaders(200);
		$this->readInput();

		$this->load->model('Login_model','login_model');
		$this->load->model('Message_model');
		$this->load->model('Global_model');
		$this->load->library('bcrypt');
    
	}

	public function index(){

		$username = $this->getInput('username');
		$password = $this->getInput('password');

		$this->logs->log('login','Data input Login',$_POST);
        
        try {
            
             /*check username and password exist*/
            $result = $this->login_model->check_account($username,$password);

            /*clear token existing or before*/
            $this->login_model->clear_token($result->user_id);

			$profile = $this->login_model->get_user_profile($result->user_id);
            if($profile)$user_profile = $profile;
				
			$menu = $this->login_model->get_menu($result->level_id);
			if($menu)$menu_user = $menu;
					
			/*update last logon user*/
			$this->login_model->last_logon($result->username,$result->password);
			
			/*upadte key */
			$key = $this->login_model->update_key($result->user_id,$result->username);
			$result->key = $key;
			$this->logs->log('login','Update key',array('user_id' => $result->user_id,'key' => $key));

            $data = array(
                        'logged' => TRUE, 
                        'user' => $result, 
						'user_profile' => isset($user_profile)?$user_profile:'',
						'menu' => isset($menu_user)?$menu_user:[],
					);
					
			if($result->level_id==2){
				$judul = $this->login_model->get_judul($result->user_id);
				if($judul){
					$judul_user = $judul;

					$room = $this->Message_model->get_room_by_id($result->user_id,$judul->dospem);
					$sum_unread=$unread=0;
	
					if(!empty($room)){
						foreach ($room as $value) {
							if($value->unread!=null){
								$sum_unread += $value->unread;
							}
						}
	
						$unread = $this->master->thousandsCurrencyFormat($sum_unread);
					}
	
					$data['sum_unread'] = $unread;
				}else{
					$data['sum_unread'] = 0;
				}

				$data['judul'] = isset($judul)?$judul:'';


			}else if($result->level_id==3){
				$judul = $this->login_model->get_judul_by_dosen($result->user_id);
				if($judul)$judul_user = $judul;

				$data['judul'] = isset($judul)?$judul:'';

				$room = $this->Message_model->get_room_by_id_($result->user_id);
				$sum_unread=$unread=0;

				if(!empty($room)){
					foreach ($room as $value) {
						if($value->unread!=null){
							$sum_unread += $value->unread;
						}
					}

					$unread = $this->master->thousandsCurrencyFormat($sum_unread);
				}

				$data['sum_unread'] = $unread;
			}

			$jadwalbimbingan = $this->login_model->get_jadwalbimbingan($result->user_id,$result->level_id);
			if($jadwalbimbingan){
				$jadwalbimbingan_user = $jadwalbimbingan;
				$data['sum_unread_notif'] = $this->get_notif($result->user_id,$result->level_id);
			}else{
				$data['sum_unread_notif'] = 0;
			}

			$data['jadwal_bimbingan'] = isset($jadwalbimbingan)?$jadwalbimbingan:'';

				
			
            $response = array(
				'status' => 200,
				'message' => $this->getHttpStatusMessage(200),
			);

            $arr_merge = array_merge($response, $data);

            echo json_encode( $arr_merge );

        } catch (Exception $e) {
            
            $response = array(
				'status' => 500,
				'message' => $e->getMessage(),
			);

            echo json_encode( $response );

        }
        

	}
	
	public function get_notif($user_id,$level)
    {
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
	
		return $data[1];

    }

    public function logout()
    {   
        $this->login_model->clear_token($this->session->userdata('user')->user_id);
    }

	
	public function process_register(){

		$username = $this->getInput('username');
		$phone_number = $this->getInput('phone');
		$fullname = $this->getInput('fullname');
		$security_code = $this->getInput('security_code');
		$confirm_security_code = $this->getInput('confirm_security_code');
		$user_id = $this->getInput('user_id');

		$user = $this->login_model->get_by_username($username);

		if($user==0 || $user_id!=''){

			$this->db->trans_begin();

			$dataexc = array(
				'username' => $this->regex->_genRegex($username,'RGXQSL'),
				'phone_no' => $this->regex->_genRegex($phone_number,'RGXQSL'),
				'fullname' => $this->regex->_genRegex($fullname,'RGXQSL'),
				'level_id' => $this->getInput('level_id'),
			);

			if($user_id!=''){
				$dataexc['is_active'] =  $this->getInput('is_active');
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $this->login_model->update('user',$dataexc,array('user_id' => $user_id));
                $newId = $user_id;

                $message = "User berhasil di update";
            }else{
				 $dataexc['is_active'] = 'Y';
				 $dataexc['security_code'] = rand(1000, 9999);
                $dataexc['password'] = $this->bcrypt->hash_password($security_code);
                $dataexc['created_date'] = date('Y-m-d H:i:s');

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

                $this->login_model->create_key($keyexec);

				$message = "User berhasil ditambahkan";
				
				/*send notification by sms*/

				// $config_sms = array(
				// 	'from' => 'Bitaonline',
				// 	'phone' => $newData->phone_no,
				// 	'message' => '(no-reply) Bitaonline : Kode Verifikasi anda '.$newData->security_code.'',
				// 	);

				// $send_sms = $this->api->adsmedia_send_sms($config_sms);
				
				/*end send notification by sms*/
            }		
					

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
			}
			else
			{
				$this->db->trans_commit();

				if($user_id!=''){
					echo json_encode(array('status' => 200, 'message' => 'Proses berhasil, '.$message.'', 'id' => $newId ));
				}else{
					$hash_security_code = $this->bcrypt->hash_password($dataexc['security_code']);
					echo json_encode(array('status' => 200, 'message' => 'Proses berhasil', 'verifikasi_code' => $dataexc['security_code'], 'uid' => $hash_security_code,'id' => $newId ));
				}
				
			}
		}else{
			echo json_encode(array('status' => 301, 'message' => 'Maaf Username Sudah digunakan'));
		}

	}
	
	
	public function send_sms()
    {

		/*get new data register*/
		$newData = $this->login_model->get_by_id($this->getInput('id'));

		/*send notification by sms*/

         $config_sms = array(
             'from' => 'Bitaonline',
             'phone' => $newData->phone_no,
             'message' => '(no-reply) Bitaonline : Kode Verifikasi anda '.$newData->security_code.'',
             );

         $send_sms = $this->api->adsmedia_send_sms($config_sms);


         if( $send_sms==1 ){
            echo json_encode(array('status' => 200, 'message' => 'Proses pengiriman berhasil dilakukan' ));
         }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
         }

	}
	
	public function process_verification(){
        /*find security code*/
        $security_code = $this->getInput('key');
        /*check security code*/
        $find = $this->login_model->verifikasi_security_code($security_code);
        if (count($find) > 0) {
            /*update status akun aktif*/
            $this->login_model->update_status_account($find[0]->user_id, 'Y');
            
            /*show message success*/
            echo json_encode(array('status' => '200', 'message' => 'Verifikasi berhasil'));
        }else{
            echo json_encode(array('status' => '301', 'message' => 'Security Code tidak dapat diverifikasi' ));
        }


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

    

}