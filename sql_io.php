<?php
function SQL_insert_query($statement){
	if (!mysql_query($statement))
		{
			echo mysql_error();
			echo $statement;
		}
	echo "<span style=\"display:none;\">Records added</span>";
}
function SQL_truncate_query($statement){
	if (!mysql_query($statement))
		{
			//echo mysql_error();
		}
	echo "<span style=\"display:none;\">Record truncated.</span>";
}
function SQL_update_query($statement){
global $modded_fields;
global $unchanged_fields;
	if (!mysql_query($statement))
		{
			$error = mysql_error();
			return $error;
		}
	$info = mysql_info();
	$record = "Changed: 1";
	$pos = strpos($info,$record);
	if($pos === false) {
		$unchanged_fields++;
	}
	else{
		$modded_fields++;
	}
}
function SQL_delete_query($statement){
	if (!mysql_query($statement))
		{
			//echo mysql_error();
		}
	echo "<span style=\"display:none;\">Records deleted.</span>";
}

function SQL_query($statement){
	if ($result = mysql_query($statement)) {
		$i = 0;
		$db_array = array();
		while($db_field = mysql_fetch_assoc($result)) {
			foreach ($db_field as $j => $value) {
				$db_array[$i][] = $db_field[$j];
			}
		$i++;
		}
	return $db_array;
	}
	else {
		echo mysql_error();
		echo $statement;
		return "No results";
	}
}
function SQL_assoc_query($statement){
	if ($result = mysql_query($statement)) {
	return $result;
	}
	else {
		echo mysql_error();
		echo $statement;
		return "No results";
	}
}
?>
