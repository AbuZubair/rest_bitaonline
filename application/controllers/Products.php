<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends REST_Controller {
	public function __construct() {

		parent::__construct();
		$this->load->model('Products_model');
    
	}

	public function get_all_products_get()
    {
        $q = ($this->get('q'))?$this->get('q'):'';
        $n = $this->get('n');
        $data = $this->Products_model->get_all_products($n,$q);

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
	}

	public function get_rfu_get()
	{
		$data = $this->Products_model->get_rfu();

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
	}
	
    public function get_detail_get()
    {
        $n = $this->get('n');
        $data = $this->Products_model->get_detail($n);
        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
	}
    public function get_category_get()
    {
        
        $data = $this->Products_model->get_category();

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }
    
    public function get_brand_get()
    {
        
        $data = $this->Products_model->get_brand();

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }
    

}
?>