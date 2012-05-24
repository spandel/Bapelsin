<?php
class CFormCheckbox extends CFormElement{
	public function __construct($name, $attributes=array())
	{
		parent::__construct($name, $attributes);
		$this['type']='checkbox';
		$this->useNameAsDefaultLabel();
	}
}
