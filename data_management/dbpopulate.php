<style type="text/css">body {font-family:monospace;}</style>
<?php
include('dbprep.php');

function insertSQL($table, $fields, $values){
$insert = "INSERT IGNORE INTO $table ($fields) VALUES ".$values;
return $insert;
}
$relationshipFields = "`child`, `type`, `parent`, `claim_id`";
$relationshipSQL = insertSQL("`relationships`", $relationshipFields, $relationshipValues);

$claimFields = "`claim_id`, `source_claim`, `publication`, `claim_type`";
$claimSQL = insertSQL("`claims`", $claimFields, $claimValues);

$nodeFields = "`description`, `name`, `type`";
$nodeSQL = insertSQL("`nodes`", $nodeFields, $nodeValues);

echo $relationshipSQL."<hr>";
echo $claimSQL."<hr>";
echo $nodeSQL."<hr>";
//echo $parentValues."<hr>";

include('new-db-permissions.php');

function SQL_query($statement){
	if (!mysql_query($statement))
		{
			die('Error: ' . mysql_error());
		}
	echo "<br><br>Records added";
}


$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found) {
	//SQL_query($relationshipSQL);
	//SQL_query($claimSQL);
	//SQL_query($nodeSQL);
}
else {
echo "Cannot connect to database.";
}
?>
