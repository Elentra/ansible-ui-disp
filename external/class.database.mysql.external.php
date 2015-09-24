<?
/*
	Описание:
	Класс для работы с базой данных MySQL

	Исключения:
	"Database address not provided"
	"Database login not provided"
	"Database name not provided"
	"Failed to establish connection with database"
	"Trying to create database with empty name"
	"Table name was empty"

*/
class Database {

/* Database settings */
protected $dbAddress;
protected $dbLogin;
protected $dbPassword;
protected $dbPort;
protected $dbDatabase;
protected $dbTablePrefix;

/* Specific setting & descriptors */
protected $dbConnectionLink;

	public function __construct($connAddress, $connLogin, $connPassword, $connDatabase, $connTablePrefix = "")
	{
		if($connAddress == "")
		{
			throw new Exception("Database address not provided");
			return false;
		}
		if($connLogin == "")
		{
			throw new Exception("Database login not provided");
			return false;
		}
		if($connDatabase == "")
		{
			throw new Exception("Database name not provided");
			return false;
		}
		$this->dbAddress = $connAddress;
		$this->dbLogin = $connLogin;
		$this->dbPassword = $connPassword;
		$this->dbDatabase = $connDatabase;
		$this->dbTablePrefix = $connTablePrefix;
		$dbConnected = $this->establishConnection();
		if(($dbConnected) and ($this->dbConnectionLink))
		{
			$this->structureCheck();
		}
	}
	private function establishConnection()
	{
		$this->dbConnectionLink = @mysql_connect($this->dbAddress, $this->dbLogin, $this->dbPassword);
		if($this->dbConnectionLink === false)
		{
			throw new Exception("Failed to establish connection with database");
			return false;
		} else {
			return true;
		}
		
	}
	private function structureCheck()
	{
		$dbSelected = @mysql_select_db($this->dbDatabase, $this->dbConnectionLink);
		if($dbSelected)
		{
			return true;
		} else {
			$dbCreateReport = $this->createDatabase($this->dbDatabase);
			if($dbCreateReport)
			{
				$dbSelected = @mysql_select_db($this->dbDatabase, $this->dbConnectionLink);
			}
		}
	}
	private function createDatabase($dbName = "")
	{
		if($dbName == "")
		{
			throw new Exception("Trying to create database with empty name");
			return false;
		}
		$dbCreated = mysql_query("CREATE DATABASE `".$dbName."` CHARACTER SET utf8;", $this->dbConnectionLink);
		if($dbCreated)
		{
			echo "Database ".$dbName." successfully created<br/>";
			return true;
		} else {
			echo "Can't create database [".$dbName."]<br/>";
		}
		return false;
	}
	public function getData($tableName, $queryTail = "")
	{
		if($tableName == "")
		{
			throw new Exception("Table name was empty");
			return false;		
		}
		$dbQuery = "SELECT * FROM `".$tableName."`";
		if($queryTail != "")
		{
			$dbQuery .= " ".$queryTail;
		}
		$dbQuery .= ";";
		$getResult = @mysql_query($dbQuery);
		if($getResult === false)
		{
			return false;
		} else {
			while($entryRow = mysql_fetch_array($getResult))
			{
				$resultArray[] = $entryRow;
			}
			return $resultArray;
		}
	}
	public function escapeData($escData)
	{
		$escData = mysql_real_escape_string($escData);
		return $escData;
	}
}
?>
