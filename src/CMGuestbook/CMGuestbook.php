<?php
class CMGuestbook extends CObject implements IHasSQL, IModule
{
	public function __construct()
	{
		parent::__construct();
	}
	public static function SQL($key=null)
	{
		$queries = array(
  			'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
  			'insert into guestbook'   => 'INSERT INTO Guestbook (entry, poet) VALUES (?,?);',
  			'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
  			'delete from guestbook'   => 'DELETE FROM Guestbook;',
  			);
  		if(!isset($queries[$key])) 
  		{
  			throw new Exception("No such SQL query, key '$key' was not found.");
  		}
  		return $queries[$key];
	}
	public function manage($action=null)
	{
		switch($action)
		{
		case 'install':
			return $this->init();
			break;
		default:
			throw new Exception('Unsupported action for this module.');
			break;
		}
	}
  	public function init() 
  	{
  		try {
			$this->db->query(self::SQL("create table guestbook"));
			return array('success', 'Created table.');
		} 
		catch(Exception$e) 
		{
			die("Failed to open database: " . $this->config['database'][0]['dsn'] . "</br>" . $e);
		}
  	}
  	public function addNewEntry($entry, $poet) 
  	{
  		$this->db->query(self::SQL("insert into guestbook"),array($entry,$poet));
		$this->session->addMessage('success', 'Added your beautiful poem.');
		if($this->db->rowCount() != 1) 
		{
			die('Failed to insert new guestbook item into database.');
		}
  	}
  	public function emptyEntries()
	{		
		$this->db->query(self::SQL("delete from guestbook"));
		$this->session->addMessage('error', 'BURN ALL THE POETRY!!!');
	}
  	public function getEntries()
	{
		$res=array();		
		
		try 
		{
			$res=$this->db->select(self::SQL('select * from guestbook'));	
		} 
		catch(Exception $e) 
		{
			echo("Could not select from db<br>".$e);
		}
		return $res;
	}
}
