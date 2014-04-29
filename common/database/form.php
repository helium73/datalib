<?php

require_once("db.php");
require_once("input/input.php");
require_once("input/factory.php");


interface iForm  {
	public function set_table(iTable $table);
  	//public function add_input($fieldname);
	public function display($_ID = "");
}	


abstract class abstract_Form implements iForm{
	protected $table;
	protected $action = "";
	protected $method = "POST";
	protected $primary_key = "null";
	protected $input_list = array();
	protected $input_class_list = array();
	protected $input_objects = array();
	protected $default_input_val = "";

	public function set_default_input_val($default_input_val){
		$this->default_input_val = $default_input_val;
	}

	function __construct ($table){
		$this->table = $table;
		$this->input_list = $table->get_field_names();
		$input_class_list = array();
		
		$input_factory = new InputFactory();
		$this->default_input_val = 114; //default value for the $input

		foreach($this->input_list as $key => $value){
			$this->input_class_list[$key] = "Text_Box";
			$field = $this->table->get_field($key);
			$this->input_objects[$key] = $input_factory->createInput($this->input_class_list[$key], $field, $this->default_input_val);  //note the $val default value
		}
		echo "<pre>";
		//print_r($this->input_list);
		print_r($table->get_row(371));
		//print_r($table->get_fields());
		
		echo "</pre>";
	}
	
	public function get_row($row_id){
		return $this->table->get_row($row_id);
	}

	public function set_values($row_id){
		$row = $this->get_row($row_id);
		$input_factory = new InputFactory();
		foreach($row as $key=>$value){
			//$this->input_objects[$key] = $value;
			$field = $this->table->get_field($key);
			$this->input_objects[$key] = $input_factory->createInput($this->input_class_list[$key], $field, $value);  //note the $val default value
		}
	}

	public function get_input_object($name){
		return $this->input_objects[$name];
	}

	public function set_input_object($input, $name){
		$this->input_objects[$name] = $input; 
		
		echo "<pre>";
		//print_r($this->input_objects);
		echo "</pre>";
		
	}
	public function set_input_class($field_name, $class){
		$this->input_class_list[$field_name] = $class;
	}

	public function set_table(iTable $table){
		$this->table =  $table;
	}

	public function set_action($action){
		$this->action = $action;
	}
	public function set_method($method){
		$this->action = $method;
	}

	public function get_header(){
		return "<form action='{$this->action}' method='{$this->method}'>";
	}

	public function get_footer(){
		$submit = "<input type='submit' value='submit'/>";
		echo "$submit</form>";

	}

	public function display($_ID = ""){
		echo $this->get_header();
		if(is_numeric($_ID)){
			$this->set_values($_ID);
		}
		$field_array = $this->table->get_fields();
		$input_factory = new InputFactory();
		echo "<pre>";
		//print_r($field_array);	
		echo "</pre>";
		foreach($this->input_class_list as $key=>$field_type){
			$field = $field_array[$key];	
			//$input = $input_factory->createInput($this->input_class_list[$key], $field);	
			$input = $this->get_input_object($key);
			echo "{$input->get_code()}<br />";
		}
		echo $this->get_footer();

		if(!empty($_POST)){
			foreach($field_array as $key=>$value){ //each input should have a CORRESPONDING HANDLER
				echo "({$value->get_field_name()}:{$_POST[$value->get_field_name()]})xx<br />";
			}
		}
	}

	public function display_handler(){
		/*
		foreach($_POST as $key => $value){
			echo "$key => $value<br />";
		}
		 */	
		 $id = "";
		 if(!empty($_GET['id'])){
		 	$id = $_GET['id'];
		 }
		$this->table->add($_POST, $id);	
		/*
		echo $this->method;
		$method = "_{$this->method}";
		//$meth = "_".$method;
		echo ${$method};
		echo $_POST;
		if(!empty($_POST) && $this->method == "POST"){

		}
		if(!empty(${$method})){
			foreach(${$method} as $key=>$value){
				echo "$key:$value<br />";
			}
		}
		if(!empty($_POST)){
			foreach($_POST as $key=>$value){
				echo "$key:$value<br />";
			}
		}
		 */
	}
}
class Document_Form extends abstract_Form{
}
class Form extends abstract_Form{
}
/*
$db = new Database();
$documents = $db->table("documents");
$form = new Document_Form($documents);
$form->display();
 */
//$form->display_handler();

/*
$db = new Database();
$documents = $db->table("documents");
$form = new Document_Form($documents);
$form->display();
 */
//$form->display_handler();

