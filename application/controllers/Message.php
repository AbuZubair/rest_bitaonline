<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Message extends REST_Controller {
	public function __construct() {

		parent::__construct();
		
		
		$this->load->model('Message_model');
    
	}


	// GET
	public function index_get(){

		$data_siswa = array(
			"error_message" => "something wrong"
		);

		$this->response($data_siswa, 500);
	}

	// POST
	public function index_post(){

		$data_siswa = array(
			"nama" => "degananda",
			"umur" => "22"
		);

		$this->response($data_siswa, 200);
	}


	// DELETE
	public function index_delete(){

		$data_siswa = array(
			"nama" => "degananda",
			"umur" => "22"
		);

		$this->response($data_siswa, 200);
	}

	public function get_room_by_id_get()
    {
		$data = $this->Message_model->get_room_by_id($this->get('q'));
        $sum_unread=$unread=0;

		if(!empty($data)){
			foreach ($data as $value) {
				if($value->unread!=null){
					$sum_unread += $value->unread;
				}
			}

			$unread = $this->master->thousandsCurrencyFormat($sum_unread);
		}
               
		
		$resp = array(
			'data' => $data,
			'sum_unread' =>$unread,
		);

		
        $this->response($resp, 200);
    }


}
?>