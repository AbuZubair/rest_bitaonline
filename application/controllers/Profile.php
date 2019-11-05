<?php 
include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends REST_Controller {

	public function __construct() {

		parent::__construct();

		$this->load->model('Profile_model');
    
    }

	public function process_profile_user_post(){

        // form validation
        $fullname = $this->post('fullname');
        $pob = $this->post('pob');
        $dob = $this->post('dob');
        $address = $this->post('address');
        $province = $this->post('province');
        $regency = $this->post('regency');
        $district = $this->post('district');
        $village = $this->post('village');
        $phone = $this->post('phone');
        $gender = $this->post('gender');
        $no_ktp = $this->post('no_ktp');
        $user_id = $this->post('user_id');
        $path_photo = $this->post('path_photo');
          
        try {
    
            /*execution form*/
    
            $this->db->trans_begin();
    
            $dataexc = array(
                'fullname' => $this->regex->_genRegex($fullname,'RGXQSL'),
                'pob' => $this->regex->_genRegex($pob,'RGXQSL'),
                'dob' => $this->regex->_genRegex($dob,'RGXQSL'),
                'address' => $this->regex->_genRegex($address,'RGXQSL'),
                'province' => $this->regex->_genRegex($province,'RGXINT'),
                'regency' => $this->regex->_genRegex($regency,'RGXINT'),
                'district' => $this->regex->_genRegex($district,'RGXINT'),
                'village' => $this->regex->_genRegex($village,'RGXINT'),
                'phone' => $this->regex->_genRegex($phone,'RGXQSL'),
                'gender' => $this->regex->_genRegex($gender,'RGXAZ'),
                'no_ktp' => $this->regex->_genRegex($no_ktp,'RGXQSL'),
                'user_id' => $this->regex->_genRegex($user_id,'RGXINT'),
                'path_photo' => $this->regex->_genRegex($path_photo,'RGXQSL'),
            );
    
            $user_profile = $this->db->get_where('user_profile',array('user_id' => $dataexc['user_id'] ) )->num_rows();
    
            if($user_profile == 0){
                
                /*save post data*/
                $newId = $this->Profile_model->save('user_profile',$dataexc);
    
                $this->Profile_model->update('user', array('fullname' => $dataexc['fullname'],'phone_no' => $dataexc['phone']), array('user_id' => $dataexc['user_id']));
            }else{

                $dataexc['updated_date'] = date('Y-m-d H:i:s');
    
                $this->Profile_model->update_profile_user($dataexc);
    
                $this->Profile_model->update('user', array('fullname' => $dataexc['fullname'],'phone_no' => $dataexc['phone']), array('user_id' => $dataexc['user_id']));
            }
    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                
                $resp = array(
                    'message' => 'Maaf Proses Gagal Dilakukan',
                );

                $this->response($resp, 301);
        
            }
            else
            {
                $this->db->trans_commit();

                $resp = array(
                    'status' => 200,
                    'message' => 'Proses Berhasil Dilakukan',
                    'data' => $dataexc
                );

                $this->response($resp, 200);
        
            }
    
    
        } catch (Exception $e) {
            
            $resp = array(
                'message' => $e->getMessage(),
            );

            $this->response($resp, 500);
    
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










