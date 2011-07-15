<?php

//*****************

//Step(s) of associating Actions, Design Alternatives?


//print_r($_POST);
/*
if ($_SERVER['HTTP_HOST']==='localhost') {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}
*/
  include('db-permissions.php');
  include('live_queries.php');
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
      if(isset($_POST['corry'])){
      	$CorrelationsDealtWith = "yeaaaaaaah";
      }
      if(isset($_POST['claim_classes'])){
		   $class_type_array = $_POST['claim_classes'];
		   $Active_class_array = $_POST['claim_classes'];
		   $project_goal_class_types = $class_type_array;
      }
      elseif(isset($_POST['ACTION_goal_classes'])){
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
          $project_goal_class_types_deleting_array[] = "(project_goal_id='" . $ActiveGoal . "' AND goal_class_id='" . $project_goal_class_types[$i] . "')";
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
              $project_goal_class_types_SQL = insert_project_goal_classes_SQL($project_goal_class_types_values);
              SQL_insert_query($project_goal_class_types_SQL);
          }

          $select_classes_SQL = select_classes_SQL($ActiveGoal);
          $retrieved_classes = SQL_query($select_classes_SQL);
          $select_library_goals_SQL = select_library_goals_SQL($ActiveGoal);
          $retrieved_library_goals = SQL_query($select_library_goals_SQL);
          
          if ($ActiveLibrary) {

	include('active_library.php');
              
                            $date= getdate();
      $timestamp= $ActiveProject."--".$date["year"]."-".$date["mon"]."-".$date["mday"]."_".$date["hours"]."-".$date["minutes"];
              
              $classes_select_values = implode(" OR ", $classes);
              $classes_select_values = "AND (" . $classes_select_values . ")";
              $dd_SQL = select_design_alternatives_SQL($ActiveProject, $order_by, $order_dir);
              $active_dd_SQL = select_active_design_alternatives_SQL ($ActiveGoal, $order_by2, $order_dir2);
              $output_active_SQL = export_active_design_alternatives_SQL($ActiveGoal, $order_by2, $order_dir2, null, $timestamp);
              $output_active_special_SQL = export_no_corr_designs_corr_relations($ActiveGoal, $order_by, $order_dir, $timestamp, "goal");
              $output_active_corr_SQL = export_active_design_alternatives_SQL($ActiveGoal, $order_by2, $order_dir2, true, $timestamp);
              $output_SQL = design_alternatives_no_corr_SQL($ActiveProject, $order_by, $order_dir, $timestamp,"export","project");
              $output_special_SQL = export_no_corr_designs_corr_relations($ActiveProject, $order_by, $order_dir, $timestamp, "project", true);
              $output_corr_SQL = export_design_alternatives_SQL($ActiveProject, $order_by2, $order_dir2, $timestamp);
              
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
  <link rel="stylesheet" href="treeview.css" type="text/css" />
  <script type="text/javascript" src="treeview.js"></script>
  <script>
  $(document).ready(function(){
    $("#alternatives_analysis_container").treeview({
        animated: "fast",
		persist: "location",
		collapsed: true,
		unique: true
	});
    
  });
  </script>

</head>
<body>
<?php
	include('tables_lists.php');
	$ChosenClassesJoin = "\"" . implode("\", \"", $class_type_array) . "\"";

	$select_projects_SQL = 		select_projects_SQL();
	$project_array = 			SQL_query($select_projects_SQL);
	$project_goals_SQL = 		select_project_goals_SQL($ActiveProject);
	$project_goals = 			SQL_query($project_goals_SQL);
	$proto_design_name = 		"testing";
	$library_goals_SQL = 		select_class_library_goals_SQL ($class_type_array);
	$library_claim_classes_SQL = 	select_library_claim_classes_SQL();

	include('forms.php');

	if ($project_goals){
		print "<h1><span class=\"active_project\">".$ActiveProject."</span>: Goal Analysis</h1>";
		print "<form name=\"projgoal\" action=\"project_analysis.php\" method=\"post\">";
		print "<input type=\"hidden\" name=\"project_name\" value=\"$ActiveProject\">\n";
		if ($class_type_array) {
		    $projectstyle = "hidden";
		    $javascript = "<input class=\"toggler\" type=reset onclick=\"javascript:toggler('projform', 'page_section');\" value=\"Change Scenario Goal\">";
		}
		else {
		    $projectstyle = "visible";
		    $javascript = "";
		}
		
		print "<div id=\"scenario_goal\" class=\"bigDiv\">";
		
		if ($class_type_array) {
		    print "<h2>STEP 1: Select &amp; Classify Scenario Goal<span style=\"font-size:0.6em; margin:0 0 0 20px; font-style:italic; \">Current: &quot;" . $ActiveGoal_name . "&quot;</span></h2>" . $javascript;
		}
		
		print "<div id=\"projform\" class=\"" . $projectstyle . "\">";
		print "<h3>Select Scenario Goal to Analyze</h3>";
		//print_r($retrieved_classes);
		//print $library_claim_classes_SQL;
		print formlist("project_goals", $ActiveGoalArray, $project_goals_SQL, "goal_name", "id", null, null);
		print "<div id=\"goal_classes\"><h3>Classify this goal:<br><span style=\"font-size:0.6em;\">Choose one or more goal types.</span></h3>";
		print formlist("claim_classes", $retrieved_classes, $library_claim_classes_SQL, "type", "id", true);
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
				  $javascript = "<input class=\"toggler\" type=reset onclick=\"javascript:toggler('library_goals', 'page_section');\" value=\"Associate Library Goals\">";
			 }
			 else {
				  $projectstyle = "visible";
				  $javascript = "";
			 }
			 
			 print "<div id=\"goal_classes\" class=\"bigDiv\"><h2>STEP 2: Match with goals from the design library</h2>" . $javascript . "<div id=\"library_goals\" class=\"" . $projectstyle . "\">";
			 print "\n<p>Consider how any of the below goals relate to the chosen scenario goal:<br> <span class=\"important\">&quot;". $ActiveGoal_name."&quot;</span><p>Select those that do, and provide a reason for your selection.";
			 print "<form name=\"softgoal\" action=\"project_analysis.php\" method=\"post\">\n<input type=\"hidden\" name=\"project_name\" value=\"$ActiveProject\"><input type=\"hidden\" name=\"project_goals\" value=\"$ActiveGoal\">\n<input type=\"hidden\" name=\"library_goals\" value=\"$ActiveGoal\"><div style=\"display:none\">";
		?>
		<input type="hidden" name="order_dir" value="<?php print $order_dir; ?>"/>
		<input type="hidden" name="order_by" value="<?php print $order_by; ?>"/>
		<input type="hidden" name="order_dir2" value="<?php print $order_dir2;?>"/>
		<input type="hidden" name="order_by2" value="<?php print $order_by2;?>"/>
		<?php
			 print formlist("claim_classes", $retrieved_classes, $library_claim_classes_SQL, "type", "id", true);
			 print "</div>";
			 //print formlist("claim_goals", $retrieved_library_goals, $library_goals_SQL, "goal", "goal", true, "class");
			 
			  $all_library_goals_SQL = select_descriptions_SQL();
			 print "<div id=\"lib_goals_descriptions_box\">";
			 print descriptions($all_library_goals_SQL, "");
			 print "</div>";
			 $class_act = 0;
			 print "<div id=\"lib_goals_list\">";
			 foreach ($retrieved_classes as $i => $values) {
			 if ($class_act == 0){
			 $class_actt = "act";
			 $class_act++;
			 }
			 else {
			 $class_actt = "passive";
			 $class_act++;
			 }
				  $TTselect_library_goals_SQL = select_project_goal_lib_goal_SQL($ActiveGoal, $values[0]);
				  $TTretrieved_library_goals = SQL_query($TTselect_library_goals_SQL);
				  $TTlibrary_goals_SQL = select_project_goal_lib_goal_SQL2($ActiveGoal, $values[0], $values[1]);
				  //print formlist("Lib_Goal_" . $values[0], $TTretrieved_library_goals, $TTlibrary_goals_SQL, "goal", "id", true);
				//print $TTlibrary_goals_SQL;
				print "<div class=\"lib_goal_checks\">";				
				print checkbox($values[0], null, $TTlibrary_goals_SQL, "goal", "id", $values[1], $class_actt, $class_act, $ActiveGoal);
				print "</div>";
			 }
			
		?>
		  </div><br style="clear:both;">
		<?php
			print "<input type=\"submit\" value=\"Associate Checked Goals!\"></form></div></div>";
		}
		if ($ActiveLibrary) {
		 print "<div id=\"project_correlations\" class=\"bigDiv\"><h2>STEP 3: Analyze Correlations</h2>\n";
		 if($CorrelationsDealtWith) {
		 $projectstyle = "hidden";
		 $javascript = "<input class=\"toggler\" type=reset onclick=\"javascript:toggler('correlations_list', 'page_section');\" value=\"Analyze Goal Correlations\">";
		 }
		 else {
		    $projectstyle = "visible";
		    $javascript = "";
		}

			 print "<span class=\"hidden\">";
			 SQL_query($output_active_corr_SQL);
			 SQL_query($output_active_SQL);
			 SQL_query($output_active_special_SQL);
			 
			 print "</span>";
			 
		print $javascript . "<div id=\"correlations_list\" class=\"" . $projectstyle . "\">";
				 print "<div style=\"margin:0.5em 20px 0.5em 0; float:right;\"><a href=\"q7_export/q7.php?timestamp=goal_".$ActiveGoal."_".$timestamp."\"><button class=\"button_link\">Basic Q7</button></a> <a href=\"q7_export/q7.php?timestamp=goal_".$ActiveGoal."_".$timestamp."_special\"><button class=\"button_link\">Mid Q7</button></a> <a href=\"q7_export/q7.php?timestamp=goal_".$ActiveGoal."_correlations_".$timestamp."\"><button class=\"button_link\">Full Q7</button></a></div>";
				 //print $output_active_special_SQL."!!";
		 $project_goal_correlations_SQL = select_project_goal_correlations_SQL($ActiveGoal, $ActiveProject);
		 $project_goal_correlations = SQL_query($project_goal_correlations_SQL);
		 //print "<h3>".$ActiveGoal_name."</h3>";
		 //table_print($project_goal_correlations, "correlations");
		print $project_goal_correlations_SQL;
		 //print_r($project_goal_correlations);
		 foreach ($project_goal_correlations as $i => $correlation) {
		 		if ((($correlation[0] == "helps") && ($correlation[1] == "helps"))||(($correlation[0] == "hurts") && ($correlation[1] == "hurts"))||(($correlation[0] == "affords") && ($correlation[1] == "helps"))) {
		 		$helped_cor_goals[] = $correlation[3]."_".$correlation[2];
		 		}
		 		elseif ((($correlation[0] == "hurts") && ($correlation[1] == "helps"))||(($correlation[0] == "helps") && ($correlation[1] == "hurts"))||(($correlation[0] == "affords") && ($correlation[1] == "hurts"))) {
		 		$hurt_cor_goals[] = $correlation[3]."_".$correlation[2];
		 		}
		 		$id = $correlation[3];
		 		$cor_metadata[$id]["name"]= $correlation[2];
		 		$cor_metadata[$id]["desc"]= $correlation[4];
		 		$cor_metadata[$id][$i]["r3"]= $correlation[0];
		 		$cor_metadata[$id][$i]["r4"]= $correlation[1];
		 		$cor_metadata[$id][$i]["r3_c"]= $correlation[5];
		 		$cor_metadata[$id][$i]["r4_c"]= $correlation[6];
		 		$cor_metadata[$id][$i]["n1"]= $correlation[7];
		 		$cor_metadata[$id][$i]["n2"]= $correlation[8];
		 		$cor_metadata[$id][$i]["n3"]= $correlation[9];
		 		$cor_metadata[$id][$i]["n4"]= $correlation[10];
		 		$cor_metadata[$id][$i]["r4_claim"]= $correlation[11];
		 		$cor_metadata[$id][$i]["pg"]= $correlation[12];
		 		$cor_metadata[$id][$i]["pgc"]= $correlation[13];
		 		$suggested_designs[] = $correlation[9];
		 }

		 $suggested_designs = array_unique($suggested_designs);
		 $suggested_designs = implode("</span><span>", $suggested_designs);
		 print "<p>The following are recommended Design Alternatives that help satisfy project goals that also have unaccounted-for contributions.";
		 print "<p class=\"spanned\"><span>".$suggested_designs."</span></p>";
		 print "<p class=\"spanned\"><br>The below list are goals from the design library that have not been included in your project. However, the recommended design alternatives for your project have been found to contribute to these goals. Consider whether they are relevant to include them for goal analysis.
		 <h3>Select any goals you would like to include in your model.</h3>
		 ";
		 
		 print "<div id=\"correlations_description_box\">";
		 print cor_descriptions($cor_metadata);
		 print "</div>";
		 	
			$helped_cor_goals=array_unique($helped_cor_goals);
		 	$hurt_cor_goals=array_unique($hurt_cor_goals);
		 	$all_cor_goals = array_merge($helped_cor_goals, $hurt_cor_goals);
		 	$conflict_cor_goals = array_unique(array_diff_assoc($all_cor_goals,array_unique($all_cor_goals)));
		 	if($conflict_cor_goals){
		 	$hurt_cor_goals=array_diff($hurt_cor_goals,$conflict_cor_goals);
		 	$helped_cor_goals=array_diff($helped_cor_goals,$conflict_cor_goals);
		 	}
		 	
		 	function corr_goal_print ($contrib, $goal_name_id){
		 		global $ActiveProject;
		 		$correlation_id_pattern = '/(\d+)_(.*)/';
		 		$goal = preg_replace($correlation_id_pattern, '\2', $goal_name_id);
		 		$id = preg_replace($correlation_id_pattern, '\1', $goal_name_id);
		 		$front_matter = "<input type=\"checkbox\" name=\"correlation_add_".$id."\" value=\"".$id."\" onclick=\"document.getElementById('correlation_dropdowns_".$id."').className = this.checked ? 'visible' : 'hidden'\"><label for=\"correlation_add_".$id."\">".$goal."</label>";
		 		$node_claim_classes_SQL = node_claim_classes_SQL($id);
		 		//print $node_claim_classes_SQL;
		 		$fieldname = "correlation_add_class_".$id;
		 		$fieldname2 = "correlation_add_pg_".$id;
		 		$fakearray[] = "asdfadsf";
		 		$dropdown = "<label for = \"".$fieldname2."\">Associate with Goal Class</label>".formlist($fieldname, null, $node_claim_classes_SQL, "type", "id", null, null);
		 		$project_goals_SQL = select_project_goals_SQL($ActiveProject);
		 		$project_dropdown = "<label for = \"".$fieldname2."\">Associate with Project Goal</label>".formlist($fieldname2, null, $project_goals_SQL, "goal_name", "id", null, null);
		 		$dropdowns = "<div class=\"hidden\" id=\"correlation_dropdowns_".$id."\">".$project_dropdown.$dropdown."<label for=\"correlation_add_rationale_".$id."\">Selected because:</label><input type=\"text\" name=\"correlation_add_rationale_".$id."\" value=\" \"/></div>";
		 		$end_matter = "<span class=\"passive\" id=\"correlation_desc_btn_".$id."\" onclick=\"toggle('correlation_desc_".$id."', 'descriptions','correlation_desc_btn_".$id."'); toggle2('correlation_desc_btn_".$id."','desc_button');\" title=\"Click for more information.\">&#9654;</span>".$dropdowns; 	
		 		print "<div id=\"correlation_goal_".$id."\" class=\"correlation_".$contrib."\">".$front_matter.$end_matter."</div>";
		 	}
		 	print "<form name=\"correlations\" action=\"project_analysis.php\" method=\"post\"><input type=\"hidden\" name=\"corry\" value=\"corry\"/><input type=\"hidden\" name=\"project_name\" value=\"$ActiveProject\"><input type=\"hidden\" name=\"project_goals\" value=\"$ActiveGoal\">\n<input type=\"hidden\" name=\"library_goals\" value=\"$ActiveGoal\"><div style=\"display:none\">";
		 	print formlist("claim_classes", $retrieved_classes, $library_claim_classes_SQL, "type", "id", true);
			 print "</div>";
		?>
		<input type="hidden" name="order_dir" value="<?php print $order_dir; ?>"/>
		<input type="hidden" name="order_by" value="<?php print $order_by; ?>"/>
		<input type="hidden" name="order_dir2" value="<?php print $order_dir2;?>"/>
		<input type="hidden" name="order_by2" value="<?php print $order_by2;?>"/>
		<div id="correlation_list">
		<?php
			print "<h3>Positive Correlations</h3>";
		 	foreach ($helped_cor_goals as $i => $goal_name_id){
		 		corr_goal_print("helps", $goal_name_id);
		 	}
		 	print "<h3>Conflicting Correlations</h3>";
		 	foreach ($conflict_cor_goals as $i => $goal_name_id){
		 		corr_goal_print("conflict", $goal_name_id);
		 	}
		 	print "<h3>Negative Correlations</h3>";
		 	foreach ($hurt_cor_goals as $i => $goal_name_id){
		 		corr_goal_print("hurts", $goal_name_id);
		 	}
		 	print "</div><input type =\"submit\" value=\"Associate Checked Correlations\"</form></div></div>";
		}
		if ($CorrelationsDealtWith) {
		?>
		<script type="text/javascript" src="eval.js"></script>

		<?php
		
		print "<div id=\"alternatives_identification\" class=\"bigDiv\"><h2>STEP 4: Analyze Design Alternatives</h2>\n
		<h3>Active Scenario Goal Design Alternatives</h3>
		<div id=\"alternatives_status\">notes</div>
		";
				
		//$slice_SQL = active_goal_slice ($ActiveProject,null, "select", null);
		//$slice_SQL = select_design_alternatives_SQL($ActiveProject, "goal class`, `Soft Goal`, `Action`, `Design Decision", $order_dir);
		$slice_SQL = export_no_corr_designs_corr_relations($ActiveProject, "pg`, `lg`, `a`, `d", $order_dir, null, "project", null);
		
		$slices = SQL_assoc_query($slice_SQL);
        //print $slice_SQL;
		$id=0;
		$contrib_style = "";
    	function plus_minus_contrib($c){
    	global $contrib_style;
    	switch($c) {
    	case "helps":
    	    $contrib_style = "helps";
    	    return "+";
    	    break;
    	case "hurts":
    	    $contrib_style = "hurts";
    	    return "-";
    	    break;   
    	case "affords":
    	    $contrib_style = "helps";
    	    return "++";
    	    break; 
    	}
    	}
    	function contrib_selector($contrib, $id){
		 		$o1 = "<option>AND</option>\n";
		        $o2 = "<option>OR</option>\n";
		        $o3 = "<option>++</option>\n";
		        $o4 = "<option>+</option>\n";
		        $o5 = "<option>?</option>\n";
		        $o6 = "<option>-</option>\n";
		        $o7 = "<option>--</option>\n";
		        $o8 = "<option>N/A</option>\n";
		 
		     switch ($contrib) {
		            case "AND":
		                $o1 = "<option selected class=\"selected\">AND</option>\n";
		                break;
		            case "OR":
		                $o2 = "<option selected class=\"selected\">OR</option>\n";
		                break;
		            case "++":
		                $o3 = "<option selected class=\"selected\">++</option>\n";
		                break;
		            case "+":
		                $o4 = "<option selected class=\"selected\">+</option>\n";
		                break;
		            case "?":
		                $o5 = "<option selected class=\"selected\">?</option>\n";
		                break;
		            case "-":
		                $o6 = "<option selected class=\"selected\">-</option>\n";
		                break;
		            case "--":
		                $o7 = "<option selected class=\"selected\">--</option>\n";
		                break;
	                case "N/A":
		                $o8 = "<option selected class=\"selected\">N/A</option>\n";
		                break;
		     }
		     
		     return "
		        <select name=\"".$id."\" class=\"alternatives_id\">
		        ".$o1.$o2.$o3.$o4.$o5.$o6.$o7.$o8."
	            </select>
		     ";
		 }
		 
		 function checky($id){
		    return "<input type=\"checkbox\" style=\"float:right;\" checked name=\"".$id."\" id=\"".$id."\"/>";
		 }
		print "<div style=\"width:500px\">\n<ul id=\"alternatives_analysis_container\">";
		 
    	$last_pg = "foo";
    	$last_lg = "foo";
    	$last_a = "foo";
    	$last_d = "foo";
    	
    	while($slice = mysql_fetch_assoc($slices)) {
    	    $h = $i-1;
    	    $a_id = $slice["a_id"];
       		$d_id = $slice["d_id"];
	        $lg_id = $slice["lg_id"];
    	    if(($last_pg=="foo")||($slice["pg"]!==$last_pg)){
    	        if($last_pg!=="foo"){
    	        print "\n\t\t\t\t\t</ul>\n\t\t\t\t</li>\n\t\t\t</ul>\n\t\t</li>\n\t</ul>\n</li>";
    	        }
    	        $last_lg = "foo";
    	        $last_a = "foo";
    	        $last_d = "foo";
    	        print "\n<li><span>".$slice["pg"]."</span>\n\t<ul>"; 
    	    }
    	    if(($last_lg=="foo")||($slice["lg"]!==$last_lg)){
    	        if($last_lg!=="foo"){
    	        print "\n\t\t\t\t\t</ul>\n\t\t\t\t</li>\n\t\t\t</ul>\n\t\t</li>";
    	        }
    	        $last_a = "foo";
    	        $last_d = "foo";
    	        
    	        $moreinfo = "<div class=\"passive\" style=\"float:right;\" id=\"correlation_desc_btn_".$id."\" onclick=\"toggle('correlation_desc_".$id."', 'descriptions','correlation_desc_btn_".$id."'); toggle2('correlation_desc_btn_".$id."','desc_button');\" title=\"Click for more information.\">&#9654;</div>";
    	        
		        $contrib_selector = contrib_selector("++", $lg_id);
		            $checkbox = checky("check_".$lg_id);
    	        
    	        print "\n\t\t<li class=\"alternatives_L1\"><span><img src=\"img/softgoal.gif\" width=20 alt=\"soft goal\"/>".$slice["lg"]."</span>".$moreinfo.$checkbox.$contrib_selector."\n\t\t\t<ul>";
    	    }
    	    if(($last_a=="foo")||($slice["a"]!==$last_a)){
    	        if($last_a!=="foo"){
    	        print "\n\t\t\t\t\t</ul>\n\t\t\t\t</li>";
    	        }
    	        $last_d = "foo";
    	        
    	        $contrib = plus_minus_contrib($slice["c1"]);
           		
           		$contrib_selector = contrib_selector($contrib, $a_id."_".$lg_id);
           		$checkbox = checky("check_".$a_id);
           		$moreinfo = "<div class=\"passive\" id=\"correlation_desc_btn_".$id."\" onclick=\"toggle('correlation_desc_".$id."', 'descriptions','correlation_desc_btn_".$id."'); toggle2('correlation_desc_btn_".$id."','desc_button');\" title=\"Click for more information.\">&#9654;</div>";
    	      
    	        print "\n\t\t\t\t<li class=\"alternatives_L2\"><span>".$slice["a"]."</span>".$moreinfo.$checkbox.$contrib_selector."\n\t\t\t\t\t<ul>";
    	       
    	        
    	    }
    	    if(($last_d=="foo")||($slice["d"]!==$last_d)){
    	        
    	        $moreinfo = "<div class=\"passive\" id=\"correlation_desc_btn_".$id."\" onclick=\"toggle('correlation_desc_".$id."', 'descriptions','correlation_desc_btn_".$id."'); toggle2('correlation_desc_btn_".$id."','desc_button');\" title=\"Click for more information.\">&#9654;</div>";
    	        
           		$checkbox = checky("check_".$d_id);
    	        $contrib = plus_minus_contrib($slice["c2"]);
           		$contrib_selector = contrib_selector($contrib, $d_id."_".$a_id);
    	        print "\n\t\t\t\t\t\t<li class=\"alternatives_L3\"><span>".$slice["d"]."</span>".$moreinfo.$checkbox.$contrib_selector."</li>";
    	    }
    	    $last_pg=$slice["pg"];
    	    $last_lg = $slice["lg"];
    	    $last_a = $slice["a"];
    	    $last_d = $slice["d"];
    	}
    	print "\n\t\t\t\t\t</ul>\n\t\t\t\t</li>\n\t\t\t</ul>\n\t\t</li>\n\t</ul>\n</li>\n</ul>\n";
?>
<?php
		}
		if($asdf){
		      	include('correlations.php');
			 print "<div id=\"project_summary\" class=\"bigDiv\"><h2>STEP 5: Design Alternatives that contribute to scenario goals</h2>\n
		<h3>Active Scenario Goal Contributions</h3>";;
			print "<div style=\"margin:-1.5em 20px 0 0; text-align:right; float:right;\"><a href=\"q7_export/q7.php?timestamp=".$timestamp."_correlations\"><button class=\"button_link\">Full Q7</button></a> <a href=\"q7_export/q7.php?timestamp=".$timestamp."_special\"><button class=\"button_link\">Mid Q7</button></a> <a href=\"q7_export/q7.php?timestamp=".$timestamp."_nocorrelations\"><button class=\"button_link\">Basic Q7</button></a></div>";
			 SQL_table_query($active_dd_SQL, "active");
			 print "<h3>All Scenario Goal Contributions</h3>";
			 print "<div style=\"margin:-1.5em 20px 1.5em 0; float:right;\"><a href=\"q7_export/q7.php?timestamp=".$timestamp."\"><button class=\"button_link\">Get Q7 file for Goal Model!</button></a></div>";
			 print $output_SQL;
			 print "<p>".$output_special_SQL;
			 print "<p>".$output_corr_SQL;
			 SQL_table_query($dd_SQL, "complete");
			 print "<span class=\"hidden\">";
			 SQL_query($output_corr_SQL);
			 SQL_query($output_SQL);
			 SQL_query($output_special_SQL);
			 print "</span>";
			 print "</div>";
		}
	}
	print_r($goal_actions);
}
else{
	print "nothing found.";
}
?>
</body>

</html>
