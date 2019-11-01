<?php
defined('BASEPATH') OR exit('No direct script access allowed');
@header('Content-Type: application/json');
if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE):			
	echo '{"code":500, "status":"Exception", "errors":'. json_encode($exception->getTrace()).'}';
else:
	echo '{"code":500, "status":"Exception"}';
endif;
