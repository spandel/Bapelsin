<?php
class CFormUserProfile extends CForm{
	
	public function __construct($object, $user)
	{		
		parent::__construct();
		
		$this->addElement(new CFormElementText('user', array('readonly'=>true, 'value'=>$user['acronym'])))
         	 ->addElement(new CFormElementPassword('password'))
         	 ->addElement(new CFormElementPassword('password1', array('label'=>'Password again:')))
         	 ->addElement(new CFormElementSubmit('change_password', 'doChangePassword'))
         	 ->addElement(new CFormElementText('name', array('value'=>$user['name'], 'required'=>true)))
         	 ->addElement(new CFormElementText('email', array('value'=>$user['email'], 'required'=>true)))
         	 ->addElement(new CFormElementSubmit('save', 'doProfileSave'));         	 
         	 
        $this->setValidation('name', array('not_empty'))
         	 ->setValidation('email', array('not_empty'));
	}
}
