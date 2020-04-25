<?php 
// include_once(APPPATH.'libraries/REST_Controller.php');
defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_Controller  {

	public function __construct() {

		parent::__construct();
        $this->load->library('Pdf','pdf');
		$this->load->library('mailer');
    
    }

    public function uploadImage()
    {
        header('Access-Control-Allow-Origin: *');
        # code...
        $random = rand(1,99);
        $unique_filename = $_FILES['path_photo']['name'] . $random ;

        $path = PATH_PHOTO_PROFILE_DEFAULT;
        $vfile_upload = $path . $_FILES['path_photo']['name'];
         
        if (move_uploaded_file($_FILES['path_photo']['tmp_name'], $vfile_upload)) {
            echo $_FILES['path_photo']['name'];
        } else {
        echo $target_path;
            echo "There was an error uploading the file, please try again!";
        }
    }

    public function uploadFile()
    {
        header('Access-Control-Allow-Origin: *');
        # code...
        $unique_filename = $_FILES['path_photo']['name'] ;

        $path = PATH_FILE_PROFILE_DEFAULT;
        $vfile_upload = $path . $_FILES['path_photo']['name'];
         
        if (move_uploaded_file($_FILES['path_photo']['tmp_name'], $vfile_upload)) {
            echo $_FILES['path_photo']['name'];
        } else {
        echo $target_path;
            echo "There was an error uploading the file, please try again!";
        }
    }

}
?>










