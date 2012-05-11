<?php
class CBapelsin implements ISingleton
{	
	private static $instance = null;
	
	protected function __construct()
	{
		$this->timer['first']=round(microtime(true),5);
		
		$bap=&$this;
		require(BAPELSIN_SITE_PATH.'/config.php');
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
		$this->request->init($this->config['base_url'], $this->config['routing']);
		
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
		
		if(!isset($this->config['theme']))
		{
			return;
		}
		$themeName= $this->config['theme']['name'];
		
		$themePath= BAPELSIN_INSTALL_PATH . "/{$this->config['theme']['path']}";
    	$themeUrl = $this->request->base_url.$this->config['theme']['path'];
    	
    	$parentPath = null;
    	$parentUrl = null;
    	
    	if(isset($this->config['theme']['parent'])) 
    	{
    		$parentPath = BAPELSIN_INSTALL_PATH . '/' . $this->config['theme']['parent'];
    		$parentUrl   = $this->request->base_url . $this->config['theme']['parent'];
    	}
    	
    	//$this->data['stylesheet'] = "{$themeUrl}/".$this->config['theme']['stylesheet'];
    	
    	$this->data['stylesheet'] =$this->config['theme']['stylesheet'];
    	
    	$this->themeUrl = $themeUrl;
    	$this->themeParentUrl = $parentUrl;
    	
    	if(is_array($this->config['theme']['menu_to_region'])) 
    	{
    		foreach($this->config['theme']['menu_to_region'] as $key => $val) 
    		{
    			$this->views->addString($this->drawMenu($key), array(), $val);
        	}
        }
    	
    	$bap=&$this;
    	
    	include(BAPELSIN_INSTALL_PATH."/theme/functions.php");
    	
    	if($parentPath) 
    	{
    		if(is_file("{$parentPath}/functions.php")) 
    		{
    			include "{$parentPath}/functions.php";
    		}
    	}
    	
    	$functionsPath="{$themePath}/functions.php";
    	if(is_file($functionsPath))
    	{
    		include($functionsPath);
    	}
    	
    	
    	//extract($this->data);
    	$this->views->setVariable('stylesheet',$this->data['stylesheet']);
    	extract($this->views->getData());
    	if(isset($this->config['theme']['data']))
    		extract($this->config['theme']['data']);
    	
    	$template="default.tpl.php";
    	if(isset($this->config['theme']['template_file']))
    		$template=$this->config['theme']['template_file'];
    	
    	if(is_file("{$themePath}/{$template}")) 
    	{
    		include("{$themePath}/{$template}");
   		} else if(is_file("{$parentPath}/{$template}")) 
   		{
   			include("{$parentPath}/{$template}");
   		} else 
   		{
   			throw new Exception('No such template file.');
   		}
    	//include("{$themePath}/".$template);
    	
		/*echo "<h1>I'm CLydia::themeEngineRender</h1><p>You are most welcome. Nothing to render at the moment</p>";
		echo "<h2>The content of the config array:</h2><pre>", htmlentities(print_r($this->config, true)) . "</pre>";
		echo "<h2>The content of the data array:</h2><pre>", htmlentities(print_r($this->data, true)) . "</pre>";
		echo "<h2>The content of the request array:</h2><pre>", htmlentities(print_r($this->request, true)) . "</pre>";*/
	}
	public function drawMenu($menu)
	{
		$items=null;
		$items="<ul class='menu $menu'>";
		foreach($this->config['menus'][$menu] as $val)
		{
			 $selected = null;
			 $url=$this->request->createUrl($val['url']);
			 if($url == $this->request->getCurrentUrl()) 
			 {
			 	 $selected = " class='selected'";
			 }
			 $items.="<li><a{$selected} href='{$url}'>{$val['label']}</a></li>";
		}
		return $items."</ul>";
	}
	public static function instance()
	{	
		if(self::$instance==null)
		{
			self::$instance=new CBapelsin(); 
		}
		return self::$instance;		
	}
}
