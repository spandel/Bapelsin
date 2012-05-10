<?php
class CFormContent extends CForm {

	private $content;

	public function __construct($content) 
	{
		parent::__construct();
		$this->content = $content;
		$save = isset($content['id']) ? 'save' : 'create';
		$this->addElement(new CFormElementHidden('id', array('value'=>$content['id'])))
    	     ->addElement(new CFormElementText('title', array('value'=>$content['title'])))
    	     ->addElement(new CFormElementText('key', array('value'=>$content['key'])))
    	     ->addElement(new CFormElementTextarea('data', array('label'=>'Content:', 'value'=>$content['data'])))
    	     ->addElement(new CFormElementText('type', array('value'=>$content['type'])))
    	     ->addElement(new CFormElementText('filter', array('value'=>$content['filter'])))
    	     ->addElement(new CFormElementSubmit($save, 'doSave'))
    	     ->addElement(new CFormElementSubmit('remove', 'doRemove'));

        $this->setValidation('title', array('not_empty'))
       	     ->setValidation('key', array('not_empty'));
    }
}
