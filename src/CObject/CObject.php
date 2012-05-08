<?php
class CObject{
	public $config;
	public $data;
	public $views;
	public $request;
	public $db;
	public $session;
	public $user;
	
	protected function __construct($bap=null)
	{
		if($bap==null)
			$bap=CBapelsin::instance();
		
		$this->config	= &$bap->config;
		$this->data		= &$bap->data;
		$this->request	= &$bap->request;
		$this->db		= &$bap->db;
		$this->views	= &$bap->views;
		$this->session	= &$bap->session;
		$this->user		= &$bap->user;
	}
	
	protected function redirectTo($urlOrController=null, $method=null) 
	{
		$bap = CBapelsin::instance();
		if(isset($bap->config['debug']['db-num-queries']) && $bap->config['debug']['db-num-queries'] && isset($bap->db)) 
		{
			$this->session->setFlash('database_numQueries', $this->db->GetNumQueries());
		}    
		if(isset($bap->config['debug']['db-queries']) && $bap->config['debug']['db-queries'] && isset($bap->db)) {
			$this->session->setFlash('database_queries', $this->db->GetQueries());
		}    
		if(isset($bap->config['debug']['timer']) && $bap->config['debug']['timer']) {
			$this->session->setFlash('timer', $bap->timer);
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
