<?php
class CFormElementSubmit extends CFormElement{
	
	public function __construct($value, $name=null, $attributes=array())
	{
		if($name==null)
			$name=$value;
		
		parent::__construct($value, $attributes);
		$this['type']='submit';
		$this->useNameAsDefaultValue();
		$this['name']=$name;
	}
}

