<h1>Projects</h1>
<h2>Modify Project</h2>

<?php
  include('db-permissions.php');
  $db_handle = mysql_connect($hostname, $username, $password);
  $db_found = mysql_select_db($database, $db_handle);
if ($db_found) {
if(isset($_POST['project_name'])){
$project_name = mysql_real_escape_string($_POST['project_name']);
$project_description = mysql_real_escape_string($_POST['project_description']);

include('sql_io.php');

print "<h3>".$project_name."</h3>";
print "<br>";
$modded_fields=0;
$unchanged_fields=0;
$insert_values = array();

foreach($_POST as $i => $data){
$new_goal = "new_project_goal";
$pos = strpos($i,$new_goal);
	if(($i !== "project_name")&&($i !== "project_description")&&($pos === false)){
		//print $i." ".$data."<br>";
		$id = mysql_real_escape_string($i);
		$goal = mysql_real_escape_string ($data);
		if($goal !== ""){
		$update_SQL = "UPDATE project_goals SET goal_name = '$goal' WHERE id='$id' AND project_name='$project_name' LIMIT 1;";
		//print $update_SQL."<br>";
		}
		else{
		$update_SQL = "DELETE FROM project_goals WHERE id='$id' AND project_name='$project_name' LIMIT 1;";
		}
		SQL_update_query($update_SQL);
	}
	elseif(($i !== "project_name")&&($i !== "project_description")&&($pos = true)){
		$new_goal = mysql_real_escape_string ($data);
		if($new_goal !== ""){
		$insert_values[] = $new_goal;
		}
	}
}
print "<p>".$modded_fields." project goals modified";
print "<p>".$unchanged_fields." project goals unchanged";
if($insert_values[0]){
$new_project_goals_values = "('".$project_name."', '".implode("'),('".$project_name."', '", $insert_values)."')";
$new_project_goals_SQL = "INSERT INTO project_goals (project_name, goal_name) VALUES $new_project_goals_values;";
print "<p>".count($new_project_goals_values)." project goals added";
SQL_insert_query($new_project_goals_SQL);
}
?>
<hr>
<p><a href="project_analysis.php?project=<?php print $project_name; ?>" style="text-decoration:none;"><button style=" cursor:pointer;">Analyze &quot;<?php print $project_name; ?>&quot;</a></a>
<?php
//$new_project_SQL = "INSERT INTO projects (name, description) VALUES ('$project_name','$project_description');";
//print $new_project_SQL;
//SQL_insert_query($new_project_SQL);

//$new_project_goals_SQL = "INSERT INTO project_goals (project_name, goal_name) VALUES $project_goals_values;";
//print $new_project_goals_SQL;
//SQL_insert_query($new_project_goals_SQL);


?>
<?php
}
else{
print "no project name given.";
}
} else {
print "nothing found.";
}
?>
