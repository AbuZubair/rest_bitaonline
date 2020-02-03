<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Quotation extends REST_Controller {

	public function __construct() {

		parent::__construct();

        $this->load->model('Quotation_model');
        $this->load->library('Pdf','pdf');
		$this->load->library('mailer');
    
    }

	public function get_all_data_get()
    {
        $q = ($this->get('q'))?$this->get('q'):'';
        $n = $this->get('n');
        $id = $this->get('i');
        $data = $this->Quotation_model->get_all_data($id,$n,$q);

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }
	public function get_all_data_userselected_get()
    {
        $q = ($this->get('q'))?$this->get('q'):'';
        $n = $this->get('n');
        $id = $this->get('i');
        $data = $this->Quotation_model->get_all_data_userselected($id,$n,$q);

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    }

    

    public function get_all_data_pending_get()
    {
        $q = ($this->get('q'))?$this->get('q'):'';
        $n = $this->get('n');
        $id = $this->get('i');
        //check id permission

        $data = $this->Quotation_model->get_all_data_pending($id,$n,$q);

        $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
    		
    } 
    public function get_all_data_aprroved_get()
    {
        $q = ($this->get('q'))?$this->get('q'):'';
        $n = $this->get('n');
        $id = $this->get('i');
        //check id permission

        $data = $this->Quotation_model->get_all_data_aprroved($id,$n,$q);

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
    
    public function process_approve_quotation_post(){
        log_message('debug','process_approve_quotation_post data : '.json_encode($this->post()));
        $id = $this->post('id');
        $user_id = $this->post('user_id');
        $reward = $this->post('reward');




        if($id =='' || $user_id = ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     


        
        else {
            try {
      
                $this->db->trans_begin();
    
                $update = $this->Quotation_model->update('quotation', array('status' => (int)'1', 'reward' => $reward,  'updated_by' => $this->post('user_id'),'updated_date' => date('Y-m-d H:i:s')), array('id' => $id));

        
                log_message('debug','process_submit_quotation_post submitdata : '.json_encode($update));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_approve_quotation_post submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();

                    //get quotation email
                    $getemail = $this->Quotation_model->getQuotationEmail($id);
                    if($getemail){
                        if($getemail !==''){
                            $this->sendemail($getemail,$id);
                        }
                    }
                    
                    log_message('debug','process_approve_quotation_post submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $id),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_approve_quotation_post submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }

    }


    public function process_reject_quotation_post(){
        log_message('debug','process_reject_quotation_post data : '.json_encode($this->post()));
        $id = $this->post('id');
        $user_id = $this->post('user_id');

        if($id =='' || $user_id = ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     
        else {
            try {
      
                $this->db->trans_begin();
    
                $update = $this->Quotation_model->update('quotation', array('status' => (int)'2','updated_by' => $this->post('user_id'),'updated_date' => date('Y-m-d H:i:s')), array('id' => $id));

        
                log_message('debug','process_reject_quotation_post submitdata : '.json_encode($update));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_reject_quotation_post submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();
                    log_message('debug','process_reject_quotation_post submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $id),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_reject_quotation_post submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }

    }


    public function process_confirmpayment_quotation_post(){
        log_message('debug','process_confirmpayment_quotation data : '.json_encode($this->post()));
        $id = $this->post('id');
        $user_id = $this->post('user_id');
        if($id =='' || $user_id = ''){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
        }     
        else {
            try {
      
                $this->db->trans_begin();
    
                $update = $this->Quotation_model->update('quotation', array('payment' => (int)'1','updated_by' => $this->post('user_id'),'updated_date' => date('Y-m-d H:i:s')), array('id' => $id));

        
                log_message('debug','process_confirmpayment_quotation submitdata : '.json_encode($update));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    log_message('debug','process_confirmpayment_quotation submitdata trans_rollback');
                    $this->response(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'),301);
                }
                else
                {
                    $this->db->trans_commit();


                    //get quotation email
                    $getemail = $this->Quotation_model->getQuotationEmailUser($id);
                    if($getemail){
                        if($getemail !==''){
                            $this->sendemail($getemail,$id);
                        }
                    }

                    log_message('debug','process_confirmpayment_quotation submitdata trans_commit');
                    $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $id),200);
                }
    
    
            } catch (Exception $e) {
                log_message('debug','process_confirmpayment_quotation submitdata try error 500'); 
               $this->response( $e->getMessage() ,500);
            }
        }

    }

    




	public function process_submit_quotation_post(){

        $company_name = $this->post('company_name');
        $company_address = $this->post('company_address');
        $product = $this->post('product');
        $productManual = $this->post('productManual');
        $up = $this->post('up');
        $email = $this->post('email');
        $jabatan = $this->post('jabatan');



        $user_id    = $this->post('user_id');
        

        if($company_name =='' || $company_address =='' || $up =='' || $email =='' || $jabatan ==''){
            $resp = array('message' => 'Data Tidak Lengkap, Proses Gagal Dilakukan');
            $this->response($resp);
        }
        if (!is_array(json_decode($product))) {
            $resp = array('message' => 'Data Produk Kosong, Proses Gagal Dilakukan');
            $this->response($resp);
        }
        if (!is_array(json_decode($productManual))) {
            $resp = array('message' => 'Data Produk Input Manual Kosong, Proses Gagal Dilakukan');
            $this->response($resp);
        }
        if($user_id ==''){
            $resp = array('message' => 'Data User Kosong, Proses Gagal Dilakukan');
            $this->response($resp);
        }     

        $product_id = [];
        $unit_price = [];
        $amount = [];
        $name = [];
        $img = [];
        $qty = [];   
        $total_amount = 0;
        $err = 0;
        $x = 0; // next array for manual input
        foreach (json_decode($product) as $key => $value) {
            $name[$key] = $value->name;
            $img[$key] = $value->img;
            $product_id[$key] = $value->id;
            $unit_price[$key] = $value->price;
            $qty[$key] = $value->qty;
            $amount[$key] = $value->price * $value->qty;

            $total_amount = $total_amount + $amount[$key];

            if($value->price =='' || $value->price ==0  || (int)$value->price < 1 || $value->qty =='' || $value->qty ==0 || (int)$value->qty < 1){
                $err++;
            }
            $x++;
        }


        foreach (json_decode($productManual) as $key => $value) {
            $name[$x] = $value->name;
            $img[$x] = $value->img;
            $product_id[$x] = $value->id;
            $unit_price[$x] = $value->price;
            $qty[$x] = (string)$value->qty;
            $amount[$x] = $value->price * $value->qty;

            $total_amount = $total_amount + $amount[$x];

            if($value->price =='' || $value->price ==0  || (int)$value->price < 1 || $value->qty =='' || $value->qty ==0 || (int)$value->qty < 1){
                $err++;
            }
            $x++;
        }




        if($err>0){
            $resp = array('message' => 'Data tidak valid, Proses Gagal Dilakukan');
            $this->response($resp);
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
                'customer_id' => $user_id,
                'valid_date' => date('Y-m-d H:i:s'),
            );
            log_message('debug','process_submit_quotation_post submitdata : '.json_encode($submitdata));

            $createInvoice = $this->Quotation_model->save('quotation',$submitdata);
            log_message('debug','process_submit_quotation_post submitdata result : '.json_encode($createInvoice));
            $get_last_insert_id = $this->db->insert_id();

            $k=0;
            while ($k < count($product_id)) {
                $submitdatadetail = array(
                    'quotation_no' => $quotation_no,
                    'product_id' => $product_id[$k],
                    'name' => $name[$k],
                    'img' => $img[$k],
                    'qty' => $qty[$k],
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




                    //* pdf & email //
                    $data =$this->get_detail_quotation_emailpdf($get_last_insert_id);
                    log_message('debug','process_submit_quotation_post email pdf get_last_insert_id: '.json_encode($get_last_insert_id));
                    log_message('debug','process_submit_quotation_post email pdf getdata result: '.json_encode($data));
                    if($data){
                        //$this->load->view('quotation_pdf',$data);
                        // $this->load->view('quotaion_view',$data);
            
                            /*Sample Send Email with Attach */
            
                                /*save pdf*/
            
                                $html = $this->load->view('quotation_pdf',$data,true);
            
                                $path_file = $this->pdf->print_pdf($html,'Title'.date('YmdHis').'');
            
                                /*kirim email */
            
                                // $data['data'] = $data;
                                $html_email = $this->load->view('quotaion_view',$data,true);
            
                                $this->mailer->sendemailWithAttach(EMAIL_ADMIN,$html_email,'Quotation',$path_file); 
            
                            /*******************************/
            
                    } else {
                        //log message error
                        log_message('debug','process_submit_quotation_post email pdf getdata false');
                    }
                     //* /pdf & email //
                    



                $this->response(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $quotation_no),200);
            }


        } catch (Exception $e) {
            log_message('debug','process_submit_quotation_post submitdata try error 500'); 
           $this->response( $e->getMessage() ,500);
        }
 
    }

    
    public function get_detail_quotation_emailpdf($n)
    {
        //$n = $this->get('n');
        //$n = 7;
        $data = $this->Quotation_model->get_detail($n);
        //var_dump($data[0]->quotation_no); die();

        if(count($data)>0){
            $data[0]->products = $this->Quotation_model->get_detail_products($data[0]->quotation_no);
            // $this->response(array('status' => 200, 'message' => 'Sukses', 'data' => $data),200);
            return $data[0];
        } else {
            return false;
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

    public function sendemail($email,$id){
        log_message('debug','sendemail'); 
        $data =$this->get_detail_quotation_emailpdf($id);
        log_message('debug','get data email : '.json_encode($data)); 
        
        $html = $this->load->view('quotation_pdf',$data,true);
        $path_file = $this->pdf->print_pdf($html,'Title'.date('YmdHis').'');
            
        $html_email = $this->load->view('quotaion_view',$data,true);
            
        $this->mailer->sendemailWithAttach($email,$html_email,'Quotation',$path_file); 
        log_message('debug','path : '.json_encode(date('YmdHis'))); 
    }

    public function get_email_get()
    {
        echo "zz";
        //$this->load->view('quotation_pdf');
        $data=array();
        $this->load->view('quotaion_view',$data,true);
        // die();
        
        //         /*Sample Send Email with Attach */

        //             /*save pdf*/
        //             $data = array();
        //             $html = $this->load->view('quotation_pdf',$data,true);

        //             $path_file = $this->pdf->print_pdf($html,'Title'.date('YmdHis').'');

        //             /*kirim email */

        //             $data['data'] = $data;
        //             $html_email = $this->load->view('quotaion_view',$data,true);

        //             $this->mailer->sendemailWithAttach(EMAIL_ADMIN,$html_email,'Quotation',$path_file); 

        //         /*******************************/
    }

}
?>










