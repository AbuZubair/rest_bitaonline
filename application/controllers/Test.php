<?php 
// include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_Controller  {

	public function __construct() {

		parent::__construct();

        $this->load->model('Quotation_model');
        $this->load->library('Pdf','pdf');
		$this->load->library('mailer');
    
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
    

    public function get_email_get()
    {
        $x=7;
        $data =$this->get_detail_quotation_emailpdf($x);
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
        }
       

    }

}
?>










