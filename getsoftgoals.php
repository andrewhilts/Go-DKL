<?php
include('db-permissions.php');
$softgoalSQL = "select claim_id, child, relationships.type, parent FROM relationships INNER JOIN nodes ON nodes.name = relationships.parent WHERE nodes.type = 'soft goal' AND (relationships.type = 'helps' OR relationships.type = 'hurts') ORDER BY relationships.type;";

function tr ($array) {
	$tr = "<tr><td>".implode("</td><td>", $array)."</td></tr>";
	return $tr;
}

function SQL_query($statement){
	if ($result = mysql_query($statement)) {
		echo "<table>";
		while($db_field = mysql_fetch_assoc($result)) {
			unset($rowData);
			foreach ($db_field as $i => $value) {
			$rowData[] = $db_field[$i];
			}		
		echo tr($rowData);
		}
		echo "</table>";
	}
	else {
		die('Error: ' . mysql_error());
	}
}

$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found) {
	SQL_query($softgoalSQL);
}
else {
echo "Cannot connect to database.";
}

