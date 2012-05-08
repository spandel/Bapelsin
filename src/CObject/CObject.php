<?php
class CObject{
	public $config;
	public $data;
	public $views;
	public $request;
	public $db;
	public $session;
	public $user;
	
	protected function __construct($ly=null)
	{
		if($ly==null)
			$ly=CLydia::instance();
		
		$this->config	= &$ly->config;
		$this->data		= &$ly->data;
		$this->request	= &$ly->request;
		$this->db		= &$ly->db;
		$this->views	= &$ly->views;
		$this->session	= &$ly->session;
		$this->user		= &$ly->user;
	}
	
	protected function redirectTo($urlOrController=null, $method=null) 
	{
		$ly = CLydia::instance();
		if(isset($ly->config['debug']['db-num-queries']) && $ly->config['debug']['db-num-queries'] && isset($ly->db)) 
		{
			$this->session->setFlash('database_numQueries', $this->db->GetNumQueries());
		}    
		if(isset($ly->config['debug']['db-queries']) && $ly->config['debug']['db-queries'] && isset($ly->db)) {
			$this->session->setFlash('database_queries', $this->db->GetQueries());
		}    
		if(isset($ly->config['debug']['timer']) && $ly->config['debug']['timer']) {
			$this->session->setFlash('timer', $ly->timer);
		}    
		$this->session->store();
		header('Location: ' . $this->request->createUrl($urlOrController."/".$method));
		//echo $this->request->createUrl($urlOrController."/".$method)."<br/>";
	}
	protected function redirectToController($method=null) 
	{
		$this->redirectTo($this->request->controller, $method);
	}
	protected function redirectToControllerMethod($controller=null, $method=null) 
	{
		$controller = is_null($controller) ? $this->request->controller : null;
		$method = is_null($method) ? $this->request->method : null;	  
		$this->redirectTo($this->request->CreateUrl($controller, $method));
	}	
}
