<?php
/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final Class Api {

    // ================================= DASHBOARD =================================== //
     public function getApiData($link) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $link);

         // Execute post
        $result = curl_exec($ch);
        //print_r($result);die;
        if ($result === FALSE) {
          die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // do anything you want with your response
        return json_decode($result);
    }

    function postData($link, $post_data=''){

        $uri = $link;
        $c = curl_init();

        curl_setopt($c, CURLOPT_URL, $uri);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $post_data);

        $result = curl_exec($c);
        curl_close($c); 
        return json_decode($result);

    }

    public function adsmedia_send_sms($data)
    {
        if($data['phone']!=''){
            # code...
            $phone = (substr($data['phone'], 0,1) == '0')?"62".substr($data['phone'],1)."":"".$data['phone']."";
            ob_start();
            // setting 
            $apikey      = 'df7abde97b3c6e944658806371f095d9'; // api key 
            $urlserver   = 'http://45.32.107.195/sms/api_sms_otp_send_json.php'; // url server sms 
            $callbackurl = ''; // url callback get status sms 
            $senderid    = '0'; // Option senderid 0=Sms Long Number / 1=Sms Masking/Custome Senderid

            // create header json  
            $senddata = array(
                'apikey' => $apikey,  
                'callbackurl' => $callbackurl, 
                'senderid' => $senderid, 
                'datapacket'=>array()
            );

            // create detail data json 
            // data 1
            $number=$phone;
            $message=$data['message'];
            array_push($senddata['datapacket'],array(
                'number' => trim($number),
                'message' => $message
            ));
            // sending  
            $data=json_encode($senddata);
            $curlHandle = curl_init($urlserver);
            curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data))
            );
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
            curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 30);
            $respon = curl_exec($curlHandle);

            $curl_errno = curl_errno($curlHandle);
            $curl_error = curl_error($curlHandle);	
            $http_code  = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            curl_close($curlHandle);
            if ($curl_errno > 0) {
                $senddatax = array(
                'sending_respon'=>array(
                    'globalstatus' => 90, 
                    'globalstatustext' => $curl_errno."|".$http_code)
                );
                $respon=json_encode($senddatax);
                log_message('info', 'sending sms'.$respon);
            } else {
                if ($http_code<>"200") {
                    $senddatax = array(
                    'sending_respon'=>array(
                        'globalstatus' => 90, 
                        'globalstatustext' => $curl_errno."|".$http_code)
                    );
                    $respon= json_encode($senddatax);	
                    log_message('info', 'sending sms'.$respon);
                }
            }		
            log_message('info', 'sending sms'.json_encode($respon));
            header('Content-Type: application/json');
            return true;
        } else{
            return false;
        }
    }

    

}

?>