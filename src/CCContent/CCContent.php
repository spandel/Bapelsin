<?php
class CCContent extends CObject implements IController{
	
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$content =new CMContent();
		$this->views->setTitle("Content Controller");
		$this->views->addInclude(__DIR__. '/index.tpl.php',array('contents'=>$content->listAll()));
	}
	public function init()
	{
		$content =new CMContent();
		$content->init();
		$this->redirectToController();
	}
	public function handler()
	{		
		if(isset($_POST['doSave']))
		{
			$this->edit();
		}
		if(isset($_POST['doRemove']))
		{			
			$this->remove();
		}		
	}
	public function create()
	{
		$this->edit();
	}
	public function remove($id=null)
	{
		$content=new CMContent($id);
		$form = new CFormContent($content);
		$content['id']    = $form['id']['value'];
		//$this->instance()->AAAAAAAAAAAAAAAAAAAAAAAAAAAHHHHHHHGHGHGHGHGHGHG=$content['id'];
		$content->remove($_POST['id']);
		$this->redirectToController('index');
	}
	public function edit($id=null)
	{
		$content=new CMContent($id);
		$form = new CFormContent($content);
		$form->form['action']=$this->request->createUrl('content/handler');
		$status=$form->check();
		
		if($status===false)
		{
			$this->addMessage('notice', 'The form is incomplete.');
			$this->redirectToController('edit');
		}
		else if(isset($_POST['doSave']))
		{
			$this->doSave($form, $content);
			$this->redirectToController("edit/{$content['id']}");
		}
		
		$title = isset($id) ? 'Edit' : 'Create';
		$this->views->setTitle("$title Controller: $id");
		$this->views->addInclude(__DIR__. '/edit.tpl.php',array('content'=>$content->listAll(), 'form'=>$form));
	}
	public function doSave($form, $content) 
    {    		
    	$content['id']    = $form['id']['value'];
    	$content['title'] = $form['title']['value'];
    	$content['key']   = $form['key']['value'];
    	$content['data']  = $form['data']['value'];
    	$content['type']  = $form['type']['value'];
    	$content['filter']= $form['filter']['value'];
    	return $content->save();
    }
	
}
