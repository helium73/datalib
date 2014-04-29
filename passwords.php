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
require_once('common/database/db.php');
require_once('common/database/form.php');
class PassDB extends PDO_Database{

	protected $host = 'localhost';
	protected $user = 'pass_user';
	protected $pass =  'xereg33';
	protected $dbname = 'passwords';
	protected $fields = array();

}


$pass_db = new PassDB();
$logins = $pass_db->table("logins");
$pass_form = new Form($logins);
if(empty($_POST)){
	$pass_form->display($logins);
} else{
	$pass_form->display_handler();
}

?>
	</body>
</html>

