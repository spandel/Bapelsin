<?php
class CformElementSelect extends CFormElement{
	public function __construct($name, $attributes=array())
	{
		parent::__construct($name, $attributes);
		$this['type']='select';
		$this->useNameAsDefaultLabel();
	}
}
