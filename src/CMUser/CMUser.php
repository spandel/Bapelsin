<?php
class CMUser extends CObject implements IHasSQL, ArrayAccess 
{
	 public $profile = array();

	 public function __construct($ly=null) 
	 {
	 	 parent::__construct($ly);
	 	 $profile = $this->session->GetAuthenticatedUser();
	 	 $this->profile = is_null($profile) ? array() : $profile;
	 	 $this['isAuthenticated'] = is_null($profile) ? false : true;
	 	 
	 	 if(!$this['isAuthenticated']) 
	 	 {
	 	 	 $this['id'] = 1;
	 	 	 $this['acronym'] = 'anonymous';      
	 	 }	 	 
	 }
	
	/**
	 * Implementing ArrayAccess for
   	*/
   	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->profile[] = $value; } else { $this->profile[$offset] = $value; }}
   	public function offsetExists($offset) { return isset($this->profile[$offset]); }
   	public function offsetUnset($offset) { unset($this->profile[$offset]); }
   	public function offsetGet($offset) { return isset($this->profile[$offset]) ? $this->profile[$offset] : null; }
	
   	public static function SQL($key=null) 
	{
		$queries = array(
      'drop table user'         => "DROP TABLE IF EXISTS User;",
      'drop table group'        => "DROP TABLE IF EXISTS Groups;",
      'drop table user2group'   => "DROP TABLE IF EXISTS User2Groups;",
      'create table user'       => "CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
      'create table group'      => "CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
      'create table user2group' => "CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
      'insert into user'        => 'INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);',
      'insert into group'       => 'INSERT INTO Groups (acronym,name) VALUES (?,?);',
      'insert into user2group'  => 'INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);',
      'check user password'     => 'SELECT * FROM User WHERE (acronym=? OR email=?);',
      'get group memberships'   => 'SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
      'update profile'          => "UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
      'update password'         => "UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now') WHERE id=?;",
     );
		if(!isset($queries[$key])) 
		{
			throw new Exception("No such SQL query, key '$key' was not found.");
		}
		return $queries[$key];
	}
	public function isAdministrator()
	{
		$user=$this->getUserProfile();
		$groups=$this->db->select(self::SQL('get group memberships'),array($user['id']));
		
		return  (($groups[0]['id'])==1);
	}
	public function init() 
	{
		try 
		{
			$this->db->query(self::SQL('drop table user2group'));
			$this->db->query(self::SQL('drop table group'));
			$this->db->query(self::SQL('drop table user'));
			$this->db->query(self::SQL('create table user'));
			$this->db->query(self::SQL('create table group'));
			$this->db->query(self::SQL('create table user2group'));
			$this->db->query(self::SQL('insert into user'), array('anonymous', 'Anonymous, not authenticated', null, 'plain', null, null));
			$pass=$this->createPassword('root');
			$this->db->query(self::SQL('insert into user'), array('root', 'The Administrator', 'root@dbwebb.se', $pass['algorithm'],$pass['salt'],$pass['password']));
			$idRootUser = $this->db->lastInsertId();
			$pass=$this->createPassword('doe');
			$this->db->query(self::SQL('insert into user'), array('doe', 'John/Jane Doe', 'doe@dbwebb.se', $pass['algorithm'],$pass['salt'],$pass['password']));
			$idDoeUser = $this->db->lastInsertId();
			$this->db->query(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
			$idAdminGroup = $this->db->lastInsertId();
			$this->db->query(self::SQL('insert into group'), array('user', 'The User Group'));
			$idUserGroup = $this->db->lastInsertId();
			
			$this->db->query(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
			$this->db->query(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
			$this->db->query(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
			
			$this->session->addMessage('notice', 'Successfully created the database tables and created a default admin user as root:root.');
		} 
		catch(Exception$e) 
		{
			die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
		}
	}
   	public function login($akronymOrEmail, $password) 
   	{
   		$user = $this->db->select(self::SQL('check user password'), array($akronymOrEmail, $akronymOrEmail));
   		
   		$user = (isset($user[0])) ? $user[0] : null;
   		
   		if(!$user)
   		{
   			return false;
   		}
   		else if(!$this->checkPassword($password, $user['algorithm'],$user['salt'],$user['password']))
   		{
   			//echo"hej ".$akronymOrEmail."...".$password;
   			return false;
   	   	}		
   		unset($user['algorithm']);
   		unset($user['salt']);
   		unset($user['password']);
   	
   		if($user) 
   		{   			
   			$user['groups']=$this->db->select(self::SQL('get group memberships'),array($user['id']));
   			$this->profile=$user;
   			$this->session->setAuthenticatedUser($user);
   			$this->session->addMessage('success', "Welcome '{$user['name']}'.");
   			//$this->profile=$user;
   			} 
   		else 
   		{
   			$this->session->addMessage('notice', "Could not login, user does not exists or password did not match.");
   		}
   		return ($user != null);
   	}
   	public function logout() 
   	{
   		$this->session->unsetAuthenticatedUser();
   		$this->session->addMessage('success', "You have logged out.");
   	}
   	public function isAuthenticated() 
   	{
   		return ($this->session->getAuthenticatedUser() != false);
   	}
   	public function getUserProfile() 
   	{
   		return $this->session->getAuthenticatedUser();
   	}  
   	public function save() 
   	{	
   		$this->db->query(self::SQL('update profile'), array($this['name'], $this['email'], $this['id']));
   		$this->session->setAuthenticatedUser($this->profile);
   		return $this->db->rowCount() === 1;
   	}
   	public function create()
   	{
   		$pass=$this->createPassword($this['password']);
   		/*
   		$this->db->query(self::SQL('insert into user'), array('root', 'The Administrator', 'root@dbwebb.se', $pass['algorithm'],$pass['salt'],$pass['password']));
			
   		*/
   		
   		$this->db->query(self::SQL('insert into user'), array($this['acronym'], $this['name'], $this['email'], $pass['algorithm'], $pass['salt'], $pass['password']));
   	
   		$idUser=$this->db->lastInsertId();
   		
		$this->db->query(self::SQL('insert into user2group'), array($idUser, 2));
		$this->login($this['acronym'],$this['password']);
   		$this->session->setAuthenticatedUser($this->profile);
   		
   		return $this->db->rowCount() === 1;
   	}
   	public function changePassword($password) 
   	{
   		$password=$this->createPassword($password);
   		$this->db->query(self::SQL('update password'), array($password['algorithm'],$password['salt'], $password['password'], $this['id']));
   		return $this->db->rowCount() === 1;
   	}
   	public function checkPassword($plain, $algorithm, $salt, $password)
   	{
   		switch($algorithm)
   		{
   		case 'sha1salt':
   			return sha1($salt.$plain)===$password;
   			break;
   		case 'sha1':
   			return sha1($plain)===$password;
   			break;
   		case 'md5salt':
   			return md5($salt.$plain)===$password;
   			break;
   		case 'md5':
   			return md5($plain)===$password;
   			break;
   		case 'plain':
   			return $plain===$password;
   			break;
   		default:
   			die("unknown hashing algorithm :S");
   		}
   		
   		return md5($salt.$plain)===$password;
   	}
   	public function createPassword($plain, $algorithm=null)
   	{
   		$salt=md5(microtime());
   		$pass=array();
   		if($algorithm==null)
   			$algorithm=$this->config['hashing_algorithm'];
   		
   		switch($algorithm)
   		{
   		case 'sha1salt':
   			$pass=array('salt'=>$salt,'algorithm'=>'sha1salt', 'password'=>sha1($salt.$plain));
   			break;
   		case 'sha1':
   			$pass=array('salt'=>$salt,'algorithm'=>'sha1', 'password'=>sha1($plain));
   			break;
   		case 'md5salt':
   			$pass=array('salt'=>$salt,'algorithm'=>'md5salt', 'password'=>md5($salt.$plain));
   			break;
   		case 'md5':
   			$pass=array('salt'=>$salt,'algorithm'=>'', 'password'=>md5($plain));
   			break;
   		case 'plain':
   			$pass=array('salt'=>'','algorithm'=>'', 'password'=>$plain);
   			break;
   		default:
   			die("unknown hashing algorithm :S");
   		}
   		
   		return $pass;
   	}
   	
   	
   	
   	
   	
   	
   	
}
