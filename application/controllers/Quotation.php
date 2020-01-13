<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Quotation extends REST_Controller {

	public function __construct() {

		parent::__construct();

		$this->load->model('Quotation_model');
    
    }

	public function get_all_data_get()
    {
        $q = ($this->get('q'))?$this->get('q'):'';
        $n = $this->get('n');
        $data = $this->Quotation_model->get_all_data($n,$q);

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }
  
    public function get_detail_get()
    {
        $n = $this->get('n');
        $data = $this->Quotation_model->get_detail($n);
        //var_dump($data[0]->quotation_no); die();
        $data[0]->products = $this->Quotation_model->get_detail_products($data[0]->quotation_no);
        

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }
    

	public function process_submit_quotation_post(){

        $company_name = $this->post('company_name');
        $company_address = $this->post('company_address');
        $total_amount = $this->post('total_amount');
        $product = $this->post('product');
        $qty = $this->post('qty');
  
        if($company_name =='' || $company_address =='' || $total_amount =='' || (int)$total_amount < 1){
                $resp = array('message' => 'Data Kosong, Proses Gagal Dilakukan');
                $this->response($resp);
        }

        if(is_array(json_decode($qty))){
            if( count(json_decode($qty)) < 1 ){
                $resp = array('message' => 'Data product Kosong, Proses Gagal Dilakukan');
                $this->response($resp);
            }
        } else {
            $resp = array(
                'message' => 'Data Qty Error, Proses Gagal Dilakukan',
            );
            $this->response($resp);
        }

        if(is_array(json_decode($product))){
            if(count(json_decode($product)) < 1){
                $resp = array(
                    'message' => 'Data product Kosong, Proses Gagal Dilakukan',
                );
                $this->response($resp);
            }
        } else {
            $resp = array(
                'message' => 'Data product Error, Proses Gagal Dilakukan',
            );
            $this->response($resp);
        }

        $arrProducts = json_decode($product);
        $arrQty = json_decode($qty);
        $product_id = [];
        $unit_price = [];
        $amount = [];
        foreach ($arrProducts as $key => $value) {
            $product_id[$key] = $value->id;
            $unit_price[$key] = $value->price;
            $amount[$key] = $value->price * $arrQty[$key];
        }

        log_message('debug','process_submit_quotation_post data : '.json_encode($this->post()));
 
        try {
      
            $this->db->trans_begin();

            $quotation_no = date('ymdHis').rand(100,999);

            $submitdata = array(
                'quotation_no' => $quotation_no,
                'company_name' => $company_name,
                'company_address' => $company_address,
                'total_amount' => $total_amount,
                'valid_date' => date('Y-m-d H:i:s'),
            );
            log_message('debug','process_submit_quotation_post submitdata : '.json_encode($submitdata));

            $createInvoice = $this->Quotation_model->save('quotation',$submitdata);
            log_message('debug','process_submit_quotation_post submitdata result : '.json_encode($createInvoice));

            $k=0;
            while ($k < count($product_id)) {
                $submitdatadetail = array(
                    'quotation_no' => $quotation_no,
                    'product_id' => $product_id[$k],
                    'qty' => $arrQty[$k],
                    'unit_price' => $unit_price[$k],
                    'amount' => $amount[$k],
                );
                log_message('debug','process_submit_quotation_post submitdatadetail : '.json_encode($submitdatadetail));
    
                $createInvoicedetail = $this->Quotation_model->save('quotation_detail',$submitdatadetail);
                log_message('debug','process_submit_quotation_post submitdatadetail result : '.json_encode($createInvoicedetail));
                $k++;
            }


            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                log_message('debug','process_submit_quotation_post submitdata trans_rollback');
                $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
            }
            else
            {
                $this->db->trans_commit();
                log_message('debug','process_submit_quotation_post submitdata trans_commit');
                $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $quotation_no),200);
            }


        } catch (Exception $e) {
            log_message('debug','process_submit_quotation_post submitdata try error 500'); 
           $this->response( $e->getMessage() ,500);
        }
 
    }

    public function get_prov_get()
    {
        
        $data = $this->Profile_model->get_prov();

        $resp = array(
            'status' => 200,
            'message' => 'Proses Berhasil Dilakukan',
            'data' => $data
        );

        $this->response($resp, 200);
    }

    public function get_regency_get()
    {

        $data = $this->Profile_model->get_regency($this->get('q'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }

    public function get_district_get()
    {
        
        $data = $this->Profile_model->get_district($this->get('q'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }

    public function get_village_get()
    {
        
        $data = $this->Profile_model->get_village($this->get('q'));

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    }


}
?>










