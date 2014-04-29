<?php

//require_once(__DIR__.'/../CONFIG.php');
require_once("table.php");
abstract class PDO_Database{

	protected $host;
	protected $user;
	protected $pass;
	protected $dbname;
	protected $dbh;
	public $table;

	function __construct(){
		$this->dbh = $this->connect();
	}
	

	public function table($table_name){  //this is to get a table from the database so I guess it should return a generic table 
						 //Mysql_Table
		$this->table = new MySQL_Table($table_name, $this->dbh); //for some reason I was able to change this to a PDO_Table
		return $this->table;
	}
	public function query($sql){
		try{
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute();  // NO RESULT !!!!!!!!!  ITS AN OBJECT!!!!!!!
		}
		catch(PDOException $e) {  
			echo $e->getMessage();
			echo "error<br />";
		}  
	    return $stmt->fetchall(PDO::FETCH_ASSOC );
	}


	public function connect()
	{

		try {  
		  # MySQL with PDO_MYSQL  
			$dbh = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass,array(PDO::ATTR_ERRMODE));  
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      
			return $dbh;
		}  
		catch(PDOException $e) {  
			return $e->getMessage();  
		}  
		return $dbh;
	}
}
class Database extends PDO_Database{

	protected $host = 'localhost';
	protected $user = 'doc_db_user';
	protected $pass =  'RpvWF8UQPdHjxdQp';
	protected $dbname = 'documents_db';
	protected $fields = array();

}

/*
$db = new Database();
$rows = $db->query("select * from documents");
$dbh = $db->connect();
//$documents = new Table("documents", $dbh);
$documents = $db->table("documents");
echo "<pre>";
//print_r($rows);
//print_r($documents->get_all());
echo "</pre>";
$post_vars2 = array("company_id" => 18,
"vendor_id"=>27,
"vendor_name"=>"Mr. Rogers",
"document_date"=>"2001-12-23",
"document_number"=>273);
$documents->update(274,$post_vars2 );
$documents->insert($post_vars2 );
 */
