<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriberProfile extends CI_Controller {
	
	public $input = array();

	private $log_file = '/var/www/html/cms2/log/API_log/subprofiles.log';
	private $cdr_file = '/var/www/html/cms2/log/API_log/subprofiles.cdr';

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
		$this->load->model('SubscriberProfile_model');
						
		// $this->Token->setInput($this->input);		

	}


	public function index()
	{
		$this->log('call Profiles API index');
		echo '{"code":"200", "status":"success", "name":"Profiles API", "version":"0.0.1"}';

		//$this->load->view('api');
	}

	public function clmNew($msisdn,$profile)
	{
		$a_result=array();
		//$a_package=array($msisdn);
		if(empty($profile) || empty($msisdn)) 
		{
			return false;
		}
		
		 $profileSingle = explode("|",urldecode($profile));
		 foreach ($profileSingle as $prof) {
                                 
                               // array_push($a_package,urldecode($prof));
			 	$this->log('call Profiles API clm | profile : '.urldecode($prof));

				//check campaign id and msisdn
				$profile_check = $this->SubscriberProfile_model->validateProfile(urldecode($prof));
				$msisdn_check = $this->SubscriberProfile_model->validateMsisdn($msisdn);
				
				
				if($msisdn_check==false){
					$this->log('No found MSISDN : '.$msisdn);		
					//array_push($a_result,$msisdn.'-'.urldecode($prof)." : MSISDN doesn't exist");                                        
                                         $myObj=array(urldecode($prof) => "MSISDN doesn't exist");                                         
                                         array_push($a_result, $myObj);
					 break;
				}
				
				if(($profile_check==false))
				{
					$this->log('No found profile : '.urldecode($prof));					
					//echo "campaign and msisdn not found";
					//$response['status_code'] = "99";
					//$response['status_message'] = "Already Got Profile ".$prof;	
					//array_push($a_result,$msisdn.'-'.urldecode($prof)." : Profile doesn't exist");				
                                         $myObj=array(urldecode($prof) => "Profile doesn't exist");                                         
                                         array_push($a_result, $myObj);
					//break;
					//return false;
				}
				else{		
						$this->log('profile msisdn par :'.$msisdn.' id :'.$profile_check);	
						$msisdnprofile_check = $this->SubscriberProfile_model->validateMsisdnProfile($msisdn,$profile_check);				
						$this->log('profile msisdn cec :'.$msisdnprofile_check);
						if($msisdnprofile_check==true){
							$this->log('MSISDN  : '.$msisdn . ' and profile : '.urldecode($prof).' already exist');		
							//array_push($a_result,$msisdn . '-'.urldecode($prof).' : already exist');
                                                        $myObj=array(urldecode($prof) => "Already exist");                                         
                                                        array_push($a_result, $myObj);
							continue;
						}
						
						$add_profiles 	= $this->SubscriberProfile_model->addProfile($msisdn,$profile_check);
						if($add_profiles==false)
						{
							$this->log('error on submit data for profile : '.urldecode($prof). ' - Msisdn : '.$msisdn);							
							//array_push($a_result,$msisdn.'-'.urldecode($prof)." : Error while inserting");										
                                                        $myObj=array(urldecode($prof) => "Error while inserting");                                         
                                                        array_push($a_result, $myObj);
							//break;
							//return false;			
						}
						else{
							$this->log('Success on submit data for profile : '.urldecode($prof));							
							//array_push($a_result,$msisdn.'-'.urldecode($prof)." : Success inserted");	
						}        $myObj=array(urldecode($prof) => "Success inserted");                                         
                                                        array_push($a_result, $myObj);
				}
				
				
		 }
		
                        //array_push($a_package,$a_result);
                        $a_package=array($msisdn=>$a_result);
			$response['result']=$a_package;
                        
			$json_response = json_encode($response);
			echo $json_response;
		
	}

	public function ping()
	{
		$this->log('Call API ping');
		echo '{"code":"200", "status":"success", "message":"ping"}';
	}
        
        public function clm($profile)
	{
		$a_result=array();
		//$a_package=array($msisdn);
                $a_package=array();
                $a_all=array();
		if(empty($profile)) 
		{
			return false;
		}
		
                 $this->log('call Profiles API clm | Parameter : '.urldecode($profile));
		 $profileSingle = explode("|||",urldecode($profile));                
		 foreach ($profileSingle as $prof) {                    
                    $idx1 = strpos(urldecode($prof), '|');
                    $this->log('call Profiles API clm | Index Start profiles : '.$idx1);
                    $msisdn= substr(urldecode($prof),0, $idx1);  
                    $allprofile = substr(urldecode($prof),$idx1+1,strlen(urldecode($prof)));
                     $this->log('call Profiles API clm | MSISDN : '.$msisdn);
                      $this->log('call Profiles API clm | All Profiles : '.$allprofile);
                    $msisdnSingle = explode("|",urldecode($allprofile));                      
                           foreach ($msisdnSingle as $profiles) {     
                               // array_push($a_package,urldecode($prof));
			 	$this->log('call Profiles API clm | profile : '.urldecode($profiles));
				//check campaign id and msisdn
				$profile_check = $this->SubscriberProfile_model->validateProfile(urldecode($profiles));
				$msisdn_check = $this->SubscriberProfile_model->validateMsisdn($msisdn);
				
				
				if($msisdn_check==false){
					$this->log('No found MSISDN : '.$msisdn);		
					//array_push($a_result,$msisdn.'-'.urldecode($prof)." : MSISDN doesn't exist");                                        
                                         // $myObj=array(urldecode($profiles) => "MSISDN doesn't exist");       
                                          $a_result= "MSISDN doesn't exist"; 
                                        // array_push($a_result, $myObj);
					 break;
				}
				
				if(($profile_check==false))
				{
					$this->log('No found profile : '.urldecode($profiles));					
					//echo "campaign and msisdn not found";
					//$response['status_code'] = "99";
					//$response['status_message'] = "Already Got Profile ".$prof;	
					//array_push($a_result,$msisdn.'-'.urldecode($prof)." : Profile doesn't exist");				
                                         $myObj=array(urldecode($profiles) => "Profile doesn't exist");                                         
                                         array_push($a_result, $myObj);
					//break;
					//return false;
				}
				else{		
						$this->log('profile msisdn par :'.$msisdn.' id :'.$profile_check);	
						$msisdnprofile_check = $this->SubscriberProfile_model->validateMsisdnProfile($msisdn,$profile_check);				
						$this->log('profile msisdn cec :'.$msisdnprofile_check);
						if($msisdnprofile_check==true){
							$this->log('MSISDN  : '.$msisdn . ' and profile : '.urldecode($profiles).' already exist');		
							//array_push($a_result,$msisdn . '-'.urldecode($prof).' : already exist');
                                                        $myObj=array(urldecode($profiles) => "Already exist");                                         
                                                        array_push($a_result, $myObj);
							continue;
						}
						
						$add_profiles 	= $this->SubscriberProfile_model->addProfile($msisdn,$profile_check);
						if($add_profiles==false)
						{
							$this->log('error on submit data for profile : '.urldecode($profiles). ' - Msisdn : '.$msisdn);							
							//array_push($a_result,$msisdn.'-'.urldecode($prof)." : Error while inserting");										
                                                        $myObj=array(urldecode($profiles) => "Error while inserting");                                         
                                                        array_push($a_result, $myObj);
							//break;
							//return false;			
						}
						else{
							$this->log('Success on submit data for profile : '.urldecode($profiles));							
							//array_push($a_result,$msisdn.'-'.urldecode($prof)." : Success inserted");	
						}        $myObj=array(urldecode($profiles) => "Success inserted");                                         
                                                        array_push($a_result, $myObj);
				}				
                        }
                         $a_package=array($msisdn=>$a_result);
                         array_push($a_all,$a_package);
                         $a_result=array();
                 }
		
                        //array_push($a_package,$a_result);
                     //   $a_package=array($msisdn=>$a_result);
                       
			$response['result']=$a_all;
                        
			$json_response = json_encode($response);
			echo $json_response;
		
	}

}