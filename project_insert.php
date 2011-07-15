<?php
  include('db-permissions.php');
  $db_handle = mysql_connect($hostname, $username, $password);
  $db_found = mysql_select_db($database, $db_handle);
if ($db_found) {
if(isset($_POST['project_name'])){
$project_name = $_POST['project_name'];
$project_description = $_POST['project_description'];
$project_goals = array();
if($_POST['project_goal1']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal1']);}
if($_POST['project_goal2']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal2']);}
if($_POST['project_goal3']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal3']);}
if($_POST['project_goal4']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal4']);}
if($_POST['project_goal5']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal5']);}
if($_POST['project_goal6']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal6']);}
if($_POST['project_goal7']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal7']);}
if($_POST['project_goal8']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal8']);}
if($_POST['project_goal9']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal9']);}
if($_POST['project_goal10']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal10']);}
if($_POST['project_goal11']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal11']);}
if($_POST['project_goal12']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal12']);}
if($_POST['project_goal13']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal13']);}
if($_POST['project_goal14']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal14']);}
if($_POST['project_goal15']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal15']);}
if($_POST['project_goal16']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal16']);}
if($_POST['project_goal17']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal17']);}
if($_POST['project_goal18']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal18']);}
if($_POST['project_goal19']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal19']);}
if($_POST['project_goal20']){$project_goals[] = mysql_real_escape_string ($_POST['project_goal20']);}

$project_goals_values = "('".$project_name."', '".implode("'),('".$project_name."', '", $project_goals)."')";

/*
print $project_name;
print "<br>".$project_description;
print "<br>".$project_goals_values;
*/
include('sql_io.php');

$new_project_SQL = "INSERT INTO projects (name, description) VALUES ('$project_name','$project_description');";
//print $new_project_SQL;
SQL_insert_query($new_project_SQL);

$new_project_goals_SQL = "INSERT INTO project_goals (project_name, goal_name) VALUES $project_goals_values;";
//print $new_project_goals_SQL;
SQL_insert_query($new_project_goals_SQL);

print "<form name=\"analysis\" action=\"project.php\" method=\"post\"><input type=\"hidden\" name=\"project_name\" value=\"$project_name\"><input type=\"submit\" value=\"Analyze ".$project_name."\"/>";
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
