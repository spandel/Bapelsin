<?php
class CDatabase{
	private $db = null;
	private $stmt = null;
	private static $numQueries = 0;
	private static $queries = array();
	
	public function __construct($dsn, $username=null, $password=null, $driver_options=null)
	{
		$this->db = new PDO($dsn, $username, $password, $driver_options);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	}
	public function setAttribute($attribute, $value) 
	{
		return $this->db->setAttribute($attribute, $value);
	}
	public function GetNumQueries() { return self::$numQueries; }
	public function GetQueries() { return self::$queries; }

	public function select($query, $params=array())
	{
		$this->stmt = $this->db->prepare($query);
		self::$queries[] = $query; 
		self::$numQueries++;
		$this->stmt->execute($params);
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	 public function query($query, $params = array()) 
	 {
	 	 $this->stmt = $this->db->prepare($query);
	 	 self::$queries[] = $query; 
	 	 self::$numQueries++;
	 	 return $this->stmt->execute($params);
	 }
	 public function rowCount() 
	 {
	 	 return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
	 }
	 public function lastInsertID()
	 {
	 	 return $this->db->lastInsertID();
	 }
}
