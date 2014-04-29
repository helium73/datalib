<?php
require_once(__DIR__."/../db.php");
//require_once("field.php");

interface Input{
	public function get_code();
	public function set_code(Field $field);
	public function set_type($field);
}

interface Option_List{
	public function list_table($table);
	public function value($field); //Field that contains the value
	public function text($field); //Field that contains the text 
}

abstract class Abstract_Input implements Input{


	protected  $code; 
	protected  $type = "text"; 
	protected  $field_type = "varchar(20)"; 
	protected  $id; 
	protected  $name; 
	protected  $val; 
	protected  $subtext;
	protected  $tag = "input";
	protected $Field , $Type , $Null , $Key , $Default , $Extra;
	protected $field_info = array("Field"=>"" , "Type"=>"", "Null"=>"", "Key"=>"" , "Default"=>"" , "Extra"=>"");

	
	public function __construct(Field $field, $val){

		$this->val = $val;
		$this->set_code($field);
	}
	public function get_code(){
		return $this->code;
	}
	public function set_code(Field $field){
		$this->set_type($field);
		$this->id = $field->get_field_name();
		$this->name= $field->get_field_name();
		$this->value=  $field->get_field_name();
		$this->code = "<label for='{$this->name}' >{$this->name}<span>{$this->subtext}</span></label><$this->tag type='{$this->type}' id='{$this->id}' name='{$this->name}' value='$this->val' />";
	}

	public function set_type($field){
		$this->type = "text";  //this needs to be overwritten in the Select and it will set the type each time easily
	}

}


class Text_Box extends Abstract_Input{
}

class Select_Box extends Abstract_Input{

	public	$select_options = array("1"=>"mooney", "2"=>"rooney", "4"=> "tooney");
	public function set_code(Field $field){
		$this->tag = "select";
		$this->id = $field->get_field_name();
		$this->name= $field->get_field_name();
		$this->value= $field->get_field_name();
		$this->code = "<label for='{$this->name}' >{$this->name}<span>{$this->subtext}</span></label><$this->tag type='select' id='{$this->id}' name='{$this->name}' value='{$this->value}' >";
			
	
		foreach($this->select_options as $key=>$value){
			$this->code.="<option value='{$key}'>{$value}</option>";	
		}
		$this->code .= "</select>";
	}
	public function set_lookup($table, $value_field, $option_field){
		$this->lookup_table = $table;
	}
	public static function options($rows){
		
		$select_options = array();
		foreach($rows as $key=>$value){
			$select_options[$key]["value"] = $value["vendor_id"];
			$select_options[$key]["text"] = $value["vendor_name"];
		}
		return $select_options;
	}
}

