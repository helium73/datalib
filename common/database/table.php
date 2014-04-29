<?php
require_once('field.php');



abstract class PDO_Table { //i should create an interface called Table so that I can use the word Table in other places yet actually use derived versions of that table 
	protected $table_name;
	protected $database;
	protected $primary_key;
	protected $dbh;



	public function get_field($field_name){
		$field =  new Field($this->dbh, $this->table_name, $field_name);
		//echo $field->get_field_name();
		return $field;
	}

	public function get_primary_key(){
		$sql = "SHOW KEYS FROM {$this->table_name} WHERE Key_name = 'PRIMARY'";

		try{
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute();  // NO RESULT !!!!!!!!!  ITS AN OBJECT!!!!!!!
		}
		catch(PDOException $e) {  
			return $e->getMessage();  
		}  
		$row = $stmt->fetch();
		return $row["Column_name"];
	}



	public function __construct($table_name, $dbh){
		$this->table_name = $table_name;
		$this->dbh = $dbh;
		//$this->fields = 
	    //$this->get_fields();
		$this->primary_key = $this->get_primary_key();
	}

	public function get_field_names(){
		echo "<pre>";

		$field_names = array();
		//print_r($this->fields);	
		$fields = $this->get_fields();
		
		foreach($fields as $key=>$value){
			$name = $value->get_field_name();
			$field_names[$name] = $value->get_type();

		}
		//print_r($this->fields["Field"]);
		//print_r($field_names);
		
		echo "</pre>";
		return $field_names;
	}

		


	public function get_fields(){	
		$description = "describe {$this->table_name} ";

		try{
			$stmt = $this->dbh->prepare("describe {$this->table_name}");
			$stmt->execute();  // NO RESULT !!!!!!!!!  ITS AN OBJECT!!!!!!!
		}
		catch(PDOException $e) {  
			return $e->getMessage();  
		}  
		$this->fields = $stmt->fetchall();
		//echo "<pre>";
		//print_r($this->fields);
		//echo "</pre>";
		$field_objects = array();
		foreach($this->fields as $key=>$value){
			$field_objects[$value['Field']] = $this->get_field($value['Field']);
		}
		return $field_objects;



		//return $this->fields;
	}


	public function get_all(){
		//$description = "describe {$this->table_name} ";

		try{
			$stmt = $this->dbh->prepare("SELECT * FROM {$this->table_name}");
			$stmt->execute();  // NO RESULT !!!!!!!!!  ITS AN OBJECT!!!!!!!
		}
		catch(PDOException $e) {  
			return $e->getMessage();  
		}  
		$this->fields = $stmt->fetchall();

		return $this->fields;
	}

	public function get_row($key){
		//$description = "describe {$this->table_name} ";

		try{
			$stmt = $this->dbh->prepare("SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = $key");
			$stmt->execute();  // NO RESULT !!!!!!!!!  ITS AN OBJECT!!!!!!!
		}
		catch(PDOException $e) {  
			return $e->getMessage();  
		}  
		$this->row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this->row;
	}

	protected function update_vars($value){
		return "$value = :$value";
	}

}

function array_take($array, $key){
	$value = $array[$key];	
	unset($array[$key]);
	return $value;
}


class Mysql_Table extends PDO_Table {

	private function key_mirror($keys){ //makes the keys the same 
		$field_names = array();
		foreach($keys as $key=>$value){
			$field_names[$key] = $key;
		}
		return $field_names;
	}


	public function add($post_vars, $table_key = null){
		if($table_key == null){
			$this->insert($post_vars);
		}
		else{
			$this->update($table_key, $post_vars);
		}
		
	}
	public function update($table_key, $post_vars){

		$field_names = array();
		echo "<pre>";
		//print_r($this->get_fields());
		echo "</pre>";
		foreach($this->get_fields() as $row){
			$field_names[$row->get_field_name()] = $row->get_field_name();    //make values equal keys
		}
		$update_values = array_intersect_key($post_vars,$field_names); //create an array of values to insert
		$update_array = array_map(array("PDO_Table","update_vars"), $this->key_mirror($update_values));
		$sql =  "UPDATE {$this->table_name} SET ".implode(",", $update_array )." WHERE {$this->primary_key}=:{$this->primary_key}";

		$update_values[$this->primary_key] = $table_key;
		try{
			$q = $this->dbh->prepare($sql);
			$q->execute($update_values);
		}
		catch(PDOException $e) {  
			echo $e->getMessage();  
		}  
	}



	public function update_new($table_key, $post_vars){ //just needs an associative array
		foreach($this->fields as $key => $value){   //this could be an insert function
			if($value['Extra'] != "auto_increment"){
				$field[] = ("{$value['Field']}"); //we are just getting the fields 
					if(!empty($post_vars[$value['Field']])){
						$field_name = $value['Field'];
						$field_val =$post_vars[$field_name] ;
					}
					else{
						$field_val = 0;
					}
				$input_parameters[$value['Field']] = $field_val;
			}
		}
		$update_array = array_map(array("Table","update_vars"), $field);
		print_r($update_array);
		$sql =  "UPDATE {$this->table_name} SET(".implode(",", $update_array ).")";
			

		try{
			$q = $this->dbh->prepare($sql);
			$q->execute($input_parameters);
		}
		catch(PDOException $e) {  
			echo $e->getMessage();  
		}  
	}


	public function insert($post_vars){ //just needs an associative array
			
		$fields = $this->get_fields();
		foreach($fields as $key => $value){   //this could be an insert function
			if($value->get_extra() != "auto_increment" && $value->get_key() != 'PRI'){
				$field[] = ("{$value->get_field_name()}"); //we are just getting the fields 
				if(!empty($post_vars[$value->get_field_name()])){
						$field_name = $value->get_field_name();
						$field_val =$post_vars[$field_name] ;
					}
					else{
						$field_val = 0;
					}
				$input_parameters[$value->get_field_name()] = $field_val;
			}
		}
		$sql =  "INSERT INTO {$this->table_name} (".implode(",", $field ).") VALUES (:".
				implode(",:", $field).")";
			
		try{
			$q = $this->dbh->prepare($sql);
			$q->execute($input_parameters);
		}
		catch(PDOException $e) {  
			echo $e->getMessage();  
		}  
	}
}

?>
