<?php

//Step(s) of associating Actions, Design Alternatives?


//print_r($_POST);

if ($_SERVER['HTTP_HOST']==='localhost') {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}

include('shared.php');
$ActiveProject = active_project();

include('db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){
include('live_queries.php');
include('sql_io.php');
include('forms.php');

$ActiveGoal = active_goal($ActiveProject);

$select_classes_SQL = select_classes_SQL($ActiveGoal['id']);
$retrieved_classes = SQL_query($select_classes_SQL);
$all_library_goals_SQL = select_descriptions_SQL();

?>
<html>
<head>
    <title><?php print $site_name;?>- Goal Browsing</title>
    <link rel="stylesheet" href="default.css" type="text/css"/>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="js.js"></script>
    
</head>
<body>

<?php

if(isset($_GET['message'])){
redirect_msg();
}
?>
<h1><span class="active"><?php print $ActiveProject;?></span>: <?php print $site_name; ?></h1>
<?php include('menu.php');?>
<div id="project_goal" class="bigDiv">
    <h2>STEP 1.2: Browse and Select Library Goals</h2>
<div id="goal_classes">
        <h3>Aggregate Goals contain collapsed list of library goals that might be of interest to the <?php print $ActiveProject;?> project.</span></h3>
        <p>Active Goal: <span class="active"><?php print $ActiveGoal['name'];?></a>
        <p>Browse through the lists, click on the <span class="more_info_btn">&#9654;</span> for more information about the goal, like its description and the sources in which it has been found.
        <p>Consider how any of the below goals relate to the active goal.
        <p>Select those that do, and provide a reason for your selection.
        <form name="project_goal_class_library_goal" action="form_processing.php?type=project_goal_class_library_goal&project=<?php print $ActiveProject;?>&projgoal=<?php print $ActiveGoal['id'];?>" method="post">
        <div id="lib_goals_descriptions_box">
		    <?php
		    print descriptions($all_library_goals_SQL, "");
		    ?>
        </div>
        <div id="lib_goals_list">
		    <?php
        	$class_act = 0;
        		foreach ($retrieved_classes as $i => $values) {
					if ($class_act == 0){
						$class_actt = "act";
						$class_act++;
					}
					else {
						$class_actt = "passive";
						$class_act++;
					}
					$TTselect_library_goals_SQL = select_project_goal_lib_goal_SQL($ActiveGoal['id'], $values[0]);
					$TTretrieved_library_goals = SQL_query($TTselect_library_goals_SQL);
					$TTlibrary_goals_SQL = select_project_goal_lib_goal_SQL2($ActiveGoal['id'], $values[0], $values[1]);
					print "<div class=\"lib_goal_checks\">";				
					print checkbox($values[0], null, $TTlibrary_goals_SQL, "goal", "id", $values[1], $class_actt, $class_act, $ActiveGoal['id']);
					print "</div>";
			 }
        	?>
        </div>
        <br>
        <input type="submit" value="Save Goal Selections!"></div>
        </form>

    </div>
    
</div>
</body>
</html>
<?php
}
