<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends REST_Controller {
	public function __construct() {

		parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Login_model','login_model');
    
	}

	public function get_all_user_get()
    {
        $id = $this->get('id');

        $data = $this->User_model->get_all_user($id);

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }

    public function get_userlevel_get()
    {
        
        $data = $this->User_model->get_userlevel();

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }
    
    
    public function get_detail_get()
    {
        $n = $this->get('n');
        $data = $this->User_model->get_detail($n);
        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }

    public function process_register_post(){

        $post = json_decode(file_get_contents('php://input'), true);
        
		$user = $this->login_model->get_by_username($post['username']);

		if($user==0){

			$this->db->trans_begin();

			$dataexc = array(
				'username' => $this->regex->_genRegex($post['username'],'RGXQSL'),
				'phone_no' => $this->regex->_genRegex($post['phone_number'],'RGXQSL'),
                'fullname' => $this->regex->_genRegex($post['fullname'],'RGXQSL'),
                'level_id' => $post['level_id'],
                'is_active' => 'Y',
			);
                
            if(isset($post['user_id']) AND $post['user_id']!=''){
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $this->User_model->update('user',$dataexc, array('user_id' => $post['user_id']));
                $newId = $post['user_id'];

                $message = "User berhasil di update";
            }else{

                $dataexc['password'] = $this->bcrypt->hash_password($post['security_code']);
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
            }		

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
			}
			else
			{
				$this->db->trans_commit();
				echo json_encode(array('status' => 200, 'message' => 'Proses berhasil, '.$message.'', 'id' => $newId ));
			}
		}else{
			echo json_encode(array('status' => 301, 'message' => 'Maaf Username Sudah digunakan'));
		}

    }

    public function get_judul_get()
    {
        $id = $this->get('id');
        $level = $this->get('level');

        if($level==2){
            $data = $this->login_model->get_judul($id);
        }else if($level==3){
            $data = $this->login_model->get_judul_by_dosen($id);
        }

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => ($data)?$data:''),200);
    		
    }

    public function get_bimbingan_get()
    {
        $id = $this->get('id');
        $level = $this->get('level');

        $data = $this->login_model->get_jadwalbimbingan($id,$level);
        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => ($data)?$data:''),200);
    		
    }
    
}
?>