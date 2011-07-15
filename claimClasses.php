<?php
function csvify($csvarr, $file) {
	$row = 1;
	if (($handle = fopen($file, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
		$num = count($data);
			$num = count($data);
			$row++;
			for ($c=0; $c < $num; $c++) {
			if(($data[$c] !== "")&&($c != 0)){
				$claimClasses[] = array($data[0],$data[$c]);
			}
			}
		}
	}
return $claimClasses;
}
$claims = csvify($claims, "claim_classes.csv");

foreach ($claims as $i => $value) {
$claim_id[]="('".$claims[$i][0]."')";
$claim_class[] = "('".$claims[$i][1]."')";
$claim_links[] = "('".$claims[$i][0]."','".$claims[$i][1]."')";
}

function uniqueArray($uniqueArray){
	$uniqueArray = array_unique($uniqueArray);
	$uniqueArray = array_values($uniqueArray);
		foreach ($uniqueArray as $i => $value) {
		$concatArray = $concatArray.$uniqueArray[$i].",";
	}
	$replacement = "";
	$concatArray = substr($concatArray, 0, -1).$replacement;
	return $concatArray;
}
$claim_id = uniqueArray($claim_id);
$claim_class= uniqueArray($claim_class);
$claim_links = uniqueArray($claim_links);



include('db-permissions.php');

function insertSQL($table, $fields, $values){
$insert = "INSERT INTO $table ($fields) VALUES ".$values;
return $insert;
}
$classFields = "`type`";
$classSQL = insertSQL("`claim_class_types`", $classFields, $claim_class);


$claimFields = "`claim_id`, `class_type`";
$claimSQL = insertSQL("`claim_classes`", $claimFields, $claim_links);
echo $classSQL;
echo $claimSQL;
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
	SQL_query($claimSQL);
	SQL_query($classSQL);
}
else {
echo "Cannot connect to database.";
}

?>
