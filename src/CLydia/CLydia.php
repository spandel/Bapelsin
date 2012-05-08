<?php
class CLydia implements ISingleton
{	
	private static $instance = null;
	
	protected function __construct()
	{
		$this->timer['first']=round(microtime(true),5);
		
		$ly=&$this;
		require(LYDIA_SITE_PATH.'/config.php');
		session_name($this->config['session_name']);
		session_start();
		$this->session=new CSession($this->config['session_key']);
		$this->session->populateFromSession();
		if(isset($this->config['database'][0]['dsn'])) {
			$this->db = new CDatabase($this->config['database'][0]['dsn']);
		}
		$this->views=new CViewContainer();
		$this->user=new CMUser($this);
	}
	public function frontControllerRoute()
	{
		$this->request=new CRequest($this->config['url_type']);
		$this->request->init($this->config['base_url']);
		
		$controller=$this->request->controller;
		$method=$this->request->method;
		$method=str_replace(array('_','-'),'',$method);
		$arguments=$this->request->arguments;
		
		$controllerExists=isset($this->config['controllers'][$controller]);
		$controllerEnabled=false;
		$className=false;
		$classExists=false;

		if($controllerExists) 
		{
			$controllerEnabled    = ($this->config['controllers'][$controller]['enabled'] == true);
			$className               = $this->config['controllers'][$controller]['class'];
			$classExists           = class_exists($className);
		}
		
		// Check if controller has a callable method in the controller class, if then call it
		if($controllerExists && $controllerEnabled && $classExists) 
		{
			$rc = new ReflectionClass($className);
			if($rc->implementsInterface('IController')) 
			{
				if($rc->hasMethod($method)) 
				{
					$controllerObj = $rc->newInstance();
					$methodObj = $rc->getMethod($method);
					$methodObj->invokeArgs($controllerObj, $arguments);
				} else 
				{
					die("404. " . get_class() . ' error: Controller does not contain method.');
				}
			} else 
			{
				die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
			}
		} 
		else 
		{ 
			die('404. Page is not found.');
		}
		
		
		$this->data['debug']="REQUEST_URI - {$_SERVER['REQUEST_URI']}\n";
		$this->data['debug'].="SCRIPT_NAME - {$_SERVER['SCRIPT_NAME']}\n";
	}
	public function themeEngineRender()
	{
		$this->session->store();
		$ly=&$this;
		$themeName= $this->config['theme']['name'];
		$themePath= LYDIA_INSTALL_PATH . "/theme/{$themeName}";
    	$themeUrl = $ly->request->base_url."theme/{$themeName}";
    	$this->data['stylesheet'] = "{$themeUrl}/".$this->config['theme']['stylesheet'];
    	
    	include(LYDIA_INSTALL_PATH."/theme/functions.php");
    	$functionsPath="{$themePath}/functions.php";
    	if(is_file($functionsPath))
    	{
    		include($functionsPath);
    	}
    	
    	extract($this->data);
    	extract($this->views->getData());
    	
    	$template="default.tpl.php";
    	if(isset($this->config['theme']['template_file']))
    		$template=$this->config['theme']['template_file'];
    	
    	
    	include("{$themePath}/".$template);
    	
		/*echo "<h1>I'm CLydia::themeEngineRender</h1><p>You are most welcome. Nothing to render at the moment</p>";
		echo "<h2>The content of the config array:</h2><pre>", htmlentities(print_r($this->config, true)) . "</pre>";
		echo "<h2>The content of the data array:</h2><pre>", htmlentities(print_r($this->data, true)) . "</pre>";
		echo "<h2>The content of the request array:</h2><pre>", htmlentities(print_r($this->request, true)) . "</pre>";*/
	}
	public static function instance()
	{	
		if(self::$instance==null)
		{
			self::$instance=new CLydia(); 
		}
		return self::$instance;		
	}
}
