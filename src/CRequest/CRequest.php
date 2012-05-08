<?php
class CRequest
{
	public $cleanUrl;
	public $querystringUrl;
	
	public function __construct($urlType=0) 
	{
		$this->cleanUrl= $urlType= 1 ? true : false;
		$this->querystringUrl = $urlType= 2 ? true : false;
	}
	public function init($baseUrl = null)
	{
		$requestUri = $_SERVER['REQUEST_URI'];
		$scriptName = $_SERVER['SCRIPT_NAME'];
		
		$currentUrl=$this->getCurrentUrl();
		$parts=parse_url($currentUrl);
		$baseUrl       = !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');
		
		$this->base_url= rtrim($baseUrl, '/') . '/';
		$this->current_url= $currentUrl;
		
		$query = substr($_SERVER['REQUEST_URI'], strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/')));
		$splits = explode('/', trim($query, '/'));
		
		if(strpos($splits[0],".php")===false)
		{
			
		}else if(strpos($splits[0],"=")===false)
		{
			array_shift($splits);
		}
		else
		{
			$qarr=explode('=', $splits[0]);
			$splits[0]=$qarr[1];
		}
	
		
		$controller=!empty($splits[0]) ? $splits[0] : 'index';
		$method=!empty($splits[1]) ? $splits[1] : 'index';
		$arguments = $splits;
		unset($arguments[0], $arguments[1]);
    
		$this->request_uri= $_SERVER['REQUEST_URI'];
		$this->script_name= $_SERVER['SCRIPT_NAME'];
		$this->query= $query;
		$this->splits= $splits;
		$this->controller= $controller;
		$this->method= $method;
		$this->arguments= $arguments;
	}
	public function createUrl($url=null)
	{
		$prepend = $this->base_url;
		if($this->cleanUrl) 
		{
			;
		} elseif ($this->querystringUrl) {
			$prepend .= 'index.php?q=';
		} else {
			$prepend .= 'index.php/';
		}	
		return $prepend . rtrim($url, '/');
	}
	public function getCurrentUrl()
	{
		$url = "http";
		$url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
		$url .= "://";
		$serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
		(($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
		$url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
		return $url;
	}
	
}
