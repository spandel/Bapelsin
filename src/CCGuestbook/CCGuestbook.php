<?php
class CCGuestbook extends CObject implements IController{

	private $pageTitle="Bapelsin guestbook";
	private $gbModel;
	
  	public function __construct()
  	{
  		parent::__construct();
  		$this->gbModel=new CMGuestbook();
  	}
	public function index()
	{
		
		$form=new CFormGuestbook($this->gbModel);
		
		$form->form['action']=$this->request->createUrl('guestbook/handler');
		$this->views->setTitle($this->pageTitle);
		$this->views->addInclude(__DIR__ . '/index.tpl.php', array(
			'entries'=>$this->gbModel->getEntries(), 
			'form'=>$form,
			'formAction'=>$this->request->createUrl('guestbook/handler')
			));  
	}	
	public function handler()
	{
		if(isset($_POST['doAdd']))
		{			
			$entry=strip_tags($_POST['poem']);
			$poet=strip_tags($_POST['poet']);
			if($entry!="" && $poet!="")
				$this->gbModel->addNewEntry($entry, $poet);			
		}
		else if(isset($_POST['doClear']))
		{
			$this->gbModel->emptyEntries();
		}
		else if(isset($_POST['doCreate']))
		{
			$this->gbModel->init();
		}
		//header('Location: '.$this->request->createUrl('guestbook'));
		$this->redirectToController();
	}
	
	
	
	
	
	/*
	Functions for working with sessions... not really necessary to keep, but why not!
	*/
	private function addNewEntryToSession($entry)
	{
		$poet=strip_tags($_POST['poet']);
			$time=date('r');
			if(!isset($_SESSION['guestbook']))
				$_SESSION['guestbook'][]=array('time'=>$time, 'entry'=>$entry, 'poet'=>$poet);
			else
				array_unshift($_SESSION['guestbook'],array('time'=>$time, 'entry'=>$entry, 'poet'=>$poet));	
	}
	private function emptyEntriesFromSession()
	{
		unset($_SESSION['guestbook']);		
	}
	private function getEntriesFromSession()
	{
		$msgs="";
		if(isset($_SESSION['guestbook']))
    	{
    		foreach($_SESSION['guestbook'] as $msg)
    		{
    			$msgs.="<div style='background-color:purple; color:yellow; padding:0.3em; margin:0.3em'>
    				<p style='font-weight:bold; font-size:120%;'>".
    				$msg['poet']."</p><p style='font-style:italic;'>".
    				$msg['entry']."</p><p style='font-size:70%;'>".$msg['time']."</p></div>
    			";
    		}
    	}
		return $msgs;
	}
}
