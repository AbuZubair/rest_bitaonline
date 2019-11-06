<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends REST_Controller {
	public function __construct() {

		parent::__construct();
		$this->load->model('User_model');
    
	}

	public function get_all_user_get()
    {
		
        $data = $this->User_model->get_all_user($this->get('q'));

        $i=0;
        foreach ($data as $value) {
            $kota_ = substr(strstr($value->name," "), 1);
            $kota = strtolower($kota_);
            $data[$i]->name = ucwords($kota);
            $i++;
        }

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
	}
	

}
?>