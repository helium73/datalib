<?php
require_once("db.php");
class Report {

	//a report is where you combine tables.   A report should be able to do a basic query on multiple tables. therefore it should probably just be dependent on the
	//database.  If you need to print a report that just uses one table then you should 
	protected $dbh;
	protected $primary_key_column; 

	public function set_primary_key_column($column_name){
		$this->primary_key_column = $column_name;
	}
	function __construct($query){
		$this->query = $query;
		$this->dbh = new Database(); 
	}

	public function to_array($start, $num_rows){
		return $this->dbh->query($this->query." limit $start, $num_rows "); // so this is report but we want to print everything
	}

	public function print_report($start, $num_rows, $filename){
	$doc_array = $this->to_array($start, $num_rows);
	echo "<table>\n";
		echo "<tr>\n";
		foreach($doc_array[0] as $key=>$value){
			echo "<td>$key</td>";
		}
		echo "<tr />\n";
	foreach($doc_array as $key=>$row){
		echo "<tr>\n";
		foreach($row as $key=>$value){
			echo "<td>$value</td>\n";
		}
        echo "<td><a href='report_test.php?id={$row[$this->primary_key_column]}'>";
		echo "edit item</a></td>";	
		echo "<tr />\n";
	}
}


}

?>
<style>
td{
	border: solid 1px #000;
}
</style>

