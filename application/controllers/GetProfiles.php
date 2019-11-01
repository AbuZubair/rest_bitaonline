<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GetProfiles extends CI_Controller {
	
	public $input = array();

	private $log_file = '/var/www/html/cms2/log/API_log/getprofiles.log';
	private $cdr_file = '/var/www/html/cms2/log/API_log/getprofiles.cdr';

	private $status='0';
	private $status_message='';
	

	private function log($data, $level="INFO") {

		date_default_timezone_set('Asia/Jakarta');

		$data = json_encode($data);
		$txt = '"'.date('Y-m-d H:i:s') . '", "'.$level.'"';
		$txt .= ", ". $data ."\n\r";

		if ( ! @write_file($this->log_file, $txt, 'a'))
		{
		    @error_log('Unable to write the Profiles log file : ' . $this->log_file, 0);
		}

		if($level=="CDR") {
			$txt = '"'.date('Y-m-d H:i:s') . '", "'.$level.'". ' . implode('","', $data) .'"'."\n\r";
			if ( ! @write_file($this->cdr_file, $txt, 'a'))
			{
			    @error_log('Unable to write the Profiles CDR file : ' . $this->cdr_file, 0);
			}
		}
	}


	// API libraries:start
	// API libraries:start
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

	// API libraries:end
	// API libraries:end


	public function __construct() {

		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');
		header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
		header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 100000');
		$this->setHttpHeaders(200);
		$this->readInput();

		$this->load->helper('file');
		$this->load->model('GetProfiles_model');
		// $this->Token->setInput($this->input);		

	}


	public function index()
	{
		$this->log('call Profiles API index');
		echo '{"code":"200", "status":"success", "name":"Profiles API", "version":"0.0.1"}';

		//$this->load->view('api');
	}

	public function clm($msisdn)
	{
		$a_result=array();
		$a_temp=array();
		if(empty($msisdn)) 
		{
			return false;
		}
		
		 $msisdnSingle = explode("|",urldecode($msisdn));
                 if(sizeof($msisdnSingle) > 100){
                       $myObj=array("Failed" => "MSISDN count must be less than 100");                                         
                       array_push($a_temp, $myObj);
                 }
                 else{
                     foreach ($msisdnSingle as $prof) {
				$a_msisdn=array();
				$a_result=array();
				array_push($a_msisdn,$prof);
			 
			 	$this->log('call GetProfiles API clm | profile : '.urldecode($prof));

				$profile_check = $this->GetProfiles_model->getProfiles(urldecode($prof));
				
				
				
				/*if(($profile_check==false))
				{
					$this->log('No Profile for msisdn : '.urldecode($prof));										
					array_push($a_result,'No Profile for msisdn : '.urldecode($prof));									
				}
				else{*/	
					$this->log('Size : '.sizeof($profile_check));
					for($i=0; $i< sizeof($profile_check);$i++){						
						$this->log('Profile found : '.($profile_check[$i]['NAME']));										
						array_push($a_result,($profile_check[$i]['NAME']));	
						
					}
						
				//}
				//array_push($a_msisdn,$a_result);
                              //  array_push($a_temp,$a_msisdn);
                                $myObj=array($prof => $a_result);                                         
                                array_push($a_temp, $myObj);
				
                    }  
                 }
		
			$response['result']=$a_temp;
			$json_response = json_encode($response);
			echo $json_response;
		
	}

	public function ping()
	{
		$this->log('Call API ping');
		echo '{"code":"200", "status":"success", "message":"ping"}';
	}

}