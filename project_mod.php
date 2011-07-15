<?php
  include('db-permissions.php');
  $db_handle = mysql_connect($hostname, $username, $password);
  $db_found = mysql_select_db($database, $db_handle);
if ($db_found) {
 if(isset($_GET['project'])){
      	$ActiveProject = $_GET['project'];

include('forms.php');
include('sql_io.php');
?>
<h1>Projects</h1>
<h2>Modify Project</h2>
<?php
$project_info_SQL = "SELECT name, description FROM projects WHERE name = '".$ActiveProject."';";
$project_info = SQL_query($project_info_SQL);

$project_goals_SQL = "SELECT id, goal_name FROM project_goals WHERE project_name = '".$ActiveProject."';";
$project_goals = SQL_query($project_goals_SQL);
?>
<h3><?php print $project_info[0][0];?></h3>
<hr style="clear:both; color:#ddd;"/>
<form name="mod_project" action="project_alter.php" method="post">
<input type="hidden" name="project_name" value="<?php print $project_info[0][0];?>"/>
<br><label for="project_description" style="width:100px; display:block; float:left; clear:both; margin:5px 0 0 0">Project Description</label><textarea name="project_description" rows="5" cols="50">
<?php print $project_info[0][1];?>
</textarea>
<?php
foreach ($project_goals as $i => $goal) {
print "<hr style=\"clear:both; color:#ddd;\"/><label for=\"".$goal[0]."\" style=\"width:100px; display:block; float:left; margin:5px 0 0 0\">Goal ".$goal[0]."</label><input type=\"text\" name=\"".$goal[0]."\" value=\"".$goal[1]."\" size=100/>";
}
print "<hr>\n<h4>Add goals to project</h4>";
for($x = 1; $x <=10; $x++){
print "<hr style=\"clear:both; color:#ddd;\"/><label for=\"new_project_goal".$x."\" style=\"width:100px; display:block; float:left; margin:5px 0 0 0\">New Goal ".$x."</label><input type=\"text\" name=\"new_project_goal".$x."\" size=100 />";
}
?>
<hr style="clear:both; color:#ddd;"/>
<input type="submit" value="Modify &quot;<?php print $project_info[0][0];?>&quot;"/>
</form>
<?php
}
else {
print "no project specified";
}
}
else {
print "db not found";
}
?>
