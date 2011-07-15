<?php
  include('db-permissions.php');
  $db_handle = mysql_connect($hostname, $username, $password);
  $db_found = mysql_select_db($database, $db_handle);
  if ($db_found) {
      include('sql_io.php');
            if($_GET['project']){
      $ActiveProject = $_GET['project'];
      }
      else{
      $ActiveProject = $_POST['project_name'];
      }
      $ActiveGoal = $_POST['project_goals'];
      $ActiveLibrary = $_POST['library_goals'];
      $ActiveGoalArray[][0] = $ActiveGoal;
      $class_type_array = $_POST['claim_classes'];
      $Active_class_array = $_POST['claim_classes'];
      $claim_goal_array = $_POST['claim_goals'];
      $Active_claim_goal_array = $_POST['claim_goals'];
      $project_goal_class_types = $class_type_array;
      
      $ACTION_library_goals = $_POST['ACTION_library_goals'];
      $ACTION_goal_classes = $_POST['ACTION_goal_classes'];
      
      $order_by = $_POST['order_by'];
      $order_dir = $_POST['order_dir'];
      if (!$order_dir) {
          $order_dir = "ASC";
      }
      
      if (!$order_by) {
          $order_by = "project goal";
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
      /*foreach ($claim_goal_array as $i => $value) {
       $project_goal_library_goals_values_array[] = "('".$ActiveGoal."','".$claim_goal_array[$i]."')";
       $project_goal_library_goals_deleting_array[] = "(project_goal_id='".$ActiveGoal."' AND library_goal='".$claim_goal_array[$i]."')";
       }*/
      $project_goal_library_goals_values = implode(",", $project_goal_library_goals_values_array);
      if ($class_type_array) {
          if ($ACTION_goal_classes == "delete") {
              $project_goal_class_types_deleting = implode(" OR ", $project_goal_class_types_deleting_array);
              $project_goal_class_types_SQL = "DELETE FROM project_goal_classes WHERE $project_goal_class_types_deleting;";
              SQL_delete_query($project_goal_class_types_SQL);
          } else {
              
              $project_goal_class_types_SQL = "INSERT IGNORE INTO project_goal_classes (`project_goal_id`, `goal_class`) VALUES $project_goal_class_types_values;";
              SQL_insert_query($project_goal_class_types_SQL);
          }
          $select_classes_SQL = "SELECT goal_class FROM project_goal_classes WHERE project_goal_id='$ActiveGoal';";
          $retrieved_classes = SQL_query($select_classes_SQL);
          $select_library_goals_SQL = "SELECT library_goal FROM project_goal_class_library_goal WHERE project_goal_id='$ActiveGoal';";
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
                              $project_goal_library_goals_deleting_array[] = "(project_goal_id='" . $ActiveGoal . "' AND goal_class='" . $class . "' AND library_goal='" . $subvalues . "')";
                              //echo $subvalues.", ";
                          }
                          //echo "; ";
                      }
                  }
              }
              if ($ACTION_library_goals == "delete") {
                  $project_goal_library_goals_deleting = implode(" OR ", $project_goal_library_goals_deleting_array);
                  $project_goal_library_goals_SQL = "DELETE FROM project_goal_class_library_goal WHERE $project_goal_library_goals_deleting;";
                  SQL_delete_query($project_goal_library_goals_SQL);
              } else {
                  
                  $lib_goal_insert_values = implode(",", $lib_goals_insert_values_array);
                  $project_goal_library_goals_SQL = "INSERT IGNORE INTO project_goal_class_library_goal (project_goal_id, goal_class, library_goal) VALUES $lib_goal_insert_values;";
                  SQL_insert_query($project_goal_library_goals_SQL);
              }
              $classes_select_values = implode(" OR ", $classes);
              $classes_select_values = "AND (" . $classes_select_values . ")";
              $dd_SQL = "select lib_goals.project_goal_id as 'project goal', lib_goals.goal_class as 'goal class', r1.parent as 'Soft Goal', r1.type as 'contribution', r1.child as 'Action', r2.child as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.name INNER JOIN nodes as n2 on r1.child = n2.name INNER JOIN nodes as n3 ON r2.child = n3.name WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' ORDER BY `$order_by` $order_dir;";
              $active_dd_SQL = "select lib_goals.project_goal_id as 'project goal', lib_goals.goal_class as 'goal class', r1.parent as 'Soft Goal', r1.type as 'contribution', r1.child as 'Action', r2.child as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.name INNER JOIN nodes as n2 on r1.child = n2.name INNER JOIN nodes as n3 ON r2.child = n3.name WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND lib_goals.project_goal_id = '$ActiveGoal' ORDER BY `$order_by2` $order_dir2;";
              //$retrieved_active_dd = SQL_query($active_dd_SQL);
              //$retrieved_all_dd = SQL_query($dd_SQL);
          }
      }
?>

<html>
<head>
<title>Scenario Analysis</title>
<link rel="stylesheet" href="jquery.multiselect2side.css" typ="text/css"/>
<style rel="stylesheet" type="text/css">
body {width:960px; margin:5px auto; font-family:Arial, Sans Serif}
.hidden {display:none;}
.visible {display:block; padding:10px 5%;}
h1 {text-align:center;}
h1 {background-color:#303F7D; color:#FFF; padding:10px; -moz-border-radius:10px; -webkit-border-radius:10px; border-radius:10px}
h1, h2, h3 {margin:0}
optgroup {color:#fff; background-color:#303F7D; font-family:monospace; font-style:normal; font-size:1.15em; margin:0 0 10px 0}
option {background-color:white; color:black; font-family:sans serif; font-size:12pt; padding:3px; border-top:1px solid #ddd}
option.alternate {color:black; background-color:#F4FDE4;}
option.selected {background-color:#323B5E; color:#FFF;}
.bigDiv h2 {padding:5px 10px; font-size:1.4em; background-color:#323B5E; color:#fff; border-radius-topleft:5px; -moz-border-radius-topleft:5px; -webkit-border-radius-topleft:5px; border-radius-topright:5px; -moz-border-radius-topright:5px; -webkit-border-radius-topright:5px; border-radius:5px;}
h4 {margin:10px 0 3px 0;}
.bigDiv {-o-border-radius:7px; -moz-border-radius:7px; -webkit-border-radius:7px; border-radius:7px; border:1px solid #323B5E; margin:10px 0;background-color: #F9FEF0; padding:0 0 10px 0}
.bigDiv h3 {margin:10px 10px 0 10px;}
.bigDiv .visible h3 {margin:10px 0px 0 0px;}
.bigDiv h4 {margin:15px 0 5px 0;  font-size:1.25em;}
.bigDiv table {background-color:#fff; margin:10px; font-size:0.8em;}
input.toggler {width:90%; margin:10px 5% 0 5%;}
td {padding:5px; border:1px solid #000;}
th {padding:0px; background-color:#323B5E; color:#fff; border:1px solid #000;}
th:hover {background-color:#303F7D;}
th a {color:#F4FDE4; display:block; cursor:pointer; padding:4px 0 0 0; width:100%; height:30px; vertical-align:middle;}
.orderUP {background-color:#303F7D;}
.orderDOWN {background-color:#303F7D;}
.helps {background-color:#A0F06C;}
.althelps {background-color:#ABF27D;}
.hurts {background-color:#FF8963;}
.althurts {background-color:#FF9371;}
</style>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="functions.js"></script>
<!---<script type="text/javascript" src="jquery.column.min.js"></script>!-->
<script type="text/javascript">
$(document).ready(function(){
  var results = $("#results");
  var orderUP = $(".orderUP").parent();
  orderUP.addClass("orderUP");
  var orderDOWN = $(".orderDOWN").parent();
  orderDOWN.addClass("orderDOWN");
  $('#results tr:nth-child(odd)').addClass('alternate');
  var rows = results.children("tbody").children("tr");
  var cells = rows.children("td");
  
});      
</script>
<script type="text/javascript">
  $(document).ready(function(){
  
  $("option:even").addClass('alternate');
  $("select:not(:first)").multiselect2side();
});
</script>
<script type="text/javascript" src="jquery.multiselect2side.js"></script>
<script type="text/javascript">
window.onload=function(){
selectmenu=document.getElementById("project_goals");
claimmenu=document.getElementById("claim_classes");
libgoaldiv=document.getElementById("library_goals");
goalclassdiv=document.getElementById("goal_classes");
//selectmenu.onchange=function(){goalclassdiv.className="visible";claimmenu.selectedIndex=-1};
};

function toggle(id) {
toggle_element = document.getElementById(id).className;
if (toggle_element=="hidden"){
document.getElementById(id).className='visible';
}
else{
document.getElementById(id).className='hidden';
}
}

</script>
</head>
<body>

<?php
      function table_print($array, $style)
      {
          echo "<table class=" . $style . " cellpadding=2 cellspacing=0 border=1>";
          foreach ($array as $value => $row) {
              echo "<tr>";
              foreach ($row as $value => $data) {
                  echo "<td>" . $data . "</td>";
              }
              echo "</tr>";
          }
          echo "</table>";
      }
      function list_print($array, $list_type, $special)
      {
          echo "<" . $list_type . ">";
          foreach ($array as $value => $row) {
              echo "<li>";
              if ($special) {
                  foreach ($row as $value => $data) {
                      print $data;
                  }
              } elseif (is_array($row)) {
                  list_print($row, $list_type);
              } else {
                  echo $row;
              }
              echo "</li>";
          }
          echo "</" . $list_type . ">";
      }
      
      function tr($array, $element, $class)
      {
          if ($element == "td") {
              $tr = "<tr class=\"$class\"><" . $element . ">" . implode("</" . $element . "><" . $element . ">", $array) . "</" . $element . "></tr>";
          } else {
              $tr = "<tr class=\"$class\"><" . $element . ">" . implode("</" . $element . "><" . $element . ">", $array) . "</" . $element . "></tr>";
          }
          return $tr;
      }
      
      function SQL_table_query($statement, $id)
      {
          if ($result = mysql_query($statement)) {
              echo "<table id=\"results\" width=98% cellpadding=0 cellspacing=0 border=0>";
              $numfields = mysql_num_fields($result);
              $headerData[] = "#";
              //global $class_type;
              global $order_by;
              global $order_by2;
              global $order_dir;
              global $order_dir2;
              $ordery_dir = $order_dir;
              $ordery_by = $order_by;
              for ($i = 0; $i < $numfields; $i++) {
                  // Header
                  $field_name = mysql_field_name($result, $i);
                  if ($id == "active") {
                      $ordery_dir = $order_dir2;
                      $ordery_by = $order_by2;
                  }
                  if (($field_name == $ordery_by) && ($ordery_dir == "ASC")) {
                      $ordery_dir = "DESC";
                  } elseif (($field_name == $ordery_by) && ($ordery_dir == "DESC")) {
                      $ordery_dir = "ASC";
                  }
                  if (($field_name == $ordery_by) && ($ordery_dir == "ASC")) {
                      $headerData[] = "<a class=\"orderUP\" onclick=\"javascript:query_linky('" . $id . "', '" . $field_name . "', '" . $ordery_dir . "');\">" . mysql_field_name($result, $i) . " ^</a>";
                  } elseif (($field_name == $ordery_by) && ($ordery_dir == "DESC")) {
                      $headerData[] = "<a class=\"orderDOWN\" onclick=\"javascript:query_linky('" . $id . "', '" . $field_name . "', '" . $ordery_dir . "');\">" . mysql_field_name($result, $i) . " v</a>";
                  } else {
                      //global $class_type;
                      $headerData[] = "<a onclick=\"javascript:query_linky('" . $id . "', '" . $field_name . "', '" . $ordery_dir . "');\">" . mysql_field_name($result, $i) . " v</a>";
                  }
              }
              echo tr($headerData, "th", "");
              $j = 1;
              while ($db_field = mysql_fetch_assoc($result)) {
                  unset($rowData);
                  $rowData[] = $j;
                  $j++;
                  foreach ($db_field as $i => $value) {
                      $rowData[] = $db_field[$i];
                      if ($i == "contribution") {
                          if ($value == "helps") {
                              $class = $alt . "helps";
                          } elseif ($value == "hurts") {
                              $class = $alt . "hurts";
                          }
                          if ($alt == "alt") {
                              $alt = "";
                          } else {
                              $alt = "alt";
                          }
                      }
                  }
                  echo tr($rowData, "td", $class);
              }
              echo "</table>";
          } else {
              die('Error: ' . mysql_error());
          }
      }
      
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
      $library_goals_SQL = "SELECT nodes.name as goal, claim_classes.class_type as class FROM `nodes` INNER JOIN relationships ON relationships.parent = nodes.name INNER JOIN claim_classes ON relationships.claim_id = claim_classes.claim_id WHERE nodes.type='Soft Goal' AND ($class_types) GROUP BY class, goal;";
      $library_claim_classes_SQL = "select * from `claim_class_types` group by type";
      /*$helpedGoalsSQL = "SELECT DISTINCT relationships.parent as `Soft Goal` FROM relationships inner join claim_classes ON relationships.claim_id = claim_classes.claim_id INNER JOIN relationships as r2 ON relationships.child = r2.parent INNER JOIN claims ON claims.claim_id = relationships.claim_id INNER JOIN proto_design_nodes ON proto_design_nodes.node = r2.child WHERE (relationships.type = 'helps') AND proto_design_nodes.proto_design = '$proto_design_name' ORDER BY `Soft Goal` ASC;";
       $proto_design_helped_goals = SQL_query($helpedGoalsSQL);
       $hurtGoalsSQL = "SELECT DISTINCT relationships.parent as `Soft Goal` FROM relationships inner join claim_classes ON relationships.claim_id = claim_classes.claim_id INNER JOIN relationships as r2 ON relationships.child = r2.parent INNER JOIN claims ON claims.claim_id = relationships.claim_id INNER JOIN proto_design_nodes ON proto_design_nodes.node = r2.child WHERE (relationships.type = 'hurts') AND proto_design_nodes.proto_design = '$proto_design_name' ORDER BY `Soft Goal` ASC;";
       $proto_design_hurt_goals = SQL_query($hurtGoalsSQL);
       $chosenGoalsSQL = "SELECT DISTINCT node from proto_design_nodes INNER JOIN nodes on proto_design_nodes.node = nodes.name WHERE proto_design='$proto_design_name' AND nodes.type = 'Soft Goal';";
       $proto_design_chosen_goals = SQL_query($chosenGoalsSQL);
       $chosenDesignsSQL = "SELECT DISTINCT node from proto_design_nodes INNER JOIN nodes on proto_design_nodes.node = nodes.name WHERE proto_design='$proto_design_name' AND nodes.type = 'Design Decision  ';";
       $proto_design_chosen_designs = SQL_query($chosenDesignsSQL);
       */
  }
  
  include('forms.php');
  
  if ($project_goals) {
      //table_print($project_goals);
      //table_print($proto_design_goals);
      print "
  <h1>Scenario Goal Analysis</h1>";
?>
  <form name="projgoal" action="project2.php#goal_classes" method="post">
  <?php
      if ($class_type_array) {
          $projectstyle = "hidden";
          $javascript = "<input class=\"toggler\" type=reset onclick=\"javascript:toggle('projform');\" value=\"Change Scenario Goal\">";
      } else {
          $projectstyle = "visible";
          $javascript = "";
      }
      print "<div id=\"scenario_goal\" class=\"bigDiv\">";
      if ($class_type_array) {
          print "<h2>&quot;" . $ActiveGoal . "&quot;<span style=\"font-size:0.6em; margin:0 0 0 20px; font-style:italic; \">Active Scenario Goal</span></h2>" . $javascript;
      }
      print "<div id=\"projform\" class=\"" . $projectstyle . "\">";
      print "<h3>Select Scenario Goal to Analyze</h3>";
      print formlist("project_goals", $ActiveGoalArray, $project_goals_SQL, "goal_name", "id");
      print "<div id=\"goal_classes\"><h3>Classify this goal:<br><span style=\"font-size:0.6em;\">Choose one or more goal types.</span></h3>";
      print formlist("claim_classes", $retrieved_classes, $library_claim_classes_SQL, "type", "type", true);
?>
    <br>
  <input type="radio" name="ACTION_goal_classes" value="insert" checked> Add selected classes to scenario goal classification.<br>
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
          } else {
              $projectstyle = "visible";
              $javascript = "";
          }
          
          print "<div id=\"goal_classes\" class=\"bigDiv\"><h2>Match with goals from the design library</h2>" . $javascript . "<div id=\"library_goals\" class=\"" . $projectstyle . "\">";
          print "\n<p>Select any of the below goals that relate to the chosen scenario goal.</span></p>";
          print "<form name=\"softgoal\" action=\"project2.php#project_summary\" method=\"post\">\n
    <input type=\"hidden\" name=\"project_goals\" value=\"$ActiveGoal\">\n<input type=\"hidden\" name=\"library_goals\" value=\"$ActiveGoal\"><div style=\"display:none\">";
?>
        <input type="hidden" name="order_dir" value="<?php
          print $order_dir;
?>"/><input type="hidden" name="order_by" value="<?php
          print $order_by;
?>"/>
    <input type="hidden" name="order_dir2" value="<?php
          print $order_dir2;
?>"/><input type="hidden" name="order_by2" value="<?php
          print $order_by2;
?>"/>
    <?php
          print formlist("claim_classes", $retrieved_classes, $library_claim_classes_SQL, "type", "type", true);
          print "</div>";
          //print formlist("claim_goals", $retrieved_library_goals, $library_goals_SQL, "goal", "goal", true, "class");
          foreach ($retrieved_classes as $i => $values) {
              echo "<h4>" . $values[0] . "</h4>\n";
              $TTselect_library_goals_SQL = "SELECT library_goal FROM project_goal_class_library_goal INNER JOIN project_goal_classes ON project_goal_class_library_goal.project_goal_id=project_goal_classes.project_goal WHERE project_goal_class_library_goal.project_goal_id='$ActiveGoal' AND project_goal_classes.goal_class='$values[0]';";
              $TTretrieved_library_goals = SQL_query($TTselect_library_goals_SQL);
              $TTlibrary_goals_SQL = "SELECT nodes.name as goal, claim_classes.class_type as class FROM `nodes` INNER JOIN relationships ON relationships.parent = nodes.name INNER JOIN claim_classes ON relationships.claim_id = claim_classes.claim_id WHERE nodes.type='Soft Goal' AND claim_classes.class_type='$values[0]' GROUP BY class, goal;";
              print formlist("Lib_Goal_" . $values[0], $TTretrieved_library_goals, $TTlibrary_goals_SQL, "goal", "goal", true);
          }
?>
  <br>
  <input type="radio" name="ACTION_library_goals" value="insert" checked> Add selected goals to scenario<br>
  <input type="radio" name="ACTION_library_goals" value="delete"> Remove selected goals from scenario<br>
  <?php
          print "<input type=\"submit\" value=\"submit!\"></div></div>";
      }
      if ($ActiveLibrary) {
          print "<div id=\"project_summary\" class=\"bigDiv\"><h2>Design Alternatives that contribute to scenario goals</h2>\n
  <h3>Active Scenario Goal Contributions</h3>";
          SQL_table_query($active_dd_SQL, "active");
          print "<h3>All Scenario Goal Contributions</h3>";
          //print $dd_SQL;
          SQL_table_query($dd_SQL, "complete");
          print "</div>";
      }
      //print formlist("library_goals", "goals", $library_goals_SQL, "name", true);
      
      /*print "</div><div>\n
       <h2>Tentative Design</h2>
       <h3>Goals helped by Design</h3>";
       list_print($proto_design_helped_goals, "ol", "special");
       print "<h3>Goals hurt by Design</h3>";
       list_print($proto_design_hurt_goals, "ol", "special");
       print "</div><div>\n";
       print "<h3>Goals selected to be acheived by Design</h3>";
       list_print($proto_design_chosen_goals, "ol", "special");
       print "<h3>Tentative Design Choices</h3>";
       list_print($proto_design_chosen_designs, "ol", "special");
       */
?>  
<?php
      /*DEBUG*/
       foreach($_POST as $i => $values) {
       print "<p>".$i.": ".$values;
       foreach($values as $j => $subvalues) {
       print "<br>&nbsp;&nbsp;&nbsp;".$j.": ".$subvalues;
       }
       }
       print "<p>".$project_goal_class_types_SQL;
       print "<p>".$project_goal_library_goals_SQL;
       
  } else {
      
      print "nothing found.";
  }
?>
