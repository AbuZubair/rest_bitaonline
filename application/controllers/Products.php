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

    public function get_all_products_admin_get()
    {
        $q = ($this->get('q'))?$this->get('q'):'';
        $n = $this->get('n');
        $data = $this->Products_model->get_all_active_nonactive_products($n,$q);

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

    public function get_detail_admin_get()
    {
        $n = $this->get('n');
        $data = $this->Products_model->get_detail_admin($n);
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
    
    
    public function process_insert_product_post()
    {
        log_message('debug','process_insert_product_post data : '.json_encode($this->post()));
        $user_id = $this->post('user_id');
        
        $data = array(
            'category' => $this->post('category'),
            'brand' => $this->post('brand'),
            'name' => $this->post('name'),
            'type' => $this->post('type'),
            'subtype' => $this->post('subtype'),
            'price' => $this->post('price'),
            'img_url' => $this->post('img_url'),
            'is_active' => $this->post('is_active'),
            'created_by' => $this->post('user_id'),
            'created_date' => date('Y-m-d H:i:s')
        );

        if($user_id = ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     
        else {
            try {
      
                $this->db->trans_begin();
    

                $insert = $this->Products_model->save('product', $data);

        
                log_message('debug','process_insert_product_post submitdata : '.json_encode($insert));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_insert_product_post submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();
                    log_message('debug','process_insert_product_post submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $insert),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_insert_product_post submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }
    }

    public function process_update_product_post()
    {
        log_message('debug','process_update_product_post data : '.json_encode($this->post()));
        $id = $this->post('id');
        $user_id = $this->post('user_id');
        
        $data = array(
            'category' => $this->post('category'),
            'brand' => $this->post('brand'),
            'name' => $this->post('name'),
            'type' => $this->post('type'),
            'subtype' => $this->post('subtype'),
            'price' => $this->post('price'),
            'img_url' => $this->post('img_url'),
            'is_active' => $this->post('is_active'),
            'created_by' => $this->post('user_id'),
            'created_date' => date('Y-m-d H:i:s')
        );

        if($id =='' && $user_id = ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     
        else {
            try {
      
                $this->db->trans_begin();
    

                $update = $this->Products_model->update('product', $data, array('id' => $id));

        
                log_message('debug','process_update_product_post submitdata : '.json_encode($update));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_update_product_post submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();
                    log_message('debug','process_update_product_post submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $id),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_update_product_post submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }
    }
}
?>