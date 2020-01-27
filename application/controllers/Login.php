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
						'user_profile' => isset($user_profile)?$user_profile:[],
						'menu' => isset($menu_user)?$menu_user:[],
                    );

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

    public function logout()
    {   
        $this->login_model->clear_token($this->session->userdata('user')->user_id);
    }

	// public function ping()
	// {
	// 	$this->log('Call API ping');
	// 	echo '{"code":"200", "status":"success", "message":"ping"}';
	// }

	public function process_register(){

		$email = $this->getInput('email');
		$phone_number = $this->getInput('phone_number');
		$fullname = $this->getInput('fullname');
		$security_code = $this->getInput('security_code');
		$confirm_security_code = $this->getInput('confirm_security_code');

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
			);

			if(isset($_POST['level_id']))$dataexc['level_id'] = $this->getInput('level_id');
				
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
			
			/*send notification by sms*/

			$config_sms = array(
			    'from' => 'Hydromart',
			    'phone' => $newData->phone_no,
			    'message' => '(no-reply) Hydromart : Kode Verifikasi anda '.$newData->security_code.'',
			    );

			$send_sms = $this->api->adsmedia_send_sms($config_sms);
			
			/*end send notification by sms*/


			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
			}
			else
			{
				$this->db->trans_commit();
				$hash_security_code = $this->bcrypt->hash_password($dataexc['security_code']);
				echo json_encode(array('status' => 200, 'message' => 'Silahkan Verifikasi Code yang dikirimkan via SMS', 'verifikasi_code' => $dataexc['security_code'], 'uid' => $hash_security_code,'id' => $newId ));
			}
		}else{
			echo json_encode(array('status' => 301, 'message' => 'Maaf Email Sudah digunakan'));
		}

	}

	// public function process_register_byadmin(){

	// 	$email = $this->getInput('email');
	// 	$phone_number = $this->getInput('phone_number');
	// 	$fullname = $this->getInput('fullname');
	// 	$security_code = $this->getInput('security_code');
	// 	$confirm_security_code = $this->getInput('confirm_security_code');
	// 	$level_id = $this->getInput('level_id');

	// 	if($email =='' || $phone_number ==''|| $fullname ==''|| $security_code ==''|| $level_id ==''){
	// 		log_message('debug','process_register_byadmin some data empty');
	// 		echo json_encode(array('status' => 301, 'message' => 'Form data tidak lengkap'));
	// 		exit();
	// 	}

	// 	var_dump($_POST); die();

	// 	$user = $this->login_model->get_by_email($email);

	// 	if($user==0){

	// 		$this->db->trans_begin();

	// 		$dataexc = array(
	// 			'username' => $this->regex->_genRegex($email,'RGXQSL'),
	// 			'phone_no' => $this->regex->_genRegex($phone_number,'RGXQSL'),
	// 			'password' => $this->bcrypt->hash_password($security_code),
	// 			'fullname' => $this->regex->_genRegex($fullname,'RGXQSL'),
	// 			'created_date' => date('Y-m-d H:i:s'),
	// 			'security_code' => rand(9, 9999),
	// 			'is_active' => 'Y'
	// 		);

	// 		log_message('debug','process_register_byadmin dataexc : '.json_encode($dataexc));

	// 		if(isset($_POST['level_id']))$dataexc['level_id'] = $this->getInput('level_id');
				
	// 		/*save post data*/
	// 		$newId = $this->login_model->save_acc_register($dataexc);

	// 		/*get new data register*/
	// 		$newData = $this->login_model->get_by_id($newId);

	// 		/*create key */
	// 		$keyexec = array(
	// 			'user_id' => $newId,
	// 			'key' => sha1(date('mYd').$newData->username),
	// 			'level' => 1,
	// 			'ip_addresses' => $this->get_client_ip(),
	// 			'date_created' => date('Y-m-d H:i:s')
	// 		);

	// 		log_message('debug','process_register_byadmin keyexec : '.json_encode($keyexec));

	// 		$this->login_model->create_key($keyexec);
			
	// 		// /*send notification by sms*/

	// 		// $config_sms = array(
	// 		//     'from' => 'Hydromart',
	// 		//     'phone' => $newData->phone_no,
	// 		//     'message' => '(no-reply) Hydromart : Kode Verifikasi anda '.$newData->security_code.'',
	// 		//     );

	// 		// $send_sms = $this->api->adsmedia_send_sms($config_sms);
			
	// 		// /*end send notification by sms*/


	// 		if ($this->db->trans_status() === FALSE)
	// 		{
	// 			log_message('debug','process_register_byadmin gagal');
	// 			$this->db->trans_rollback();
	// 			echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
	// 		}
	// 		else
	// 		{
	// 			log_message('debug','process_register_byadmin sukses');
	// 			$this->db->trans_commit();
	// 			echo json_encode(array('status' => 200, 'message' => 'Sukses registrasi'));
	// 			// $hash_security_code = $this->bcrypt->hash_password($dataexc['security_code']);
	// 			// echo json_encode(array('status' => 200, 'message' => 'Silahkan Verifikasi Code yang dikirimkan via SMS', 'verifikasi_code' => $dataexc['security_code'], 'uid' => $hash_security_code,'id' => $newId ));
	// 		}
	// 	}else{
	// 		log_message('debug','process_register_byadmin email used');
	// 		echo json_encode(array('status' => 301, 'message' => 'Maaf Email Sudah digunakan'));
	// 	}

	// }
	
	
	public function send_sms()
    {

		/*get new data register*/
		$newData = $this->login_model->get_by_id($this->getInput('id'));

		/*send notification by sms*/

         $config_sms = array(
             'from' => 'Hydromart',
             'phone' => $newData->phone_no,
             'message' => '(no-reply) Hydromart : Kode Verifikasi anda '.$newData->security_code.'',
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
            echo json_encode(array('status' => '200', 'message' => 'Verifikasi berhasil, kami akan segera menghubungi anda setelah akun anda disetujui'));
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