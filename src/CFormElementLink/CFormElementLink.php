<?php
class CFormElementLink extends CFormElement{
	
	public function __construct($value, $name=null, $attributes=array())
	{
		parent::__construct($value, $attributes);
		$this['type']='link';
		
		$this['url']=CBapelsin::instance()->request->createUrl($name);
		$this['name']=$value;
	}
}

