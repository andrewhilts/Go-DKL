<?php
/**
 * Step 2: Requires that the user has selected one of his/her project goals 
 * to analyse in more detail (the "active goal"). List all "aggregate goals" 
 * (goal categories) that the user can associate with the active goal.
 */
include('menu/shared.php');
$ActiveProject = active_project();

include('admin/db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){
include('engine/live_queries.php');
include('admin/sql_io.php');
include('html_templates/forms.php');

$ActiveGoal = active_goal($ActiveProject);

$library_claim_classes_SQL = 	select_library_claim_classes_SQL();
$select_classes_SQL = select_classes_SQL($ActiveGoal['id']);
$retrieved_classes = SQL_query($select_classes_SQL);
?>
<html>
<head>
    <title><?php print $site_name;?>- Goal Association</title>
    <link rel="stylesheet" href="css/default.css" type="text/css"/>
    <link rel="stylesheet" href="css/jquery.multiselect2side.css" type="text/css"/>
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.multiselect2side.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("option:even").addClass('alternate');
            $("#claim_classes").multiselect2side();
        });
    </script>
</head>
<body>

<?php

if(isset($_GET['message'])){
redirect_msg();
}
?>
<h1><span class="active_project"><?php print $ActiveProject;?></span>: <?php print $site_name; ?></h1>
<?php include('menu/menu.php');?>
<div id="project_goal" class="bigDiv">
    <h2>STEP 1.1: Associate Project Goal</h2>
<div id="goal_classes">
        <h3>Associate the active goal with one or more aggregate goals.</span></h3>
        <p>Active Goal: <span class="active"><?php print $ActiveGoal['name'];?></a>
        <p>
        <form name="project_goal_association" action="engine/form_processing.php?type=association&project=<?php print $ActiveProject;?>&projgoal=<?php print $ActiveGoal['id'];?>" method="post">
        <?php
        print formlist("claim_classes", $retrieved_classes,$library_claim_classes_SQL, "type", "id", true);
        ?>
        <br>
        <input type="radio" name="ACTION_goal_classes" value="next" checked> No changes to project goal association.<br>
        <input type="radio" name="ACTION_goal_classes" value="insert"> Add selected classes to project goal association.<br>
        <input type="radio" name="ACTION_goal_classes" value="delete"> Remove selected classes from project goal association.<br>
        <input type="submit" value="Save Goal Association"></div>
        </form>
        <?php
        if(isset($retrieved_classes)){
		}
		else{
			print "<p>*Associate the Active Goal before browsing related Library Goals.";
		}
		?>
    </div>
    
</div>
</body>
</html>
<?php
}
