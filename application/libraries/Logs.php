<?php

/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final Class Logs {
	
	function log($filename,$msg,$data) {
		
		$CI =&get_instance();

		$CI->load->helper('file');
		
		date_default_timezone_set('Asia/Jakarta');
		
		$log_file = '././log/'.$filename.'.log';

		$data = json_encode($data);
		$txt = '"'.date('Y-m-d H:i:s') . '", "'.$msg.'"';
		$txt .= ", ". $data ."\n\r";

		if ( ! @write_file($log_file, $txt, 'a+'))
		{
		    @error_log('Unable to write the Login log file : ' . $this->log_file, 0);
		}

	}


}
    
?>