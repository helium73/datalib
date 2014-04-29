<?php

require_once("Input.php");
require_once(__DIR__."/../form.php");
//I could create all the input types here I guess.




class InputFactory{
	public function createInput($input_type, $field, $val){  //what does val do?  It's the initial value of the input?		
		if($input_type == 'Text_Box'){
			
			$input = new Text_Box($field, $val);
		}
		if($input_type == 'Select_Box'){
			$input = new Select_Box($field, $val);
		}
		if($input_type == 'CheckBox'){
			$input = new CheckBox();
		}
		//if($input_type == 'Vendor_Select'){
		else{
			$input = new $input_type($field, $val); //variable function.  $input_type is a string but if the string value is the same as a class then it will be used as a constructor.
		}
		return $input;
	}
}


class TextBox extends Abstract_Input{
	//protected $type1="<input type='text' />";
}

class Select extends Abstract_Input{
	protected $type1="<select><option>One</option><option>Two</option><option>Three</option></select>";
}
class CheckBox extends Abstract_Input{
	protected $type1="<input type='checkbox' />";
}

/*

$db = new Database();
$documents = $db->table("documents");

$form = new Document_Form($documents);
$form->display($documents);
$amount = $documents->get_field("amount");
$inputFactory = new InputFactory();
$_POST['input_type'] = 'select';
$input = $inputFactory->createInput($_POST['input_type'], $amount);
echo $input->get_code();
echo $documents->get_field_names();
?>
<p>
This test program extends the class by simply adding a different default value to a class that extends input.  
</p>
<p>
you don't have to write the get_type function and the get_type function gets the type.   It would be nice if you could call a class an update and then print out the extended class so that you can make it a stand alone class.   
</p>
 */
