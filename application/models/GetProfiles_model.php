<?php
class GetProfiles_model extends CI_Model {

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
 

	public function getProfiles($name) 
	{
		$sql = ' select a.profile_name as name from clm_profile a, subscriber_clm_profile b  where A.UUID = B.PROFILE_ID  and B.MSISDN= ? ';
		$query = $this->db->query($sql, array($name));
		/*if($query->num_rows()<1) {
			return false;
		}*/
		$row = $query->result_array();
		return $row;
	}



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



}