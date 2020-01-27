<?php 
# File for Attachment 
$f_name="../../letters/".$letter;    // use relative path OR ELSE big headaches. $letter is my file for attaching. 
$handle=fopen($f_name, 'rb'); 
$f_contents=fread($handle, filesize($f_name)); 
$f_contents=chunk_split(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode(); 
$f_type=filetype($f_name); 
fclose($handle); 
# To Email Address 
$emailaddress="user@example.com"; 
# Message Subject 
$emailsubject="Heres An Email with a PDF".date("Y/m/d H:i:s"); 
# Message Body 
ob_start(); 
  require("emailbody.php");     // i made a simple & pretty page for showing in the email 
$body=ob_get_contents(); ob_end_clean(); 

# Common Headers 
$headers .= 'From: Jonny <jon@example.com>'.$eol; 
$headers .= 'Reply-To: Jonny <jon@example.com>'.$eol; 
$headers .= 'Return-Path: Jonny <jon@example.com>'.$eol;     // these two to set reply address 
$headers .= "Message-ID:<".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$eol; 
$headers .= "X-Mailer: PHP v".phpversion().$eol;           // These two to help avoid spam-filters 
# Boundry for marking the split & Multitype Headers 
$mime_boundary=md5(time()); 
$headers .= 'MIME-Version: 1.0'.$eol; 
$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\"".$eol; 
$msg = ""; 

# Attachment 
$msg .= "--".$mime_boundary.$eol; 
$msg .= "Content-Type: application/pdf; name=\"".$letter."\"".$eol;   // sometimes i have to send MS Word, use 'msword' instead of 'pdf' 
$msg .= "Content-Transfer-Encoding: base64".$eol; 
$msg .= "Content-Disposition: attachment; filename=\"".$letter."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !! 
$msg .= $f_contents.$eol.$eol; 
# Setup for text OR html 
$msg .= "Content-Type: multipart/alternative".$eol; 

# Text Version 
$msg .= "--".$mime_boundary.$eol; 
$msg .= "Content-Type: text/plain; charset=iso-8859-1".$eol; 
$msg .= "Content-Transfer-Encoding: 8bit".$eol; 
$msg .= "This is a multi-part message in MIME format.".$eol; 
$msg .= "If you are reading this, please update your email-reading-software.".$eol; 
$msg .= "+ + Text Only Email from Genius Jon + +".$eol.$eol; 

# HTML Version 
$msg .= "--".$mime_boundary.$eol; 
$msg .= "Content-Type: text/html; charset=iso-8859-1".$eol; 
$msg .= "Content-Transfer-Encoding: 8bit".$eol; 
$msg .= $body.$eol.$eol; 

# Finished 
$msg .= "--".$mime_boundary."--".$eol.$eol;   // finish with two eol's for better security. see Injection. 

# SEND THE EMAIL 
ini_set(sendmail_from,'from@example.com');  // the INI lines are to force the From Address to be used ! 
  mail($emailaddress, $emailsubject, $msg, $headers); 
ini_restore(sendmail_from); 
?>