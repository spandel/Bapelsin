<?php
class CCTheme extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->views->setTitle('Theme display for some regionzz');
		
		

		$this->views->addInclude(__DIR__.'/index.tpl.php',array('theme_name'=>$this->config['theme']['name']));
	}
	public function someRegions()
	{
		$this->views->setTitle('Theme display for some regionzz');
		$this->views->addString('This is the PRIMARY REGION BITCH!',array(),'default');
		
		if(func_get_args())
		{
			foreach(func_get_args() as $val)
			{
				$this->views->addString("This is region: $val",array(),$val);
				$this->views->addStyle('#'.$val.'{background-color:lightblue}');
			}
		}
		
	}
	public function allRegions()
	{
		$this->views->setTitle('theme display content all regions');
		foreach($this->config['theme']['regions'] as $val)
		{
			$this->views->addString("This is region: $val",array(),$val);
			$this->views->addStyle('#'.$val.'{background-color:lightblue}');
		}
	}
	public function test()
	{
		$this->views->setTitle('testing theme style');
		$this->views->addInclude(__DIR__.'/themetest.tpl.php',array('theme_name'=>$this->config['theme']['name']));
		
	}
}
