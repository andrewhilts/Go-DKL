<?php

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

?>
