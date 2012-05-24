<?php

class CFormElement implements ArrayAccess
{
	public $attributes;
  
	public function __construct($name, $attributes=array()) 
	{
		$this->attributes = $attributes;    
		$this['name'] = $name;
	}
	public function offsetSet($offset, $value) 
	{ 
		if (is_null($offset)) 
		{ 
			$this->attributes[] = $value; 
		} 
		else 
		{ 
			$this->attributes[$offset] = $value; 
		}
	}
	public function offsetExists($offset) 
	{ 
		return isset($this->attributes[$offset]); 
	}
	public function offsetUnset($offset) 
	{ 
		unset($this->attributes[$offset]); 
	}
	public function offsetGet($offset) 
	{ 
		return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null; 
	}
	public function getHTML() 
	{
		
		$id = isset($this['id']) ? $this['id'] : 'form-element-' . $this['name'];
		$class = isset($this['class']) ? " {$this['class']}" : null;
		$validates = (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
		$class = (isset($class) || isset($validates)) ? " class='{$class}{$validates}'" : null;
		if(isset($this['name']))
			$name = " name='{$this['name']}'";
		$label = isset($this['label']) ? ($this['label'] . (isset($this['required']) && $this['required'] ? "<span class='form-element-required'>*</span>" : null)) : null;
		$autofocus = isset($this['autofocus']) && $this['autofocus'] ? " autofocus='autofocus'" : null;    
		$readonly = isset($this['readonly']) && $this['readonly'] ? " readonly='readonly'" : null;    
		$type    = isset($this['type']) ? " type='{$this['type']}'" : null;
		$value    = isset($this['value']) ? " value='{$this['value']}'" : null;
		$validates = (isset($this['validation-pass']) && $this['validation-pass'] === false) ? ' validation-failed' : null;
		$url=isset($this['url']) ? "{$this['url']}'" : null;
		$selecteds=isset($this['value']) ? $this['value'] : null;
		
		$messages="";
		if(isset($this['validation_messages']))
		{
			foreach($this['validation_messages'] as $val)
			{
				$messages.="<li>{$val}</li>\n";
			}
			$messages="<ul class='validation-message'>\n{$messages}\n</ul>\n";
		}
		if($type && $this['type']=='checkbox')
		{
			$checked="";
			if(isset($this['checked']))
				$checked="checked";
			
			return "<input id='$id'{$type}{$class}{$name}{$value}{$checked}/>\n";
		}
		if($type && $this['type'] == 'select') 
		{
			
			$options="";
			foreach($this['options'] as $key => $val)
			{
				$selected="";
				if($selecteds!=null && $selecteds==$key)
					$selected="selected='selected'";
				//if($val=='yes' )
				//	$selected="selected='selected'";
				
				$options.="<option $selected>$key</option>\n";
			}
			
			return "<p><label for='$id'>$label</label><br><select id='$id'{$type}{$class}{$name}{$autofocus}{$readonly}>{$options}</select></p>\n";   
		} 
		if($type && $this['type'] == 'textarea') 
		{
			return "<label for='$id'>$label</label><br><textarea id='$id'{$type}{$class}{$name}{$autofocus}{$readonly}>{$this['value']}</textarea>\n";   
		} 
		else if($type && $this['type'] == 'hidden') 
		{
			return "<input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />\n";   
		} 
		else if($type && $this['type'] == 'submit') 
		{
			return "<input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />\n";   
		} 
		else if($type && $this['type']=='link')
		{
			return "<a href='{$url}'>{$this['name']}</a>";
		}
		else 
		{			
			return "<p><label for='$id'>$label</label><br><input id='$id'{$type}{$class}{$name}{$value}{$autofocus}{$readonly} />{$messages}</p>\n";           
		}
	}  
	public function useNameAsDefaultLabel() 
	{
		if(!isset($this['label'])) 
		{
			$this['label'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name']))).':';
		}
	}  
	public function useNameAsDefaultValue() 
	{
		if(!isset($this['value'])) 
		{
			$this['value'] = ucfirst(strtolower(str_replace(array('-','_'), ' ', $this['name'])));
		}
	}
	public function validate($rules)
	{
		$tests= array(
			'fail'=>array(
				'message'=>'will always fail.',
				'test'=>'return false;',
				),
			'pass'=>array(
				'message'=>'will always pass.',
				'test'=>'return true;',
				),
			'not_empty' =>array(
				'message'=>'Can not be empty',
				'test'	=>'return $value != "";',				
				),
		);
		$pass = true;
		$messages = array();
		$value = $this['value'];
		foreach($rules as $key => $val) 
		{
			$rule = is_numeric($key) ? $val : $key;
			if(!isset($tests[$rule])) throw new Exception('Validation of form element failed, no such validation rule exists.');
			if(eval($tests[$rule]['test']) === false) {
				$messages[] = $tests[$rule]['message'];
				$pass = false;
			}
		}
		if(!empty($messages)) $this['validation_messages'] = $messages;
		return $pass;
	}
}



