<?php
class Profiles_model extends CI_Model {

	private $salt_key = 'API@CMS2018#';

	public $input = array();

	public $TOKEN_ID;
	public $MSISDN;
	public $IMEI;
	public $TOKEN;
	public $TOKEN_RAW;
	public $TOKEN_ACTIVE;
	public $SOURCE_IP;
	public $DEVICE_TYPE;
	public $CHANNEL;
	public $CREATED_DATE;
	public $MODIFIED_DATE;
	public $LAST_ACTIVITY;
	public $CREATED_BY;
	public $MODIFIED_BY;

	function __construct() {
		parent::__construct();

		$this->load->database();
	}


	public function validateProfile($name) 
	{
		$sql = 'SELECT PROFILE_NAME FROM CLM_PROFILE WHERE upper(PROFILE_NAME) = upper(?)';
		$query = $this->db->query($sql, array($name));
		if($query->num_rows()<1) {
			return false;
		}
		$row = $query->row_array();
		return true;
	}

	/*public function validateMsisdn($msisdn) 
	{
		$sql = 'SELECT MSISDN, SECRETKEY FROM SECRETKEY WHERE MSISDN = ?';
		$query = $this->db->query($sql, array($msisdn));
		if($query->num_rows()<1) {
			return false;
		}
		$row = $query->row_array();
		return $row['SECRETKEY'];
	}*/


	function RandStr($length) {
		$characters = 'abcdefghijklmnopqrstuvwxyz1234567890'; //ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$random = str_shuffle($randomString);
		return $random;
	}


	public function addProfile($profilename)
	{

		$PROFILE_NAME		= urldecode($profilename);
		$UUID				= uniqid();//$this->RandStr('8').'-'.$this->RandStr('4').'-'.$this->RandStr('4').'-'.$this->RandStr('4').'-'.$this->RandStr('12');
		$CREATED_DATE	= date('Y-m-d H:i:s');
		$CREATED_BY    = 'API';
		
		
		$sql = 'INSERT INTO "CLM_PROFILE" ("UUID", "PROFILE_NAME", "CREATED_DATE", "CREATED_BY") VALUES (?, ?, TO_DATE(?, \'SYYYY-MM-DD HH24:MI:SS\'), ?)';
		$query = $this->db->query($sql, array(
			$UUID,
			$PROFILE_NAME,
			$CREATED_DATE,
			$CREATED_BY,			
		));

		if(!$this->db->affected_rows()) {
			return false;
		}	
		return true;	
	}

}