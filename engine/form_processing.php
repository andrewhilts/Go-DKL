<?php
include('../menu/shared.php');
$ActiveProject = active_project();
include('../admin/db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){
include('../engine/live_queries.php');
include('../admin/sql_io.php');
include('../html_templates/forms.php');

if($_GET['type']=="association"){
    $ActiveGoal = active_goal($ActiveProject);
    $ActiveClasses = active_classes();
    if(isset($_POST['ACTION_goal_classes'])){
        $ACTION_goal_classes = $_POST['ACTION_goal_classes'];
    }
    foreach ($ActiveClasses as $i => $value) {
          $project_goal_class_types_values[] = "('" . $ActiveGoal['id'] . "','" . $ActiveClasses[$i] . "')";
          $project_goal_class_types_deleting_array[] = "(project_goal_id='" . $ActiveGoal['id'] . "' AND goal_class_id='" . $ActiveClasses[$i] . "')";
      }
      $project_goal_class_types_values = implode(",", $project_goal_class_types_values);
      //print $project_goal_class_types_values;
      
      if ($ACTION_goal_classes == "delete") {
          $project_goal_class_types_deleting = implode(" OR ", $project_goal_class_types_deleting_array);
          $project_goal_class_types_SQL = "DELETE FROM project_goal_classes WHERE $project_goal_class_types_deleting;";
          SQL_delete_query($project_goal_class_types_SQL);
          redirect("../goal_association.php?project=".$ActiveProject."&projgoal=".$ActiveGoal['id']."&message=associations_deleted");
          //print $project_goal_class_types_SQL;
      } 
      elseif ($ACTION_goal_classes == "insert") {  
          $project_goal_class_types_SQL = insert_project_goal_classes_SQL($project_goal_class_types_values);
          SQL_insert_query($project_goal_class_types_SQL);
          redirect("../goal_association.php?project=".$ActiveProject."&projgoal=".$ActiveGoal['id']."&message=associations_inserted");
          //print $project_goal_class_types_SQL;
      }
      else{
      redirect("../goal_association.php?project=".$ActiveProject."&projgoal=".$ActiveGoal['id']."&message=associations_no_changes");
      }
}
else if($_GET['type']=="project_goal_class_library_goal"){    
	$ActiveGoal = active_goal($ActiveProject);
	$x = 0;
	foreach($_POST as $i => $values){
	$postman[$x]['key'] = $i;
	$postman[$x]['values'] = $values;
	$x++;
	}
	$project_id_pattern = '/(.*)_proj_goal_(\d+)_lib_goal_(\d+)/';
	$rationale_pattern = '/(.*)_rationale_(\d+)_(\d+)/';
	$project_lib_assoc = array();
	$z=0;
	//print_r($postman);
	foreach($postman as $i => $values){
		$next=$i+1;
		$last=$i-1;
		$subject = $values['key'];
		$last_subject = $postman[$last]['key'];
		$next_subject = $postman[$next]['key'];
		if(preg_match($project_id_pattern, $subject)){
			$project_lib_assoc[$i]['goal_class'] = preg_replace($project_id_pattern, '\1', $subject);
			$project_lib_assoc[$z]['project_id'] = preg_replace($project_id_pattern, '\2', $subject);
			$project_lib_assoc[$z]['lib_goal_id'] = preg_replace($project_id_pattern, '\3', $subject);
			if(preg_match($rationale_pattern, $next_subject)){
				$project_lib_assoc[$z]['rationale'] = mysql_real_escape_string ($postman[$next]['values']);
			}
			else{
			$project_lib_assoc[$z]['rationale'] = "";
			}
			$project_lib_assoc[$z]['checked'] = 1;
		}
		elseif((preg_match($rationale_pattern, $subject))&&!(preg_match($project_id_pattern, $last_subject))&&($values['values']!=="")){
			$project_lib_assoc[$i]['goal_class'] = preg_replace($rationale_pattern, '\1', $subject);
			$project_lib_assoc[$z]['project_id'] = preg_replace($rationale_pattern, '\2', $subject);
			$project_lib_assoc[$z]['lib_goal_id'] = preg_replace($rationale_pattern, '\3', $subject);
			$project_lib_assoc[$z]['rationale'] = mysql_real_escape_string($postman[$i]['values']);
			$project_lib_assoc[$z]['checked'] = 0;
		}
		$z++;
	}
	//print_r($project_lib_assoc);
		foreach($project_lib_assoc as $i => $values){
		$goal_class = $project_lib_assoc[$i]['goal_class'];
		$project_goal = $project_lib_assoc[$i]['project_id'];
		$library_goal = $project_lib_assoc[$i]['lib_goal_id'];
		$rationale = $project_lib_assoc[$i]['rationale'];
		$checked = $project_lib_assoc[$i]['checked'];

		$pgclg_SQL = "insert into project_goal_class_library_goal (goal_class, project_goal_id, library_goal_id, rationale, checked) VALUES ('$goal_class','$project_goal','$library_goal','$rationale','$checked')
		ON DUPLICATE KEY UPDATE rationale='$rationale', checked='$checked';";

		//print $pgclg_SQL."<p>";
		SQL_query($pgclg_SQL);
		//print $project_lib_assoc[$i]['goal_class'].",".$project_lib_assoc[$i]['project_id'].",".$project_lib_assoc[$i]['lib_goal_id'].",".$project_lib_assoc[$i]['rationale'].",".$project_lib_assoc[$i]['checked']."<br>";
		}
	redirect("../goal_browse.php?project=".$ActiveProject."&projgoal=".$ActiveGoal['id']."&message=pgclgsaved");
}
else if($_GET['type']=="correlation_add"){
	$x = 0;
	foreach($_POST as $i => $values){
	$postman[$x]['key'] = $i;
	$postman[$x]['values'] = $values;
	$x++;
	}
	$correlation_id_pattern = '/correlation_add_(\d+)/';
	$class_pattern = '/correlation_add_class_(\d+)/';
	$pg_pattern = '/correlation_add_pg_(\d+)/';
	$rationale_pattern = '/correlation_add_rationale_(\d+)/';
	$correlation_assoc = array();
	$z=0;
	//print_r($postman);
	foreach($postman as $i => $values){
		$next=$i+1;
		$nextnext=$i+2;
		$nextnextnext=$i+3;
		$last=$i-1;
		$subject = $values['key'];
		$last_subject = $postman[$last]['key'];
		$next_subject = $postman[$next]['key'];
		$nextnext_subject = $postman[$nextnext]['key'];
		$nextnextnext_subject = $postman[$nextnextnext]['key'];
		if(preg_match($correlation_id_pattern, $subject)){
			$correlation_assoc[$i]['lib_goal_id'] = preg_replace($correlation_id_pattern, '\1', $subject);;
			if(preg_match($pg_pattern, $next_subject)){
				$correlation_assoc[$z]['pg'] = $postman[$next]['values'];
			}
			if(preg_match($class_pattern, $nextnext_subject)){
				$correlation_assoc[$z]['goal_class'] = $postman[$nextnext]['values'];
			}
			if(preg_match($rationale_pattern, $nextnextnext_subject)){
				$correlation_assoc[$z]['rationale'] = mysql_real_escape_string($postman[$nextnextnext]['values']);
			}
		}
		$z++;
	}
	//print_r($correlation_assoc);
	foreach($correlation_assoc as $i => $values){
		$goal_class = $correlation_assoc[$i]['goal_class'];
		$project_goal = $correlation_assoc[$i]['pg'];
		$library_goal = $correlation_assoc[$i]['lib_goal_id'];
		$rationale = $correlation_assoc[$i]['rationale'];
		$checked = "1";

		$pgclg_SQL = "insert into project_goal_class_library_goal (goal_class, project_goal_id, library_goal_id, rationale, checked) VALUES ('$goal_class','$project_goal','$library_goal','$rationale','$checked')
		ON DUPLICATE KEY UPDATE rationale='$rationale', checked='$checked';";

		//print $pgclg_SQL."<p>";
		SQL_query($pgclg_SQL);
	//print $correlation_assoc[$i]['goal_class'].",".$correlation_assoc[$i]['project_id'].",".$correlation_assoc[$i]['lib_goal_id'].",".$correlation_assoc[$i]['rationale'].",".$correlation_assoc[$i]['checked']."<br>";
		redirect("../goal_correlations.php?project=".$ActiveProject."&message=corrs_saved");
	}
}
else if($_GET['type']=="projmod_add"){
	$ProjModName = projmodname($ActiveProject);
	$projmod_SQL = "INSERT INTO projmods (projmod_name, project_name) VALUES ('$ProjModName','$ActiveProject');";
	SQL_query($projmod_SQL);
	//print $projmod_SQL;
	$projmod_id = mysql_insert_id();
	
	$slice_SQL = export_no_corr_designs_corr_relations($ActiveProject, "pg`, `lg`, `a`, `d", "DESC", null, "project", null);
		
	$slices = SQL_assoc_query($slice_SQL);
	while($slice = mysql_fetch_assoc($slices)) {
        $pg_id = $slice["pg_id"];
        $gc_id = $slice["gc_id"];
        $lg_id = $slice["lg_id"];
        $c1 =  $slice["c1"];
        $a_id = $slice["a_id"];
        $c2 = $slice["c2"];
   		$d_id = $slice["d_id"];
   		$r1_cid = $slice["r1_cid"];
   		$r2_cid = $slice["r2_cid"];
   		$pcg_SQL = "INSERT IGNORE INTO projmod_pcg (pg, gc, lg, projmod_id) VALUES ('".$pg_id."','".$gc_id."','".$lg_id."','".$projmod_id."');";
   		$projmod_rel1_SQL = "INSERT INTO projmod_rels (parent, contrib, child, claim_lib, projmod_id, contrib_lib) VALUES ('".$lg_id."','".$c1."','".$a_id."','".$r1_cid."','".$projmod_id."','".$c1."') ON DUPLICATE KEY UPDATE 
contrib_lib = 
	(CASE 
		WHEN contrib_lib='".$c1."' THEN '".$c1."'
		WHEN contrib_lib='makes' AND 'helps'='".$c1."' THEN 'helps'
		WHEN contrib_lib='helps' AND 'makes'='".$c1."' THEN 'helps'
		WHEN contrib_lib='hurts' AND 'breaks'='".$c1."' THEN 'hurts'
		WHEN contrib_lib='breaks' AND 'hurts'='".$c1."' THEN 'hurts'
		WHEN contrib_lib='affords' AND 'helps'='".$c1."' THEN 'helps'
		WHEN contrib_lib='helps' AND 'affords'='".$c1."' THEN 'helps'
		ELSE '?' 
	END),
contrib = 
	(CASE 
		WHEN contrib='".$c1."' THEN '".$c1."'
		WHEN contrib='makes' AND 'helps'='".$c1."' THEN 'helps'
		WHEN contrib='helps' AND 'makes'='".$c1."' THEN 'helps'
		WHEN contrib='hurts' AND 'breaks'='".$c1."' THEN 'hurts'
		WHEN contrib='breaks' AND 'hurts'='".$c1."' THEN 'hurts'
		WHEN contrib='affords' AND 'helps'='".$c1."' THEN 'helps'
		WHEN contrib='helps' AND 'affords'='".$c1."' THEN 'helps'
		ELSE '?' 
	END)
	;";
   		$projmod_rel2_SQL = "INSERT INTO projmod_rels (parent, contrib, child, claim_lib, projmod_id, contrib_lib) VALUES ('".$a_id."','".$c2."','".$d_id."','".$r2_cid."','".$projmod_id."','".$c2."') ON DUPLICATE KEY UPDATE 
contrib_lib = 
	(CASE 
		WHEN contrib_lib='".$c2."' THEN '".$c2."'
		WHEN contrib_lib='makes' AND 'helps'='".$c2."' THEN 'helps'
		WHEN contrib_lib='helps' AND 'makes'='".$c2."' THEN 'helps'
		WHEN contrib_lib='hurts' AND 'breaks'='".$c2."' THEN 'hurts'
		WHEN contrib_lib='breaks' AND 'hurts'='".$c2."' THEN 'hurts'
		WHEN contrib_lib='affords' AND 'helps'='".$c2."' THEN 'helps'
		WHEN contrib_lib='helps' AND 'affords'='".$c2."' THEN 'helps'
		ELSE '?' 
	END),
contrib = 
	(CASE 
		WHEN contrib='".$c2."' THEN '".$c2."'
		WHEN contrib='makes' AND 'helps'='".$c2."' THEN 'helps'
		WHEN contrib='helps' AND 'makes'='".$c2."' THEN 'helps'
		WHEN contrib='hurts' AND 'breaks'='".$c2."' THEN 'hurts'
		WHEN contrib='breaks' AND 'hurts'='".$c2."' THEN 'hurts'
		WHEN contrib='affords' AND 'helps'='".$c2."' THEN 'helps'
		WHEN contrib='helps' AND 'affords'='".$c2."' THEN 'helps'
		ELSE '?' 
	END);";
   		$projmod_lg_SQL = "INSERT IGNORE INTO projmod_nodes (id, projmod_id, checked) VALUES ('".$lg_id."','".$projmod_id."','1');";
		$projmod_a_SQL = "INSERT IGNORE INTO projmod_nodes (id, projmod_id, checked) VALUES ('".$a_id."','".$projmod_id."','1');";
		$projmod_d_SQL = "INSERT IGNORE INTO projmod_nodes (id, projmod_id, checked) VALUES ('".$d_id."','".$projmod_id."','1');";
   		SQL_query($pcg_SQL);
   		SQL_query($projmod_rel1_SQL);
   		SQL_query($projmod_rel2_SQL);
   		SQL_query($projmod_lg_SQL);
   		SQL_query($projmod_a_SQL);
   		SQL_query($projmod_d_SQL);
   		//print $projmod_rel1_SQL;
   		//print $projmod_rel2_SQL;
    }
	redirect("../project_model.php?project=".$ActiveProject."&projmod=".$projmod_id."&message=projmodcreated");    
	    
}
else if($_GET['type']=="projmod_config"){
	//configure project model
	$ActiveProjMod = active_projmod($ActiveProject);
	//print_r($_POST);
		$x = 0;
	foreach($_POST as $i => $values){
	$postman[$x]['key'] = $i;
	$postman[$x]['values'] = $values;
	$x++;
	}
	$node_check_pattern = '/check_(\d+)_\d+/';
	$relationship_contrib_pattern = '/(\d+)_(\d+)/';
	$node_check_values = array();
	$relationship_contrib_values = array();
	$z=0;
	//print_r($postman);
	foreach($postman as $i => $values){
		$subject = $values['key'];
		if(preg_match($node_check_pattern, $subject)){
			$node_check_values[$z]['node'] = preg_replace($node_check_pattern, '\1', $subject);
			$node_check_values[$z]['checked'] = $values['values'];
		}
		if(preg_match($relationship_contrib_pattern, $subject)){
			$relationship_contrib_values[$z]['parent'] = preg_replace($relationship_contrib_pattern, '\2', $subject);
			$relationship_contrib_values[$z]['child'] = preg_replace($relationship_contrib_pattern, '\1', $subject);
			$relationship_contrib_values[$z]['contrib_new'] = $values['values'];
		}
		$z++;
	}
	//print_r($node_check_values);
	//print "<p>";
	//print_r($relationship_contrib_values);
	foreach($node_check_values as $i => $node_check_value){
		if($node_check_value['checked']=="1"){
			$checked=1;
		}
		else{
			$checked=0;
		}
		$node = $node_check_value['node'];
		$node_check_value_SQL = "UPDATE projmod_nodes SET checked='$checked' WHERE id='$node' AND projmod_id='$ActiveProjMod';";
		//print "<br>".$node_check_value_SQL;
		SQL_query($node_check_value_SQL);
	}
	foreach($relationship_contrib_values as $i => $relationship_contrib_value){
		$value = $relationship_contrib_value['contrib_new'];
		$parent = $relationship_contrib_value['parent'];
		$child = $relationship_contrib_value['child'];
		$relationship_contrib_value_SQL = "UPDATE projmod_rels SET contrib='$value' WHERE parent='$parent' AND child='$child' AND projmod_id='$ActiveProjMod';";
		if($contrib_lib !== $value){
			//print "<br>".$relationship_contrib_value_SQL;
			SQL_query($relationship_contrib_value_SQL);
		}
	}
	redirect("../project_model.php?project=".$ActiveProject."&projmod=".$ActiveProjMod."&message=projmodconfigured"); 
}

else if($_GET['type']=="projmod_goal_slice"){
	$ActiveProjMod = active_projmod($ActiveProject);
	$date= getdate();
	//create Q7 filename based on project
	$filename= $ActiveProject."--GOAL".$active_projmod_goal."--".$date["year"]."-".$date["mon"]."-".$date["mday"]."_".$date["hours"]."-".$date["minutes"];
	$active_projmod_goal = $_GET['projmod_goal_id'];
	$goal_slice_SQL= active_projmod_goal_slice ($ActiveProjMod, $active_projmod_goal, "export", $filename);
	//Export goal slice into csv file
	SQL_query($goal_slice_SQL);
	//redirect to Q7 population script
	redirect("q7_export/q7.php?filename=".$filename);
}
else if($_GET['type']=="projmod_complete_q7"){
	$ActiveProjMod = active_projmod($ActiveProject);
	$date= getdate();
	$filename= $ActiveProject."--FULL--".$date["year"]."-".$date["mon"]."-".$date["mday"]."_".$date["hours"]."-".$date["minutes"];
	$model_SQL= active_projmod_complete($ActiveProjMod, "pg`, `lg`, `a`, `d", "DESC", $filename);
	//print $model_SQL;
	SQL_query($model_SQL);
	redirect("q7_export/q7.php?filename=".$filename);
}
else if($_GET['type']=="new_project"){
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
   $new_project_SQL = "INSERT INTO projects (name, description) VALUES ('$project_name','$project_description');";
   SQL_insert_query($new_project_SQL);
   $new_project_goals_SQL = "INSERT INTO project_goals (project_name, goal_name) VALUES $project_goals_values;";
   SQL_insert_query($new_project_goals_SQL);
   redirect("../index.php?message=newproject");
}
else if($_GET['type']=="foobar"){
}
else if($_GET['type']=="foobar"){
}
}
?>
