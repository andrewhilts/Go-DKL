<?php
/*if ($_SERVER['HTTP_HOST']==='localhost') {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}*/
  include('db-permissions.php');
  $db_handle = mysql_connect($hostname, $username, $password);
  $db_found = mysql_select_db($database, $db_handle);
if ($db_found){
      include('sql_io.php');
      if(isset($_GET['project'])){
      	$ActiveProject = $_GET['project'];
      }
      else{
      	$ActiveProject = $_POST['project_name'];
      }
      if(isset($_POST['project_goals'])){
      	$ActiveGoal = $_POST['project_goals'];
			$ActiveGoal_name = SQL_query("select goal_name from project_goals where id='$ActiveGoal' LIMIT 1;");
			$ActiveGoal_name = $ActiveGoal_name[0][0];
			$ActiveGoalArray[][0] = $ActiveGoal;
      }
      if(isset($_POST['library_goals'])){
      	$ActiveLibrary = $_POST['library_goals'];
      }
      if(isset($_POST['claim_classes'])){
		   $class_type_array = $_POST['claim_classes'];
		   $Active_class_array = $_POST['claim_classes'];
		   $project_goal_class_types = $class_type_array;
      }
      elseif(isset($_POST['ACTION_goal_classes'])){
      echo "pop";
      $class_type_array = array();
      $class_type_array[] = "";
		}
      if(isset($_POST['claim_goals'])){
		   $claim_goal_array = $_POST['claim_goals'];
		   $Active_claim_goal_array = $_POST['claim_goals'];
		}
		
		if(isset($_POST['ACTION_library_goals'])){
      	$ACTION_library_goals = $_POST['ACTION_library_goals'];
      }
      if(isset($_POST['ACTION_goal_classes'])){
      $ACTION_goal_classes = $_POST['ACTION_goal_classes'];
      }
      if(isset($_POST['order_by'])){
      	$order_by = $_POST['order_by'];
      }
      else{
          $order_by = "project goal";
      }
      if(isset($_POST['order_dir'])){
      	$order_dir = $_POST['order_dir'];
      }
      else {
          $order_dir = "ASC";
      }
            
      $order_by2 = $_POST['order_by2'];
      $order_dir2 = $_POST['order_dir2'];
      if (!$order_dir2) {
          $order_dir2 = "ASC";
      }
      
      if (!$order_by2) {
          $order_by2 = "project goal";
      }
      
      
      foreach ($project_goal_class_types as $i => $value) {
          $project_goal_class_types_values_array[] = "('" . $ActiveGoal . "','" . $project_goal_class_types[$i] . "')";
          $project_goal_class_types_deleting_array[] = "(project_goal_id='" . $ActiveGoal . "' AND goal_class='" . $project_goal_class_types[$i] . "')";
      }
      
      $project_goal_class_types_values = implode(",", $project_goal_class_types_values_array);
      $project_goal_library_goals_values = implode(",", $project_goal_library_goals_values_array);
      
      if ($class_type_array) {
          if ($ACTION_goal_classes == "delete") {
              $project_goal_class_types_deleting = implode(" OR ", $project_goal_class_types_deleting_array);
              $project_goal_class_types_SQL = "DELETE FROM project_goal_classes WHERE $project_goal_class_types_deleting;";
              SQL_delete_query($project_goal_class_types_SQL);
          } 
          elseif ($ACTION_goal_classes == "insert") {  
              $project_goal_class_types_SQL = "INSERT IGNORE INTO project_goal_classes (`project_goal_id`, `goal_class`) VALUES $project_goal_class_types_values;";
              SQL_insert_query($project_goal_class_types_SQL);
          }
          
          $select_classes_SQL = "SELECT goal_class FROM project_goal_classes WHERE project_goal_id='$ActiveGoal';";
          $retrieved_classes = SQL_query($select_classes_SQL);
          $select_library_goals_SQL = "SELECT library_goal_id FROM project_goal_class_library_goal WHERE project_goal_id='$ActiveGoal';";
          $retrieved_library_goals = SQL_query($select_library_goals_SQL);
          
          if ($ActiveLibrary) {
              $pattern = "/Lib_Goal.*ms2side.*/";
              $pattern2 = "/Lib_Goal/";
              foreach ($_POST as $i => $values) {
                  $source = $i;
                  preg_match($pattern, $source, $matches);
                  if (!$matches) {
                      preg_match($pattern2, $source, $matches2);
                      if ($matches2) {
                          $class = str_replace("Lib_Goal_", "", $i);
                          $classes[] = "lib_goals.goal_class='" . $class . "'";
                          //echo $i.": ";
                          foreach ($values as $j => $subvalues) {
                              $lib_goals_insert_array[] = array("class" => $class, "goal" => $subvalues);
                              $lib_goals_insert_values_array[] = "('" . $ActiveGoal . "', '" . $class . "', '" . $subvalues . "')";
                              $project_goal_library_goals_deleting_array[] = "(project_goal_id='" . $ActiveGoal . "' AND goal_class='" . $class . "' AND library_goal_id='" . $subvalues . "')";
                          }
                      }
                  }
              }
              if ($ACTION_library_goals == "delete") {
                  $project_goal_library_goals_deleting = implode(" OR ", $project_goal_library_goals_deleting_array);
                  $project_goal_library_goals_SQL = "DELETE FROM project_goal_class_library_goal WHERE $project_goal_library_goals_deleting;";
                  SQL_delete_query($project_goal_library_goals_SQL);
              } 
              elseif ($ACTION_library_goals == "insert") {    
                  $lib_goal_insert_values = implode(",", $lib_goals_insert_values_array);
                  $project_goal_library_goals_SQL = "INSERT IGNORE INTO project_goal_class_library_goal (project_goal_id, goal_class, library_goal_id) VALUES $lib_goal_insert_values;";
                  SQL_insert_query($project_goal_library_goals_SQL);
              }
              
              $classes_select_values = implode(" OR ", $classes);
              $classes_select_values = "AND (" . $classes_select_values . ")";
              $dd_SQL = "select distinct project_goals.goal_name as 'project goal', lib_goals.goal_class as 'goal class', n1.name as 'Soft Goal', r1.type as 'contribution', n2.name as 'Action', n3.name as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND project_goals.project_name = '$ActiveProject' ORDER BY `$order_by` $order_dir;";
              $date= getdate();
              $timestamp= $ActiveProject."--".$date["year"]."-".$date["mon"]."-".$date["mday"]."_".$date["hours"]."-".$date["minutes"];
              $output_SQL = "select distinct project_goals.goal_name as 'project goal', lib_goals.goal_class as 'goal class', n1.name as 'Soft Goal', r1.type as 'contribution', n2.name as 'Action', n3.name as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND project_goals.project_name = '$ActiveProject' ORDER BY `$order_by` $order_dir INTO OUTFILE '/tmp/$timestamp.csv';";
              $active_dd_SQL = "select distinct project_goals.goal_name as 'project goal', lib_goals.goal_class as 'goal class', n1.name as 'Soft Goal', r1.type as 'contribution', n2.name as 'Action', n3.name as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND lib_goals.project_goal_id = '$ActiveGoal' ORDER BY `$order_by2` $order_dir2;";
          }
      }

?>

<html>
<head>
<title>Scenario Analysis</title>
<link rel="stylesheet" href="jquery.multiselect2side.css" type="text/css"/>
<link rel="stylesheet" href="default.css" type="text/css"/>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="functions.js"></script>
<script type="text/javascript" src="jquery.multiselect2side.js"></script>
<script type="text/javascript" src="js.js"></script>
</head>
<body>
<?php
	include('tables_lists.php');
	$ChosenClassesJoin = "\"" . implode("\", \"", $class_type_array) . "\"";

	if ($class_type_array) {
		 foreach ($class_type_array as $i => $value) {
		     if (!$class_type) {
		         $class_type = $class_type_array[$i];
		     } else {
		         $class_type = $class_type . ", " . $class_type_array[$i];
		     }
		     $class_type_array[$i] = "claim_classes.class_type = '" . $class_type_array[$i] . "'";
		 }
		 $class_types = implode(" OR ", $class_type_array);
	}

	$project_array = SQL_query("SELECT * FROM projects ORDER BY name;");
	$project_goals_SQL = "SELECT `goal_name`, `id` FROM `project_goals` WHERE project_name = '".$ActiveProject."' ORDER BY goal_name;";
	$project_goals = SQL_query($project_goals_SQL);
	$proto_design_name = "testing";
	$library_goals_SQL = "SELECT nodes.name as goal, claim_classes.class_type as class, nodes.id as id FROM `nodes` INNER JOIN relationships ON relationships.parent = nodes.id INNER JOIN claim_classes ON relationships.claim_id = claim_classes.claim_id WHERE nodes.type='Soft Goal' AND ($class_types) GROUP BY class, goal;";
	$library_claim_classes_SQL = "select * from `claim_class_types` group by type";

	include('forms.php');

	if ($project_goals){
		print "<h1><span class=\"active_project\">".$ActiveProject."</span>: Goal Analysis</h1>";
		print "<form name=\"projgoal\" action=\"project_analysis.php#goal_classes\" method=\"post\">";
		print "<input type=\"hidden\" name=\"project_name\" value=\"$ActiveProject\">\n";
		if ($class_type_array) {
		    $projectstyle = "hidden";
		    $javascript = "<input class=\"toggler\" type=reset onclick=\"javascript:toggle('projform');\" value=\"Change Scenario Goal\">";
		}
		else {
		    $projectstyle = "visible";
		    $javascript = "";
		}
		
		print "<div id=\"scenario_goal\" class=\"bigDiv\">";
		
		if ($class_type_array) {
		    print "<h2>&quot;" . $ActiveGoal_name . "&quot;<span style=\"font-size:0.6em; margin:0 0 0 20px; font-style:italic; \">Active Scenario Goal</span></h2>" . $javascript;
		}
		
		print "<div id=\"projform\" class=\"" . $projectstyle . "\">";
		print "<h3>Select Scenario Goal to Analyze</h3>";
		print formlist("project_goals", $ActiveGoalArray, $project_goals_SQL, "goal_name", "id");
		print "<div id=\"goal_classes\"><h3>Classify this goal:<br><span style=\"font-size:0.6em;\">Choose one or more goal types.</span></h3>";
		print formlist("claim_classes", $retrieved_classes, $library_claim_classes_SQL, "type", "type", true);
?>
<br>
<input type="radio" name="ACTION_goal_classes" value="next" checked> No changes to scenario goal classificaiton.<br>
<input type="radio" name="ACTION_goal_classes" value="insert"> Add selected classes to scenario goal classification.<br>
<input type="radio" name="ACTION_goal_classes" value="delete"> Remove selected classes from scenario goal classification.<br>
<input type="submit" value="submit!"></div>
</form></div></div>
<?php
		if (($ActiveGoal) && (!$class_type_array)) {
			 echo "select goal type(s)";
		}
		if ($class_type_array) {
			 if ($ActiveLibrary) {
				  $projectstyle = "hidden";
				  $javascript = "<input class=\"toggler\" type=reset onclick=\"javascript:toggle('library_goals');\" value=\"Change Library Goals\">";
			 }
			 else {
				  $projectstyle = "visible";
				  $javascript = "";
			 }
			 
			 print "<div id=\"goal_classes\" class=\"bigDiv\"><h2>Match with goals from the design library</h2>" . $javascript . "<div id=\"library_goals\" class=\"" . $projectstyle . "\">";
			 print "\n<p>Select any of the below goals that relate to the chosen scenario goal.</span></p>";
			 print "<form name=\"softgoal\" action=\"project_analysis.php#project_summary\" method=\"post\">\n<input type=\"hidden\" name=\"project_name\" value=\"$ActiveProject\"><input type=\"hidden\" name=\"project_goals\" value=\"$ActiveGoal\">\n<input type=\"hidden\" name=\"library_goals\" value=\"$ActiveGoal\"><div style=\"display:none\">";
		?>
		<input type="hidden" name="order_dir" value="<?php print $order_dir; ?>"/>
		<input type="hidden" name="order_by" value="<?php print $order_by; ?>"/>
		<input type="hidden" name="order_dir2" value="<?php print $order_dir2;?>"/>
		<input type="hidden" name="order_by2" value="<?php print $order_by2;?>"/>
		<?php
			 print formlist("claim_classes", $retrieved_classes, $library_claim_classes_SQL, "type", "type", true);
			 print "</div>";
			 //print formlist("claim_goals", $retrieved_library_goals, $library_goals_SQL, "goal", "goal", true, "class");
			 foreach ($retrieved_classes as $i => $values) {
				  echo "<h4>" . $values[0] . "</h4>\n";
				  $TTselect_library_goals_SQL = "SELECT library_goal_id FROM project_goal_class_library_goal INNER JOIN project_goal_classes ON project_goal_class_library_goal.project_goal_id=project_goal_classes.project_goal_id WHERE project_goal_class_library_goal.project_goal_id='$ActiveGoal' AND project_goal_classes.goal_class='$values[0]';";
				  $TTretrieved_library_goals = SQL_query($TTselect_library_goals_SQL);
				  $TTlibrary_goals_SQL = "SELECT nodes.name as goal, claim_classes.class_type as class, nodes.id as id FROM `nodes` INNER JOIN relationships ON relationships.parent = nodes.id INNER JOIN claim_classes ON relationships.claim_id = claim_classes.claim_id WHERE nodes.type='Soft Goal' AND claim_classes.class_type='$values[0]' GROUP BY class, goal;";
				  print formlist("Lib_Goal_" . $values[0], $TTretrieved_library_goals, $TTlibrary_goals_SQL, "goal", "id", true);
			 }
		?>
		  <br>
		  <input type="radio" name="ACTION_library_goals" value="next" checked>Continue to next step with no action.<br>
		  <input type="radio" name="ACTION_library_goals" value="insert"> Add selected goals to scenario<br>
		  <input type="radio" name="ACTION_library_goals" value="delete"> Remove selected goals from scenario<br>
		<?php
			print "<input type=\"submit\" value=\"submit!\"></div></div>";
		}
		if ($ActiveLibrary) {
			 print "<div id=\"project_summary\" class=\"bigDiv\"><h2>Design Alternatives that contribute to scenario goals</h2>\n
		<h3>Active Scenario Goal Contributions</h3>";
			print "<div style=\"margin:-1.5em 20px 1.5em 0; float:right;\"><a href=\"q7_export/q7.php?timestamp=".$timestamp."\"><button class=\"button_link\">Get Q7 file for Goal Model!</button></a></div>";
			 SQL_table_query($active_dd_SQL, "active");
			 print "<h3>All Scenario Goal Contributions</h3>";
			 print "<div style=\"margin:-1.5em 20px 1.5em 0; float:right;\"><a href=\"q7_export/q7.php?timestamp=".$timestamp."\"><button class=\"button_link\">Get Q7 file for Goal Model!</button></a></div>";
			 //print $dd_SQL;
			 SQL_table_query($dd_SQL, "complete");
			 print "<span class=\"hidden\">";
			 SQL_query($output_SQL);
			 print "</span>";
			 print "</div>";
		}
	}
}
else{
	print "nothing found.";
}
?>