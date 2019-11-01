<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function index()
	{		
		header('Content-Type: application/json');
		echo '{"code":200, "status":"success"}';
	}
}
