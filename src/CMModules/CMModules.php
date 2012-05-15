<?php
/**
	* A model for managing Bapelsin modules.
	*/
class CMModules extends CObject{
	
	private $bapelsinCoreModules = array('CLydia', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject');
	private $bapelsinCMFModules = array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin', 'CFormUserProfile', 'CFormUserCreate', 'CFormContent', 'CHTMLPurifier');

	/**
	* Constructor
	*/
	public function __construct()
	{
		parent::__construct();
	}
	/**
	* Lists all available controller
	* @returns array list of controllers (key) and an array of methods
	*/
	public function availableControllers()
	{
		$items=array();
   		$mtds=array();
   		foreach($this->config['controllers'] as $key =>$val)
   		{
   			if($val['enabled'])
   			{
   				$rc=new ReflectionClass($val['class']);
   				$items[]=$key;
   				$methods= $rc->getMethods(ReflectionMethod::IS_PUBLIC);
   				
   				
   				foreach($methods as $method)
   				{
   					if($method->name !="__construct" && $method->name !='__destruct' && $method->name != 'index'  && $method->name != 'handler')
   					{
   						$mtds[]=mb_strtolower($method->name);
   					}
   				}
   				sort($mtds);
   				$items[]=$mtds;
   				$mtds=array();
   			}
   		}
   		
   		return $items;
	}
	/**
* Install all modules.
*
* @returns array with an entry for each module and the result from installing it.
*/
	public function install()
	{
		$allModules=$this->readAndAnalyze();
		$installed=array();
		foreach($allModules as $mod)
		{
			if($mod['isManageable'])
			{
				$className=$mod['name'];
				$rc=new ReflectionClass($className);
				$obj=$rc->newInstance();
				$method=$rc->getMethod('manage');
				$installed[$className]['name']=$className;
				$installed[$className]['result']=$method->invoke($obj,'install');
			}
		}
		sort($installed);
		return $installed;
		
	}
	/**
* Read and analyze all modules.
*
* @returns array with a entry for each module with the module name as the key. 
* Returns boolean false if $src can not be opened.
*/
	public function readAndAnalyze() 
	{
		$src = BAPELSIN_INSTALL_PATH.'/src';
		if(!$dir = dir($src)) throw new Exception('Could not open the directory.');
		$modules = array();
		while (($module = $dir->read()) !== false) 
		{
			if(is_dir("$src/$module")) 
			{
				if(class_exists($module)) 
				{
					$rc = new ReflectionClass($module);
					$modules[$module]['name']          = $rc->name;
					$modules[$module]['interface']     = $rc->getInterfaceNames();
					$modules[$module]['isController']  = $rc->implementsInterface('IController');
					$modules[$module]['isManageable']  = $rc->implementsInterface('IModule');
					$modules[$module]['isModel']       = preg_match('/^CM[A-Z]/', $rc->name);
					$modules[$module]['hasSQL']        = $rc->implementsInterface('IHasSQL');
					$modules[$module]['isBapelsinCore']   = in_array($rc->name, $this->bapelsinCoreModules);
					$modules[$module]['isBapelsinCMF']    = in_array($rc->name, $this->bapelsinCMFModules);
				}
			}
		}
		$dir->close();
		ksort($modules, SORT_LOCALE_STRING);
		return $modules;
	}
	/**
* Get info and details about a module.
*
* @param $module string with the module name.
* @returns array with information on the module.
*/
	private function getDetailsOfModule($module) 
	{
		$details = array();
		if(class_exists($module)) 
		{
			$rc = new ReflectionClass($module);
			$details['name']          = $rc->name;
			$details['filename']      = $rc->getFileName();
			$details['doccomment']    = $rc->getDocComment();
			$details['interface']     = $rc->getInterfaceNames();
			$details['isController']  = $rc->implementsInterface('IController');
			$details['isModel']       = preg_match('/^CM[A-Z]/', $rc->name);
			$details['hasSQL']        = $rc->implementsInterface('IHasSQL');
			$details['isManageable']  = $rc->implementsInterface('IModule');
			$details['isBapelsinCore']   = in_array($rc->name, $this->bapelsinCoreModules);
			$details['isBapelsinCMF']    = in_array($rc->name, $this->bapelsinCMFModules);
			$details['publicMethods']     = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
			$details['protectedMethods']  = $rc->getMethods(ReflectionMethod::IS_PROTECTED);
			$details['privateMethods']    = $rc->getMethods(ReflectionMethod::IS_PRIVATE);
			$details['staticMethods']     = $rc->getMethods(ReflectionMethod::IS_STATIC);
		}
		return $details;
	}
	/**
* Get info and details about the methods of a module.
*
* @param $module string with the module name.
* @returns array with information on the methods.
*/
	private function getDetailsOfModuleMethods($module) 
	{
		$methods = array();
		if(class_exists($module)) 
		{
			$rc = new ReflectionClass($module);
			$classMethods = $rc->getMethods();
			foreach($classMethods as $val) 
			{
				$methodName = $val->name;
				$rm = $rc->GetMethod($methodName);
				$methods[$methodName]['name']          = $rm->getName();
				$methods[$methodName]['doccomment']    = $rm->getDocComment();
				$methods[$methodName]['startline']     = $rm->getStartLine();
				$methods[$methodName]['endline']       = $rm->getEndLine();
				$methods[$methodName]['isPublic']      = $rm->isPublic();
				$methods[$methodName]['isProtected']   = $rm->isProtected();
				$methods[$methodName]['isPrivate']     = $rm->isPrivate();
				$methods[$methodName]['isStatic']      = $rm->isStatic();
			}
		}
		ksort($methods, SORT_LOCALE_STRING);
		return $methods;
	}
	/**
* Get info and details about a module.
*
* @param $module string with the module name.
* @returns array with information on the module.
*/
	public function readAndAnalyzeModule($module) 
	{
		$details = $this->getDetailsOfModule($module);
		$details['methods'] = $this->getDetailsOfModuleMethods($module);
		return $details;
	}
}
