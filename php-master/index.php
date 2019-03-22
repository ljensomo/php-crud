<?php
	include("Class/Connection.php");
	include("Class/Query.php");
	$query = new Query();

	# INSERT RECORD
	// $query->insert("tbl_table1",array("name"=>"john2","email"=>"john2@mail.com"));
	// $query->printErrorsJSON();

	# SELECT RECORD
	// $record = $query->select("tbl_table1");
	// // $query->printErrorsJSON();
	// foreach ($record as $key => $value) {
	// 	echo $value['id'] . " " . $value['name'] . " " . $value['email'] . "<br>";
	// }

	# DELETE RECORD
	// $query->delete("tbl_table1",["id"=>9]);
	// $query->printErrorsJSON();

	# UPDATE RECORD
	// $columns = array("name"=>"Juan Dela Cruz","email"=>"juandc@email.com");
	// $query->update("tbl_table1",$columns,["id"=>10]);

?>