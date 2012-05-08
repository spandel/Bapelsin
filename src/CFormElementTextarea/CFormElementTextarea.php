<?php
class CFormElementTextarea extends CFormElement
{
	public function __construct($name, $attributes=array())
	{
		parent::__construct($name, $attributes);
		$this['type']='textarea';
		$this->useNameAsDefaultLabel();
	}
}
