<?php
class CForm implements ArrayAccess{
	
	public $form;
	public $elements;
	
	public function __construct($form=array(),$elements=array())
	{
		$this->form=$form;
		$this->elements=$elements;
	}	
	
	//Arrayaccess shit
	public function offsetSet($offset, $value) 
	{ 
		if (is_null($offset)) 
		{ 
			$this->elements[] = $value; 
		} else 
		{
			$this->elements[$offset] = $value;
		}
	}
	public function offsetExists($offset) { return isset($this->elements[$offset]); }
	public function offsetUnset($offset) { unset($this->elements[$offset]); }
	public function offsetGet($offset) { return isset($this->elements[$offset]) ? $this->elements[$offset] : null; }
	//////////////////
	
	public function addElement($element)
	{
		$this[$element['name']]=$element;
		return $this;		
	}
	public function getHTML()
	{		
		$id=isset($this->form['id']) ? " id='{$this->form['id']}'" :null;
		$class=isset($this->form['class']) ? " class='{$this->form['class']}'" :null;
		$name=isset($this->form['name']) ? " name='{$this->form['name']}'" :null;
		$action=isset($this->form['action']) ? " action='{$this->form['action']}'" :null;
		$method=" method='post' ";
		$elements=$this->getHtmlForElements();
		$html = <<< EOD
\n<form{$id}{$class}{$name}{$action}{$method}>
	<fieldset>
		{$elements}
	</fieldset>
</form>
EOD;
		return $html;
	}
	public function getHtmlForElements()
	{
		$html="";
		foreach($this->elements as $element)
		{
			$html.=$element->getHTML();
		}
		return $html;
	}
	public function setValidation($name, $vals)
	{
		$this[$name]['validation']=$vals;
		return $this;
	}
	
	public function check()
	{
		//echo"inte posta?";
		//echo$_SERVER['REQUEST_METHOD'];
		$validates = null;
		$values = array();
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			
			unset($_SESSION['form-validation-failed']);
			$validates = true;
			foreach($this->elements as $element) 
			{
				if(isset($_POST[$element['name']])) 
				{
					$values[$element['name']]['value'] = $element['value'] = $_POST[$element['name']];
					if(isset($element['validation'])) 
					{
						$element['validation-pass'] = $element->validate($element['validation']);
						if($element['validation-pass'] === false) 
						{
							$values[$element['name']] = array('value'=>$element['value'], 'validation_messages'=>$element['validation_messages']);
							$validates = false;
						}
					}
					if($validates)
					{
						//echo("hej");
						//CLydia::instance()->redirectToController();
						header('Location: ' . CBapelsin::instance()->request->createUrl("user/handler"));						
					}
				}
			}
		} 
		else if(isset($_SESSION['form-validation-failed'])) 
		{			
			//echo"fail";
			foreach($_SESSION['form-validation-failed'] as $key => $val) 
			{
				$this[$key]['value'] = $val['value'];
				if(isset($val['validation_messages'])) 
				{
					$this[$key]['validation_messages'] = $val['validation_messages'];
					$this[$key]['validation-pass'] = false;
				}
			}
			unset($_SESSION['form-validation-failed']);
		}
		if($validates === false) 
		{
			//echo"<pre>".print_r($values,true)."</pre>";
			$_SESSION['form-validation-failed'] = $values;
		}
		return $validates;
	}
}




