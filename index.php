<?php
include('shared.php');
include('db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){

include('sql_io.php');
$list_projects_SQL = "SELECT name FROM projects WHERE name <> 'New Diaspora Dialogues' AND name <> 'Zilino' AND name <> 'LVG' AND name <> 'Facebook Transportation Conversations' AND name <> 'Digital Democracy' AND name <> 'Collaboratorium' order by name;";
$existing_projects = SQL_query($list_projects_SQL);
?>
<html>
<head>
    <title><?php print $site_name;?>- Goal Association</title>
    <link rel="stylesheet" href="default.css" type="text/css"/>
    <script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
  $(document).ready(function(){ 
  $("#menu tr:even").addClass('alternate');
});
</script>
</head>
<body>
<?php
if(isset($_GET['message'])){
redirect_msg();
}
?>

<h1><?php print $site_name; ?></h1>
<?php include('menu.php');?> 
<div id="scenario_goal" class="bigDiv">
<h2>Projects</h2>
<div>
<P>Select a project to analyze below. Alternatively, create a new project or modify an existing one.

<?php
foreach($existing_projects as $id => $project){
print "<p><a href=\"goal_selection.php?project=".$project[0]."\"><button style=\"cursor:pointer; font-size:1.8em; font-weight:bold; padding:5px;\" class=\"project_name\">".$project[0]."</button></a> \n\t<span class=\"mod_link\"><a href=\"project_mod.php?project=".$project[0]."\">Modify</a></span>\n";
}
?>
<hr>
<p><a href="project_create.php" style="text-decoration:none; font-size:1.2em; color:blue;"><button style=" cursor:pointer; font-size:1.2em; color:blue;">New Project</button></a>
</div>
</div>
<?php
}
else {
print "db not found";
}
?>
</body>
</html>
