<?php
class CFormElementPassword extends CFormElement{
	
	public function __construct($value, $attributes=array())
	{
		parent::__construct($value, $attributes);
		$this['type']='password';
		$this->useNameAsDefaultLabel();
	}
}

