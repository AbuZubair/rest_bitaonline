<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MX_Controller {

    function __construct() {
        parent::__construct();
        /*load libraries*/
        $this->load->library('bcrypt');
        $this->load->library('logs');
        $this->load->library('Form_validation');
        /*load model*/
        $this->load->model('login_model');
        //$this->load->model('setting/Tmp_apps_config_model');
        //header('Access-Control-Allow-Origin: *');
        
    }

    public function index() {

        $this->load->view('index', $data);

    }
    
    public function process(){
        //header('Access-Control-Allow-Origin: *');

        /*post username*/
        $username = $this->regex->_genRegex($this->input->post('username'), 'RGXQSL');

        /*hash password bcrypt*/
        $password = $this->input->post('password');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        
        try {
            
             /*check username and password exist*/
            $result = $this->login_model->check_account($username,$password);

            /*clear token existing or before*/
            $this->login_model->clear_token($result->user_id);

            if($this->login_model->get_user_profile($result->user_id) != false){

                $user_profile = $this->login_model->get_user_profile($result->user_id);

                $following = $this->master->thousandsCurrencyFormat($user_profile->following);
                $user_profile->following = $following;
        
                $followers = $this->master->thousandsCurrencyFormat($user_profile->followers);
                $user_profile->followers = $followers;

            }

            /*update last logon user*/
            $this->db->query("UPDATE masakindo_user SET last_logon=date('Y-m-d H:i:s') WHERE username='".$result->username."' AND password='".$result->password."'");

            $data = array(
                        'logged' => TRUE, 
                        'token' => $this->login_model->generate_token($result->user_id), 
                        'user' => $result, 
                        'user_profile' => isset($user_profile)?$user_profile:[]
                    );

            $response = $this->ws_auth->success_response();

            $arr_merge = array_merge($response, $data);

           // print_r($arr_merge);

            echo json_encode( $arr_merge );

        } catch (Exception $e) {
            
            $response = $this->ws_auth->failed_response( $e );

            echo json_encode( $response );

        }
        

    }

    public function logout()
    {   
        $this->login_model->clear_token($this->session->userdata('user')->user_id);
    }

    
}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

