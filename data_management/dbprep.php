<style type="text/css">body {font-family:monospace;}</style>
<?php
include('nvivoConvert.php');
$relationship = array();
$claim = array();
$child = array();
foreach ($complete as $i => $value) {
	foreach ($complete[$i] as $j => $value) {
		//echo "<p>".$complete[$i][$j]."</p>";
	switch($j){
	case "0":
		$relationship[$i]["child"] = $complete[$i][$j];
		$child[$i]["name"] = $complete[$i][$j];
		$contribution=$complete[$i][1];
		switch($contribution){
			case "helps":
				$child[$i]["type"] = "Action";
				break;
			case "hurts":
				$child[$i]["type"] = "Action";
				break;
			case "has goal":
				$child[$i]["type"] = "Actor";
				break;
			case "affords":
				$child[$i]["type"] = "Design Decision";
				break;
			case "no_afford":
				$child[$i]["type"] = "Design Decision";
				break;
		}
		break;
	case "1":
		$relationship[$i]["type"] = $complete[$i][$j];
		
		break;
	case "2":
		$relationship[$i]["parent"] = $complete[$i][$j];
		$parent[$i]["name"] = $complete[$i][$j];
		$contribution=$complete[$i][1];
		switch($contribution){
			case "helps":
				$parent[$i]["type"] = "Soft Goal";
				break;
			case "hurts":
				$parent[$i]["type"] = "Soft Goal";
				break;
			case "has goal":
				$parent[$i]["type"] = "Soft Goal";
				break;
			case "affords":
				$parent[$i]["type"] = "Action";
				break;
			case "no_afford":
				$parent[$i]["type"] = "Action";
				break;
		}
		break;
	case "claim0":
		$claim[$i]["num"] = $complete[$i][$j];

		break;
	case "author0":
		$claim[$i]["pub"] = $complete[$i][$j];
		break;
}
		}
	}
$relationshipValues = "";
$claimValues = "";
$childValues = "";
$parentValues = "";

function selSQL_query($statement){
	if ($result = mysql_query($statement)) {
	   $i=0;
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

include('new-db-permissions.php');


foreach ($complete as $i => $value) {
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found) {
	$child_id = selSQL_query("SELECT id from nodes where name='".$relationship[$i]["child"]."' LIMIT 1;");
	$parent_id = selSQL_query("SELECT id from nodes where name='".$relationship[$i]["parent"]."' LIMIT 1;");
}
$relationshipValues[$i]="(".$child_id[0][0].",'".$relationship[$i]["type"]."',".$parent_id[0][0].",'".$claim[$i]["pub"]."_".$claim[$i]["num"]."')";

include('admin/db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found) {
	$claim_type = selSQL_query("SELECT claim_type from claims where claim_id='".$claim[$i]["pub"]."_".$claim[$i]["num"]."' LIMIT 1;");
}
$claimValues[$i]="('".$claim[$i]["pub"]."_".$claim[$i]["num"]."','".$claim[$i]["num"]."','".$claim[$i]["pub"]."','".$claim_type[0][0]."')";
//$childValues[$i]="('".$child[$i]["name"]."','".$child[$i]["type"]."')";
//$parentValues[$i]="('".$parent[$i]["name"]."','".$parent[$i]["type"]."')";

$nodeValues[]="('".$child[$i]["name"]."','".$child[$i]["type"]."')";
$nodeValues[]="('".$parent[$i]["name"]."','".$parent[$i]["type"]."')";
}
$replacement = "";

function uniqueArray($uniqueArray){
	$uniqueArray = array_unique($uniqueArray);
	$uniqueArray = array_values($uniqueArray);
	$concatArray = "";
	foreach ($uniqueArray as $i => $value) {
		$concatArray = $concatArray.$uniqueArray[$i].",";
	}
	$replacement = ";";
	$concatArray = substr($concatArray, 0, -1).$replacement;
	return $concatArray;
}
$claimValues = uniqueArray($claimValues);
//$childValues = uniqueArray($childValues);
//$parentValues =  uniqueArray($parentValues);
$relationshipValues =uniqueArray($relationshipValues);
$nodeValues = uniqueArray($nodeValues);
?>
