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
        log_message('debug','check_account - > '.json_encode($data));
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
    
    public function get_menu_($level_id){
        
        $this->db->select('a.*');
        $this->db->from('app_program a');
        $this->db->join('user_role b','b.program_id = a.program_id');
        $this->db->where(array('a.is_active' => 'Y','b.level_id' => $level_id));
        $this->db->order_by('a.counter','ASC');
        $query = $this->db->get()->result();
             
        if(!empty($query)){
            return $query;
        }else{
            return false;
        }
    }
    

    public function get_menu($level_id){
        $getData = [];
        $qry = "SELECT app_program.* FROM user_role
                LEFT JOIN app_program ON app_program.program_id=user_role.program_id  
                WHERE user_role.level_id IN (".$level_id.") AND app_program.level_program=1 order by counter ASC";
        $res = $this->db->query($qry)->result_array();
        
        foreach ($res as $key => $value)
        {
            $result[] = array(
                'program_id' => $value['program_id'],
                'program_name' => $value['program_name'],
                'program_parent_id' => $value['program_parent_id'],
                'link' => $value['link'],
                'level_program' => $value['level_program'],
                'counter' => $value['counter'],
                'is_active' => $value['is_active']
            );
        }

        foreach ($result as $k => $v) {
            $submenu = $this->search_submenu_by_group($v['program_id'], $level_id);
            $arr = array(
                'program_id' => $v['program_id'],
                'program_name' => $v['program_name'],
                'program_parent_id' => $v['program_parent_id'],
                'link' => $v['link'],
                'level_program' => $v['level_program'],
                'counter' => $v['counter'],
                'is_active' => $v['is_active'],
                'submenu' => $submenu
            );
           // $arr['submenu'] = $submenu;
            $getData[] = $arr;
        }

        return $getData;
    }

    public function search_submenu_by_group($program_id, $level_id){
        $db = $this->load->database('default', TRUE);
        $sess = $this->load->library('session');
        $qry = "SELECT app_program.* FROM user_role
                LEFT JOIN app_program ON app_program.program_id=user_role.program_id  
                WHERE user_role.level_id IN (".$level_id.") AND app_program.program_parent_id='".$program_id."' order by counter ASC";
        return $db->query($qry)->result();
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