<?php
class CCModules extends CObject implements IController
{
	
	public function __construct()
	{
		parent::__construct();
	}
		
	public function index()
	{
		$modules= new CMModules();
		$controllers =$modules->availableControllers();
		$allModules=$modules->readAndAnalyze();
		
		$this->views->setTitle('Manage Modules');
		$this->views->addInclude(__DIR__.'/index.tpl.php',array('controllers'=>$controllers),'primary');
		$this->views->addInclude(__DIR__.'/sidebar.tpl.php',array('modules'=>$allModules),'sidebar');
	}
	public function install()
	{
		$modules= new CMModules();
		$results=$modules->install();
		$allModules=$modules->readAndAnalyze();
		$this->views->setTitle('Install Modules');
		$this->views->addInclude(__DIR__.'/install.tpl.php',array('modules'=>$results),'primary');
		$this->views->addInclude(__DIR__.'/sidebar.tpl.php',array('modules'=>$allModules),'sidebar');
	}
	public function view($module) 
	{
		if(!preg_match('/^C[a-zA-Z]+$/', $module)) 
		{
			throw new Exception('Invalid characters in module name.');
		}
		$modules = new CMModules();
		$controllers = $modules->availableControllers();
		$allModules = $modules->readAndAnalyze();
		$aModule = $modules->readAndAnalyzeModule($module);
		$this->views->setTitle('Manage Modules');
        $this->views->addInclude(__DIR__ . '/view.tpl.php', array('module'=>$aModule), 'primary');
        $this->views->addInclude(__DIR__ . '/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');
  }

}
