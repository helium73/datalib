<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="\jquery-ui-1.10.3.custom\css\smoothness\jquery-ui-1.10.3.custom.min.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

		<script "text/javascript">
			$(document).ready(function(){

			});
		</script>
		<title></title>
		<style>
		</style>
	</head>
	<body>
		<div class="slider"></div>

<?php
//echo "{$_GET['name']}<br />";
//echo $_GET['email'];




require_once("common/database/form.php");


class Vendor_Select extends Select_Box{
	public $select_options = array("1"=>"Martin's", "2"=>"Betty's", "4"=> "Tammy's");
}


$db = new Database();
$documents = $db->table("documents");
//$documents->get_
$form = new Document_Form($documents);
$input_factory = new InputFactory();


$vendor_id_field = $documents->get_field("vendor_id");
$vendor_input = $input_factory->createInput("Select_Box", $vendor_id_field, 123); 
$form->set_input_object($vendor_input, "vendor_id");


$company_id_field = $documents->get_field("company_id");
$company_input = $input_factory->createInput("Vendor_Select", $company_id_field, 123); 
$form->set_input_object($company_input, "company_id");



//$form->set_input_class("vendor_id", "Select_Box"); //it sets the input class but I want be able to alter this one
//$vendor_input = $form->get_input_object("vendor_id");

//if I could set_set_input object or create an input separately and add that  it would work a lot better.
//$input = new Input($field);
if(empty($_POST)){
	//$rows = $documents->get_fields();
	//$options = array("action"=>"get", "method"=>"get", "test"=>"testval");
	$form->display($documents);
} else{
	$form->display_handler();
	/*
	foreach($_POST as $key => $value){
		echo "$key => $value<br />";
	}
	$documents->insert($_POST);	
	 */
}
?>
		<input id='sql' value="(SELECT * FROM documents ORDER BY document_id desc LIMIT 10) ORDER BY document_id ASC;"  />
<script>

 document.getElementById("sql").onclick = function(){
document.getElementById("sql").select();
}
</script>
	</body>
</html>

