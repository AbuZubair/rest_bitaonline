<?php
class Campaign_model extends CI_Model {

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

	
	private $log_file = '/var/www/html/cms2/log/API_log/campaign.log';
	private $cdr_file = '/var/www/html/cms2/log/API_log/campaign.cdr';

	private function log($data, $level="INFO") {

		date_default_timezone_set('Asia/Jakarta');

		$data = json_encode($data);
		$txt = '"'.date('Y-m-d H:i:s') . '", "'.$level.'"';
		$txt .= ", ". $data ."\n\r";

		if ( ! @write_file($this->log_file, $txt, 'a'))
		{
		    @error_log('Unable to write the Campaign log file : ' . $this->log_file, 0);
		}

		if($level=="CDR") {
			$txt = '"'.date('Y-m-d H:i:s') . '", "'.$level.'". ' . implode('","', $data) .'"'."\n\r";
			if ( ! @write_file($this->cdr_file, $txt, 'a'))
			{
			    @error_log('Unable to write the Campaign CDR file : ' . $this->cdr_file, 0);
			}
		}
	}
	
	

	public function validateCampaign($id) 
	{
		$sql = 'SELECT CAMPAIGN_ID FROM CAMPAIGN WHERE CAMPAIGN_ID = ?';
		$query = $this->db->query($sql, array($id));
		if($query->num_rows()<1) {
			return false;
		}
		$row = $query->row_array();
		return true;
	}

	public function validateMsisdn($msisdn) 
	{
		$sql = 'SELECT MSISDN, GCM_ID FROM SECRETKEY WHERE MSISDN = ? and GCM_ID is not null';
		$query = $this->db->query($sql, array($msisdn));
		if($query->num_rows()<1) {
			return false;
		}
		$row = $query->row_array();
		return $row['GCM_ID'];
	} 
	
	function getPromoImages($id){
		$sql = 'SELECT BANNER_PATH FROM CAMPAIGN_BANNER WHERE CAMPAIGN_ID = ? and BANNER_TYPE=\'IMG_PRODUCT_PORTRAIT\'';
		$query = $this->db->query($sql, array($id));
		if($query->num_rows()<1) {
			return '';
		}
		$row = $query->row_array();
		return $row['BANNER_PATH'];
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
 

	public function addPushNotification($secretkey,$id,$msg)
	{

		$PUSH_MESSAGE		= '-';
		$GCM_URL			= $secretkey;	
		$APN_URL			= $secretkey;
		$APN_PAYLOAD		= rawurldecode($msg);
		$GCM_PAYLOAD		= rawurldecode($msg);
		$IS_SEND			= '0';
		$UUID				= $this->RandStr('8').'-'.$this->RandStr('4').'-'.$this->RandStr('4').'-'.$this->RandStr('4').'-'.$this->RandStr('12');
		$NOTIFICATION_DATE	= date('Y-m-d H:i:s');
		$BROADCAST_ID		= '0';
		$LINK				= 'http://bimaplus.tri.co.id/product?id='.$id;
		$ICON				= $this->getPromoImages($id);
		$TOP_BANNER			= $this->getPromoImages($id);
		
		// return true;
		// die();

		$sql = 'INSERT INTO NOTIFICATION_PUSH (PUSH_MESSAGE, GCM_URL, APN_URL, APN_PAYLOAD, GCM_PAYLOAD, IS_SEND, UUID, NOTIFICATION_DATE, BROADCAST_ID, LINK, ICON, TOP_BANNER) VALUES (?, ?, ?, ?, ?, ?, ?, TO_DATE(?, \'SYYYY-MM-DD HH24:MI:SS\'), ?, ?, ?, ?)';
		$this->log('Insert Into Notification_PUSH : ' .$sql . 'Params LINK : '.$LINK.' TOP BANNER :'. $TOP_BANNER.' msg :'. $APN_PAYLOAD. ' secre : ' .$secretkey);
		$query = $this->db->query($sql, array(
			$PUSH_MESSAGE,
			$GCM_URL,
			$APN_URL,
			$APN_PAYLOAD,
			$GCM_PAYLOAD,
			$IS_SEND,
			$UUID,
			$NOTIFICATION_DATE,
			$BROADCAST_ID,
			$LINK,
			$ICON,
			$TOP_BANNER
		));

		if(!$this->db->affected_rows()) {
			return false;
		}	
		return true;	
	}

}