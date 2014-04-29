<?php


interface iField{
	public function set_field($dbh, $table_name, $field_name);

	public function get_field_name();
	public function get_type();
	public function get_null();
	public function get_key();
	public function get_default();
	public function get_extra();
	public function is_primary_key();
};



Abstract class Abstract_Field implements iField{

	protected $Fieldname , $Type , $Null , $Key , $Default , $Extra, $table_name;

	public function get_field_name(){
		return $this->Fieldname;
	}
	public function get_primary_key(){
		return $is_primary_key();
	}

	public function set_field_with_array($field_array)
	{
		 $this->Fieldname  = $field_array['Fieldname'];
		 $this->Type  = $field_array['Type'];
		 $this->Null  = $field_array['Null'];
		 $this->Key  = $field_array['Key'];
		 $this->Default  = $field_array['Default'];
		 $this->Extra = $field_array['Extra'];
		 $this->table_name = $field_array['table_name;'];
	}

	public function set_field($dbh, $table_name, $field_name){
		$this->table_name = $table_name;
		$this->Fieldname = $field_name;
		$description = "describe {$this->table_name} ";
		$query_string = "SHOW COLUMNS FROM {$table_name} where Field LIKE '{$field_name}'";
		//$query_string = "SHOW COLUMNS FROM logins where Field LIKE '%%'";
			//echo $query_string;
		try{
			$stmt = $dbh->prepare($query_string);
			$stmt->execute();  // NO RESULT !!!!!!!!!  ITS AN OBJECT!!!!!!!
		}
		catch(PDOException $e) {  
			echo $query_string;
			echo $e->getMessage();
			return $e->getMessage();  
		}  
		$field_row = $stmt->fetchall();
		//echo "<pre>";
		//echo count($field_row);
		//print_r($field_row);
		//echo "</pre>";
		$this->FieldName = $field_row[0]['Field'];	
		$this->Type  = $field_row[0]['Type'];
		$this->Null  = $field_row[0]['Null'];
		$this->Key  = $field_row[0]['Key'];
		$this->Default  = $field_row[0]['Default'];
		$this->Extra = $field_row[0]['Extra'];
		
	}


	function __construct($dbh, $table_name, $field_name){
		$this->set_field($dbh, $table_name, $field_name);
	}	

	public function is_primary_key(){
		if($this->Key == "PRI"){
		   	return true;
		}
		else
		{
			return false;
		}
	}

/*
	public function get_field (){
		return 	$this->FieldName;
	}
 */
	public function get_type(){
		return $this->Type;
	}
	public function get_null(){
		return $this->Null;
	}
	public function get_key(){
		return $this->Key;
	}
	public function get_default(){
		return $this->Default;
	}
	public function get_extra(){
		return	$this->Extra;
	}


}

class Field extends Abstract_Field{
}
/*
require_once("db.php");
//have table actually return a field.  
$db = new Database();
$documents = $db->table("documents");
$vendor_id = $documents->get_field("vendor_id"); //returns a field and with that you can work with a field.

echo $vendor_id->get_type();
 */
