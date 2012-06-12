<h1>Library</h1>
<h2>Modify Library</h2>

<?php
  include('../db-permissions.php');
  $db_handle = mysql_connect($hostname, $username, $password);
  $db_found = mysql_select_db($database, $db_handle);
if ($db_found) {

include('../sql_io.php');

print "<h3>Update Nodes</h3>";
print "<br>";
$modded_fields=0;
$unchanged_fields=0;

foreach($_POST as $i => $data){
$id = mysql_real_escape_string($i);
$name = mysql_real_escape_string ($data);
$description = $name;
$append = "_description";
$pos = strpos($id,$append);
	//if(($i !== "project_name")&&($i !== "project_description")&&($pos === false)){
		//print $i." ".$data."<br>";
		if($pos === false){
		/*print "<br>Name: ";
		print $name;
		print "<br>ID: ";
		print $id;*/
		$update_SQL = "UPDATE nodes SET name = '$name' WHERE id='$id' LIMIT 1;";
		//print $update_SQL."<br>";
		SQL_update_query($update_SQL);
		}
		else{
		$desc_id = substr($id,0,-12);
		/*print "<br>Desc: ";
		print $description;
		print "<br>ID: ";
		print $desc_id;
		print "<hr>";*/
		$update_SQL = "UPDATE nodes SET description = '$description' WHERE id='$id' LIMIT 1;";
		//print $update_SQL."<br>";
		SQL_update_query($update_SQL);
		}
		/*if($goal !== ""){
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
	}*/
}

print "<p>".$modded_fields." project goals modified";
print "<p>".$unchanged_fields." project goals unchanged";
?>
<hr>
<p><a href="node_mod.php" style="text-decoration:none;"><button style=" cursor:pointer;">Continue Modifying</button></a>
<p><a href="../index.php" style="text-decoration:none;"><button style=" cursor:pointer;">Home</button></a>
<?php
//$new_project_SQL = "INSERT INTO projects (name, description) VALUES ('$project_name','$project_description');";
//print $new_project_SQL;
//SQL_insert_query($new_project_SQL);

//$new_project_goals_SQL = "INSERT INTO project_goals (project_name, goal_name) VALUES $project_goals_values;";
//print $new_project_goals_SQL;
//SQL_insert_query($new_project_goals_SQL);


?>
<?php
} else {
print "nothing found.";
}
?>
