<?php
/**
* This is the PHP REST API.
* This PHP class uses the PHP Webservice get the data from http requests.
* @author Rajesh Gupta (rg0098040@TechMahindra.com)
* @copyright Copyright CanvasM (rg0098040@TechMahindra.com)
* $version 1.01
*/
class RestRequest
{
	public $request_vars;
	public $data;
	public $http_accept;
	public $method;

	public function __construct()
	{
		$this->request_vars		= array();
		$this->data				= '';
		//$this->http_accept		= (strpos($_SERVER['HTTP_ACCEPT'], 'json')) ? 'json' : 'xml';
		$this->method			= 'get';
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}

	public function setRequestVars($request_vars)
	{
		$this->request_vars = $request_vars;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getHttpAccept()
	{
		return $this->http_accept;
	}

	public function getRequestVars()
	{
		return $this->request_vars;
	}
}