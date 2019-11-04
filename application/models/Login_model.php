<?php
class Login_model extends CI_Model {

	function __construct() {

		parent::__construct();

	}

	
	private $log_file = '././log/login.log';
	
	public function log($data, $level="INFO") {

		date_default_timezone_set('Asia/Jakarta');

		$data = json_encode($data);
		$txt = '"'.date('Y-m-d H:i:s') . '", "'.$level.'"';
		$txt .= ", ". $data ."\n\r";

		if ( ! @write_file($this->log_file, $txt, 'a'))
		{
		    @error_log('Unable to write the Campaign log file : ' . $this->log_file, 0);
		}
		

	}
	
	public function check_account($usr, $pass) {
        /*get hash password*/
        $data = $this->get_hash_password($usr);
        /*validate account*/
        if($data){
            
            if($this->bcrypt->check_password($pass,$data->password)){
                return $data;
            }else{
                throw new Exception("Invalid Username or Password", 1);
                
            }
        }else{
            throw new Exception("Invalid Username or Password", 1);
        }
        
    }

    public function get_hash_password($usr){
        $query = $this->db->select('user.user_id, user.username, user.password, user.last_logon, user.fullname, user.phone_no,user.level_id , user_profile.path_photo')
                          ->join('user_profile','user_profile.user_id=user.user_id','left')
                          ->get_where('user', array('username' => $usr, 'user.is_active' => 'Y','user.is_approved' => 'Y'))->row();
                          
        if($query){
            return $query;
        }else{
            return false;
        }
    }

    public function generate_token($user_id){

        $static_str='Login';
        $currenttimeseconds = date("mdY_His");
        $token_id=$static_str.$user_id.$currenttimeseconds;
        $data = array(
                 'token' => md5($token_id),
                 'type' => $static_str,
                 'created_date' => date('Y-m-d H:i:s'),
                 'user_id' => $user_id,
                 );
        $this->db->insert('token', $data);
        return md5($token_id);
    }

    public function clear_token($user_id){
        return $this->db->delete('token', array('user_id' => $user_id));
    }

    public function get_user_profile($user_id){
        
        $profile = $this->db->get_where('user_profile', array('user_id' => $user_id))->row();

        if(!empty($profile)){
            return $profile;
        }else{
            return false;
        }
	}
	
	public function get_key($user_id)
	{
		$key = $this->db->get_where('keys', array('user_id' => $user_id))->row();

        if(!empty($key)){
            return $key->key;
        }else{
            return false;
        }
	}

	public function last_logon($username,$password)
	{
		return $this->db->update('user', array('last_logon' => date('Y-m-d H:i:s')), array('username' => $username,'password' => $password) );
    }
    
    public function create_key($data)
    {
        if($this->db->insert('keys', $data) ){
           
            return $this->db->insert_id();
 
         }else{
 
            throw new Exception("Failed to create key");
 
         }
    }

	public function update_key($user_id,$username)
	{
		$key = sha1(date('mYd').$username);
		$this->db->update('keys', array('key' => $key), array('user_id' => $user_id) );
		return $key;
    }
    
    public function get_by_email($id){

        $query = $this->db->get_where('user', array('username' => $id,'is_active' => 'Y'));
        return $query->num_rows();

    }

    public function save_acc_register($dataexc){
        if($this->db->insert('user', $dataexc) ){
           
           return $this->db->insert_id();

        }else{

           throw new Exception("Failed to create user");

        }
       
    }

    public function get_by_id($id){

        $query = $this->db->get_where('user', array('user_id' => $id));
        return $query->row();

    }

    public function verifikasi_security_code($security_code){
        $query = $this->db->get_where('user', array('security_code' => $security_code));
        return $query->result();
    }

    public function update_status_account($ref_id,$flag_active){
        return $this->db->update('user', array('is_active' => $flag_active), array('user_id' => $ref_id) );
    }

}