<?php
include('menu/shared.php');
$ActiveProject = active_project();
include('admin/db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){

include('engine/live_queries.php');
include('admin/sql_io.php');
include('html_templates/forms.php');

$project_goals_SQL = 		select_project_goals_SQL($ActiveProject);
?>
<html>
<head>
    <title><?php print $site_name;?>- Goal Association</title>
    <link rel="stylesheet" href="css/default.css" type="text/css"/>
</head>
<body>
<?php
if(isset($_GET['message'])){
redirect_msg();
}
?>
<h1><span class="active"><?php print $ActiveProject;?></span>: <?php print $site_name; ?></h1>
<?php include('menu/menu.php');?> 
<div id="scenario_goal" class="bigDiv">
    <h2>STEP 1: Select Project Goal</h2>
    <h3>Select Project Goal to Analyze</h3>
    <div>
    <form name="projgoal" action="goal_association.php?project=<?php print $ActiveProject;?>" method="post">
    <?php
    print formlist("projgoal", null, $project_goals_SQL, "goal_name", "id", null, null);
    ?>
    <input type="submit" value="Select Goal">
    </form>
    </div>
</div>
</body>
</html>
<?php
}
