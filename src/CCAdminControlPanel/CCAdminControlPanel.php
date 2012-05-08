<?php
class CCAdminControlPanel extends CObject implements IController
{
	private $pageTitle="Admin Control Panel";
	
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->views->setTitle($this->pageTitle);
		$this->views->addInclude(__DIR__ . '/index.tpl.php');
	}	
}
