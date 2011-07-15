<html>
<head>
<?php
if($_POST['select_design_name']) {
$proto_design_name = $_POST['select_design_name'];
$proto_design_name = implode("", $proto_design_name);
}
if($_POST['design_name']) {
$proto_design_name = $_POST['design_name'];
}
if($_POST['analysis_design_name']) {
$proto_design_name = $_POST['analysis_design_name'];
}
include('db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);

?>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="functions.js"></script>
<script type="text/javascript" src="jquery.column.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var results = $("#results");
	var column1 = $('#results td:nth-col(2)');
	var column3 = $('#results td:nth-col(4)');
	var column4 = $('#results td:nth-col(5)');
	var column5 = $('#results td:nth-col(6)');
	var orderUP = $(".orderUP").parent();
	orderUP.addClass("orderUP");
	var orderDOWN = $(".orderDOWN").parent();
	orderDOWN.addClass("orderDOWN");
	$('#results tr:nth-child(odd)').addClass('alternate');
	var rows = results.children("tbody").children("tr");
	var cells = rows.children("td");
	/*for(var i=0; i<cells.length; i++){
		var parent = $(cells[i]).parent();
		var style = cells[i].innerHTML
		
		if(parent.className == "alternate"){
			stylify(parent, style);
			}
		else {
			stylify(parent, style);
			}
		}*/
	var j=1;
	columnify(column1, j, column4);
	columnify(column3, j, column4);
	columnify(column5, j, column4);
	columnify(column4, "44", column4);
	//columnclicky(column1);		
	proto_dd = $('#proto_dd');
	var designdecisions = ["<?php echo $proto_dd; ?>"];
	$("#proto_dd").val(designdecisions);
	$("#proto_dd2").val(designdecisions);
	for(var i=0; i<designdecisions.length; i++){
		for(var j=0; j<$('#results td:nth-col(2)').length; j++){
			element = $('#results td:nth-col(2)')[j];
			if(designdecisions[i] == element.innerHTML) {
				$(element).addClass("selected");
			}
		}
	}
	
});			
</script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
$order_by = $_POST['order_by'];
$order_dir = $_POST['order_dir'];
if(!$order_dir){
$order_dir="ASC";
}

if(!$order_by){
$order_by = "Soft Goal";
}
$softgoalSQL = "select r2.child as 'Design Decision', r2.type as `Affordance`, relationships.child as Action, relationships.type as `Contribution`, relationships.parent as `Soft Goal`, relationships.claim_id as `Claim ID`, class_type as `Class Type`, claim_type as `Claim Type` FROM relationships inner join claim_classes ON relationships.claim_id = claim_classes.claim_id INNER JOIN relationships as r2 ON relationships.child = r2.parent INNER JOIN claims ON claims.claim_id = relationships.claim_id INNER JOIN proto_design_nodes ON proto_design_nodes.node = r2.child WHERE (relationships.type = 'helps' OR relationships.type = 'hurts') AND proto_design_nodes.proto_design = '$proto_design_name' ORDER BY `$order_by` $order_dir;";
//print $softgoalSQL;
function formlist2 ($fieldname, $value) {
	$CatSQL = "SELECT name FROM proto_designs ORDER BY name";
	$CatCat = mysql_query($CatSQL);
		if ($required=="required"){
		$req="validate=\"required:true\"";
	} 
	else {
	$req=" ";
	}
	$CurrentoCategory= "nothing yet";
	while ($db_field = mysql_fetch_assoc($CatCat)) {
		if ($value == $db_field['name']) {
			$carl[] = "<option value=\"".$db_field['name']."\" selected=\"selected\" style=\"font-weight:bold;\">".$db_field['name']."</option>";
		}		
		else {
		$carl[] = "<option value=\"".$db_field['name']."\">".$db_field['name']."</option>";
			}
	}	
	$cats = implode($carl);
		return "<select name=\"".$fieldname."[]\" id=\"".$fieldname."\">".$cats."</select>";
}

function tr ($array, $element) {
	if($element == "td"){
	$tr = "<tr><".$element.">".implode("</".$element."><".$element.">", $array)."</".$element."></tr>";
	}
	else {
	$tr = "<tr><".$element.">".implode("</".$element."><".$element.">", $array)."</".$element."></tr>";
	}
	return $tr;
}

function SQL_query($statement){
	if ($result = mysql_query($statement)) {
		echo "<table id=\"results\" width=100% cellpadding=0 cellspacing=0 border=0>";
		$numfields = mysql_num_fields($result);
		$headerData[] = "#";
		global $class_type;
		global $order_by;
		global $order_dir;
		for ($i=0; $i < $numfields; $i++) { // Header
		$field_name = mysql_field_name($result, $i);
		if (($field_name == $order_by)&&($order_dir=="ASC")){
		$order_dir="DESC";
		}
		else if (($field_name == $order_by)&&($order_dir=="DESC")){
		$order_dir="ASC";
		}
			if (($field_name == $order_by)&&($order_dir=="ASC")) {
				$headerData[] = "<a class=\"orderUP\" onClick=\"javascript:query_link('', '".$field_name."', '".$order_dir."', '2');\">".mysql_field_name($result, $i)." ^</a>";
			}
			else if (($field_name == $order_by)&&($order_dir=="DESC")){
				$headerData[] = "<a class=\"orderDOWN\" onClick=\"javascript:query_link('', '".$field_name."', '".$order_dir."', '2');\">".mysql_field_name($result, $i)." v</a>";
			}	
			else {
				global $class_type;
				$headerData[] = "<a onClick=\"javascript:query_link('', '".$field_name."', '".$order_dir."', '2');\">".mysql_field_name($result, $i)." v</a>";
			}
		}
		echo tr($headerData, "th");
		$j=1;
		while($db_field = mysql_fetch_assoc($result)) {
			unset($rowData);
			$rowData[] = $j;
			$j++;
			foreach ($db_field as $i => $value) {
			$rowData[] = $db_field[$i];
			}		
		echo tr($rowData, "td");
		}
		echo "</table>";
	}
	else {
		die('Error: ' . mysql_error());
	}
}

if ($db_found) {
	echo "<div id=\"loader\"><h2>Review Existing Design</h2><form action=\"proto_design.php\" method=\"post\">";
	echo "<label for=select_design_name>Select a design below.</label><br>";
	echo formlist2("select_design_name", $proto_design_name);
	echo "<input type=\"submit\" value=\"Load\">";
	echo "</form></div>";
	?>
	<div id="analyser">
	<h2>Go Back</h2>
	<form action="analysis.php" method="post"">
	<input name="analysis_design_name" type="hidden" value="<?php echo $proto_design_name;?>">
	<input type="submit" style="font-size:1.3em;" value="Back to Analysis">
	</form>
	</div>
	<?php
	echo "<h1>Reviewing: <span class=\"node\">".$proto_design_name."</span></h1>";
	SQL_query($softgoalSQL);
}
else {
echo "Cannot connect to database.";
}
?>
<form action="proto_design.php" method="post" style="visibility:hidden;">
<input name="design_name" value="<?php echo $proto_design_name;?>"><label for="design_name">(Design Name)</label>
<input  name="order_by" value="<?php echo $order_by; ?>">
<input  name="order_dir" value="<?php echo $order_dir; ?>">
<input type="submit" style="font-size:1.3em;">
</form>

<form action="analysis.php" method="post"">
<input name="analysis_design_name" type="hidden" value="<?php echo $proto_design_name;?>">
<input type="submit" style="font-size:1.3em;" value="Back to Analysis">
</form>
</body>
</html>
