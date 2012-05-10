<?php
class CMContent extends CObject implements IHasSQL, ArrayAccess, IModule {
	
	public $data;

	public function __construct($id=null) 
	{
		parent::__construct();
		if($id) 
		{
			$this->loadById($id);
		} 
		else 
		{
			$this->data = array();
		}
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
	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->data[$offset]); }
	public function offsetUnset($offset) { unset($this->data[$offset]); }
	public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }

	public static function SQL($key=null, $args=null) 
	{
		$order_order  = isset($args['order-order']) ? $args['order-order'] : 'ASC';
		$order_by     = isset($args['order-by'])    ? $args['order-by'] : 'id';    
		$queries = array(
			'drop table content'      => "DROP TABLE IF EXISTS Content;",
			'create table content'    => "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
			'insert content'          => 'INSERT INTO Content (key,type,title,data,filter,idUser) VALUES (?,?,?,?,?,?);',
			'select * by id'          => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=?;',
			'select * by key'         => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=?;',
			'select * by type'        => "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? ORDER BY {$order_by} {$order_order};",
			'select *'                => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id;',
			'update content'          => "UPDATE Content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
			);
		if(!isset($queries[$key])) 
		{
			throw new Exception("No such SQL query, key '$key' was not found.");
		}
		return $queries[$key];
	}

	public function init() 
	{
		try 
		{
			$this->db->query(self::SQL('drop table content'));
			$this->db->query(self::SQL('create table content'));
			$this->db->query(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', 'This is a demo post.', 'plain', $this->user['id']));
			return array('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
		} catch(Exception$e) 
		{			
			die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
		}
	}
  
	public function save() 
	{
		$msg = null;
		if($this['id']) 
		{
			$this->db->query(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
			$msg = 'update';
		} else 
		{
			$this->db->query(self::SQL('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this->user['id']));
			$this['id'] = $this->db->lastInsertId();
			$msg = 'created';
		}
		$rowcount = $this->db->rowCount();
		if($rowcount) 
		{
			$this->session->addMessage('success', "Successfully {$msg} content '{$this['key']}'.");
		} else 
		{
			$this->session->addMessage('error', "Failed to {$msg} content '{$this['key']}'.");
		}
		return $rowcount === 1;
	}
    
	public function loadById($id) 
	{
		$res = $this->db->select(self::SQL('select * by id'), array($id));
		if(empty($res)) 
		{
			$this->session->addMessage('error', "Failed to load content with id '$id'.");
			return false;
		} 
		else 
		{
			$this->data = $res[0];
		}
		return true;
	}
	public function listAll($args=null) 
	{
		try 
		{
			if(isset($args) && isset($args['type'])) 
			{
				return $this->db->select(self::SQL('select * by type', $args), array($args['type']));
			} 
			else 
			{
				return $this->db->select(self::SQL('select *', $args));
			}
		} catch(Exception $e) 
		{
			echo $e;
			return null;
		}
	}  
	public static function filter($data, $filter)
	{
		switch($filter)
		{/*
		case 'php':
			$data=nl2br(makeClickable(eval('?>'.$data)));
			break;
		case 'html':
			$data=nl2br(makeClickable($data));
			break;*/
		case 'htmlpurify':
			$data=nl2br(CHTMLPurifier::purify($data));
			break;
		case 'bbcode':
			$data=nl2br(bbcode2html(htmlent($data)));
			break;
		case 'plain':
		default:
			$data=nl2br(makeClickable(htmlent($data)));
		}
		return $data;
	}
	public function getFilteredData()
	{
		return $this->filter($this['data'],$this['filter']);
	}
}
