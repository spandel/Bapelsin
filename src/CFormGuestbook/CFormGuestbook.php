<?php
class CFormGuestbook extends CForm {

	private $gb;

	public function __construct($gb=null) 
	{
		parent::__construct();
		
		$this->gb = $gb;
		$this->addElement(new CFormElementText('poet', array()))
    	     ->addElement(new CFormElementTextarea('poem', array()))    	     
    	     ->addElement(new CFormElementSubmit('add poem', 'doAdd'))
    	     ->addElement(new CFormElementSubmit('clear poems', 'doClear'));

        $this->setValidation('poet', array('not_empty'))
       	     ->setValidation('poem', array('not_empty'));
    }
}
