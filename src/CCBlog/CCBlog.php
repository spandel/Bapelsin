<?php
class CCBlog extends CObject implements IController{
	
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$content =new CMContent();
		$this->views->setTitle("Blog Controller");
		$this->views->addInclude(__DIR__. '/index.tpl.php',array('contents'=>$content->listAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC'))),'primary');
		
	}
}	
