<?php
class CCUser extends CObject implements IController
{
	private $userModel;
	private $pageTitle="Bapelsin users";
	
	public function __construct()
	{
		parent::__construct();
		$this->userModel=new CMUser();
	}
	public function index()
	{
		$this->views->setTitle($this->pageTitle);
		$this->views->addInclude(__DIR__ . '/index.tpl.php', array(
			'logged_in'=>$this->userModel->isAuthenticated(),
			'profile'=>$this->userModel->getUserProfile(), 
			'formAction'=>$this->request->createUrl('user/handler')
			));
	}	
	/*public function init()
	{
		$this->userModel->init();
	}*/
	public function handler()
	{
		if(isset($_POST['doLogin']))
		{			
			$user = strip_tags($_POST['username']);
			$pass=strip_tags($_POST['password']);
			//if($user!="" && $pass!="")
			$this->login($user, $pass);			
			//else
				//$this->redirectToController('login');
		}
		else if(isset($_POST['doLogout']))
		{
			$this->logout();
		}
		else if(isset($_POST['doCreate']))
		{
			$this->createDB();
			
		}
		else if(isset($_POST['doCreateAccount']))
		{
			$this->create();
		}
		else if(isset($_POST['doChangePassword']))
		{
			$this->doChangePassword();
		}
		else if(isset($_POST['doProfileSave']))
		{
			$this->profile();
		}
		else if($this->userModel->isAuthenticated())
		{
			$this->redirectToController('profile');
		}
		else
		{
			$this->redirectToController('login');
		}
		
	}
	public function createDB()
	{
		$this->views->setTitle($this->pageTitle);
		$this->userModel->init();
		$this->redirectToController('login');
	}
	public function profile()
	{
		$form= new CFormUserProfile($this, $this->user);
		$form->form['action']=$this->request->createUrl('user/handler');
		 if($form->check() === false) 
         {
         	 $this->session->addMessage('notice', 'Some fields did not validate and the form could not be processed.');
         	 $this->redirectToController('profile');
         	// $this->doProfileSave();
         }
         else if(isset($_POST['doProfileSave']))
         {
         	 $this->doProfileSave();
		 }
         
		$this->views->setTitle($this->pageTitle);
		
		$this->views->addInclude(__DIR__ . '/profile.tpl.php', array(
			'is_authenticated'=>$this->userModel->isAuthenticated(),
			'user'=>$this->user, 
			'profile_form'=>$form->getHTML(),
			));
	}
	public function create()
	{
		$form= new CFormUserCreate($this, $this->user);
		$form->form['action']=$this->request->createUrl('user/handler');
		 if($form->check() === false) 
         {
         	 $this->session->addMessage('notice', 'Some fields did not validate and the form could not be processed.');
         	 $this->redirectToController('create');
         	 //$this->doProfileSave();
         }
         else if(isset($_POST['doCreateAccount']))
         {
         	 $this->doCreateAccount();
		 }
         
		$this->views->setTitle($this->pageTitle);
		
		$this->views->addInclude(__DIR__ . '/create.tpl.php', array(
			'is_authenticated'=>$this->userModel->isAuthenticated(),
			'user'=>$this->user, 
			'create_form'=>$form->getHTML(),
			));
	}
	public function login($username=null,$password=null)
	{
		
		$form = new CForm();
		$form->form['action']=$this->request->createUrl('user/handler');
		$form->addElement(new CFormElementText('username'));
		$form->addElement(new CFormElementPassword('password'));
		$form->addElement(new CFormElementSubmit('Logga in','doLogin'));
		$form->addElement(new CFormElementLink('Create account','user/create'));		
		
		$form->setValidation('username', array('not_empty'));
        $form->setValidation('password', array('not_empty'));
        
         if($form->check() === false) 
         {
         	 $this->session->addMessage('notice', 'Some fields did not validate and the form could not be processed.');
         	// $this->redirectToController('login');
         }
		
		if($username!=null && $password!=null)
		{
			if($this->userModel->login($username,$password))
				$this->redirectToController('profile');
			else{
				$this->session->addMessage('error', 'Password does not match username.');
				$this->redirectToController('login');
			}
		}
		else
		{
			$this->views->setTitle('Login');		
			$this->views->addInclude(__DIR__ . '/login.tpl.php', array('login_form'=>$form->getHTML(), 'formAction'=>$this->request->createUrl('user/handler')));
		}
		
	}
	public function logout()
	{
		$this->userModel->logout();
		$this->redirectToController('login');
		
	}
	public function doChangePassword() 
	{
		if($_POST['password'] != $_POST['password1'] || empty($_POST['password']) || empty($_POST['password1'])) 
		{
			$this->session->addMessage('error', 'Password does not match or is empty.');
		} 
		else 
		{
			$ret = $this->user->changePassword($_POST['password']);
			$this->session->addMessage($ret, 'Saved new password.', 'Failed updating password.');
		}
		$this->redirectToController('profile');
	}
	public function doProfileSave() 
	{
		$this->user['name'] = $_POST['name'];
		$this->user['email'] = $_POST['email'];		
		
		$ret = $this->user->save();
		$this->session->addMessage($ret, 'Saved profile.', 'Failed saving profile.');
		$this->redirectToController('profile');
	}	
	public function doCreateAccount()
	{
		if($_POST['password'] != $_POST['password1'] || empty($_POST['password']) || empty($_POST['password1'])) 
		{
			$this->session->addMessage('error', 'Password does not match or is empty.');
			$this->redirectToController('create');
		}
		
		$this->user['name'] = $_POST['name'];
		$this->user['email'] = $_POST['email'];
		$this->user['password'] = $_POST['password'];
		$this->user['acronym'] = $_POST['acronym'];
			
		$ret = $this->user->create();
		$this->session->addMessage($ret, 'Created profile.', 'Failed creating profile.');
		if($this->userModel->login($username,$password))
			$this->redirectToController('profile');
	}
}
