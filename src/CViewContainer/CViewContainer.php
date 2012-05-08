<?php
class CViewContainer
{
	private $data	= array();
	private $views	= array();
	
	public function __construct()
	{
		;
	}
	public function getData()
	{
		return $this->data;
	}
	public function setVariable($key, $value)
	{
		$this->data[$key]=$value;
	}
	public function setTitle($title)
	{
		$this->setVariable('title',$title);
	}
	public function addInclude($file, $variables=array(), $region='default') 
	{
		$this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
		return $this;
	}
	public function addString($string, $variables=array(), $region='default') 
	{
		$this->views[$region][] = array('type' => 'string', 'string' => $string, 'variables' => $variables);
		return $this;
	}
	public function addStyle($style)
	{
		if(isset($this->data['inline_style']))
		{
			$this->data['inline_style'].=$style;
		}
		else{
			$this->data['inline_style']=$style;
		}
		return $this;
	}
    public function render($region='default') 
    {
    	if(!isset($this->views[$region]))
    	{
    		return;
    	}
    	foreach($this->views[$region] as $view) 
    	{
    		switch($view['type']) 
    		{
    		case 'include':
    			extract($view['variables']);
    			include($view['file']);
    			break;
    		case 'string':
    			extract($view['variables']);
    			echo $view['string'];
    			break;
    		}
    	}
    }
    public function regionHasView($region)
    {
    	if(is_array($region))
    	{
    		foreach($region as $val)
    		{
    			if(isset($this->views[$val]))
    			{
    				return true;
    			}
    		}
    		return false;
    	}else
    		return(isset($this->views[$region]));
    }
    
	
}
