<?php
class CMModules extends CObject{
	
	public function __construct()
	{
		parent::__construct();
	}
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
					$modules[$module]['isBapelsinCore']   = in_array($rc->name, array('CBapelsin', 'CDatabase', 'CRequest', 'CViewContainer', 'CSession', 'CObject'));
					$modules[$module]['isBapelsinCMF']    = in_array($rc->name, array('CForm', 'CCPage', 'CCBlog', 'CMUser', 'CCUser', 'CMContent', 'CCContent', 'CFormUserLogin', 'CFormUserProfile', 'CFormUserCreate', 'CFormContent','CFormElement','CFormElementHidden','CFormElementLink','CFormElementPassword','CFormElementSubmit','CFormElementText','CFormElementTextarea', 'CHTMLPurifier'));
				}
			}
		}
		$dir->close();
		ksort($modules, SORT_LOCALE_STRING);
		return $modules;
	}
}
