<?php
function formlist ($fieldname, $value, $SQL, $label_field, $id_field, $multiple, $groupingfield) {
	$CatCat = mysql_query($SQL);
	$CurrentoCategory= "nothing yet";
	$carl = array();
	while ($db_field = mysql_fetch_assoc($CatCat)) {
		if($groupingfield){
			if(($db_field[$groupingfield] !== $lastfield)||!($lastfield)){
				$lastfield=$db_field[$groupingfield];
				$optgroup="<optgroup label=\"".$db_field[$groupingfield]."\">";
				$endoptgroup="</optgroup>";
			}
			else {
				$optgroup="";
				$endoptgroup="";
			}
		}
		else {
		$optgroupfield="";
		$endoptgroup="";
		$optgroup="";
		}
		if($value){
			foreach ($value as $i => $values) {	
				if ($value[$i][0] == $db_field[$id_field]) {
					$carl[] = $optgroup."<option value=\"".$db_field[$id_field]."\" class=\"selected\" selected=\"selected\" style=\"font-weight:bold;\">".$db_field[$label_field]."</option>\n\t".$endoptgroup;
					$done = true;
					}
				}
			if (!$done) {
			$carl[] = $optgroup."<option value=\"".$db_field[$id_field]."\">".$db_field[$label_field]."</option>\n\t";
			}
			unset($done);
		}	
		else {
		$carl[] = $optgroup."<option value=\"".$db_field[$id_field]."\">".$db_field[$label_field]."</option>\n\t";
			}
	}	
	$cats = implode($carl);
	if($multiple){
		$multiple="multiple=multiple";
		$array="[]";
		}
	else {
		$multiple="";
		$array="";
	}
		return "\n<select ".$multiple." id=\"".$fieldname."\" name=\"".$fieldname.$array."\" id=\"".$fieldname."\">\n\t".$cats."\n</select>\n";
}

function checkbox ($fieldname, $value, $SQL, $label_field, $id_field, $header, $open, $digit, $project_goal){
//echo $SQL;
echo "<h4 class=\"".$open."\" id=\"".$digit."_checklist_h4\" onclick=\"toggle3('".$digit."_checklist_div','checklistdiv'); toggle4('".$digit."_checklist_h4','h4_button');document.getElementById(last).className='hidden';\">".$header."</h4>";
	$CatCat = mysql_query($SQL);
	$CurrentoCategory= "nothing yet";
	$alt =1;
	while ($db_field = mysql_fetch_assoc($CatCat)) {
		if($alt == 0){
			$class = "class=\"alternate\"";
			$alt = 1;
		}
		else {
			$class = "";
			$alt = 0;
		}
		if (!$db_field['rationale']){
		$rationale = " ";
		}
		else{
		$rationale = $db_field['rationale'];
		}
	$front = "<div ".$class.">";
	$end = "<span class=\"passive\" id=\"desc_btn_".$digit.$db_field[$id_field]."\" onclick=\"toggle('desc_".$db_field[$id_field]."', 'descriptions', 'desc_btn_".$digit.$db_field[$id_field]."'); toggle2('desc_btn_".$digit.$db_field[$id_field]."','desc_button');\">&#9654;</span><br/><div class=\"rationale\">selected (or not) because:<br/><input type=\"text\" size=40 name=\"".$fieldname."_rationale_".$project_goal."_".$db_field[$id_field]."\" value=\"".$rationale."\"/></div></div>";
		if($db_field['checked']==1){	
					$check_array[] = $front."<input type=\"checkbox\" name=\"".$fieldname."_proj_goal_".$project_goal."_lib_goal_".$db_field[$id_field]."\" value=\"".$db_field[$id_field]."\" checked><label for=\"".$fieldname."_proj_goal_".$project_goal."_lib_goal_".$db_field[$id_field]."\"><b>".$db_field[$label_field]."</b></label>".$end;
		}
		else {
		$check_array[] = $front."<input type=\"checkbox\" name=\"".$fieldname."_proj_goal_".$project_goal."_lib_goal_".$db_field[$id_field]."\" value=\"".$db_field[$id_field]."\"><label for=\"".$db_field[$id_field]."\">".$db_field[$label_field]."</label>".$end;
		}
	}
	if($open == "act"){
	$classy = "visible";
	}
	else{
	$classy="hidden";
	}
	return "<div id=\"".$digit."_checklist_div\" class=\"".$classy."\">".implode($check_array)."</div>";
}

function descriptions($SQL, $header){
	$CatCat = mysql_query($SQL);
	$CurrentoCategory= "nothing yet";
	while ($db_field = mysql_fetch_assoc($CatCat)) {
		$array[] = 		
			"<div id=\"desc_".$header.$db_field['id']."\" class=\"hidden\">
				<h4>".$db_field['goal']."</h4>
				<p>".$db_field['description']."</p>
				<p>Found in:</p>
				<p><a href=\"library/publications.php?pub=".$db_field['publication']."\" target=\"_blank\">".$db_field['publication']."</a>
				<p>Claim #".$db_field['source_claim']."
				<p>".$db_field['claim_type']."
			</div>";
	}
	return implode($array);
}

function cor_descriptions($raw_array){
	foreach ($raw_array as $i => $values) {
				unset($sub_values);
		foreach ($values as $j => $sub){
			if(is_int($j)){
			//$sub_values[] = "<p>The selected library goal, &quot;".$sub['n1']."&quot; can be helped by the action, &quot;".$sub['n2']."&quot;, afforded by the design alternative, &quot;".$sub['n3']."&quot;.
			//<p>&quot;".$sub['n3']."&quot;, in turn, also affords the action &quot;".$sub['n3']."&quot;, which helps ".$values['name']."&quot;";
			if((($sub['r3'] == "helps") && ($sub['r4'] == "helps"))||(($sub['r3'] == "hurts") && ($sub['r4'] == "hurts"))||(($sub['r3'] == "affords") && ($sub['r4'] == "helps"))){
				$contribution = "helps";
			}
			elseif((($sub['r3'] == "hurts") && ($sub['r4'] == "helps"))||(($sub['r3'] == "helps") && ($sub['r4'] == "hurts"))||(($sub['r3'] == "affords") && ($sub['r4'] == "hurts"))) {
				$contribution = "hurts";
			}
			$claim_link_pattern = '/(c_)(.*)/';
			if(preg_match($claim_link_pattern, $sub['r4_claim'])){
				$pub = "correlation";
				$claim = " (".preg_replace($claim_link_pattern, '\2', $sub['r4_claim']).")";
			}
			else{
				$claim_link_pattern = '/(.*)(_\d+)/';
			 	$claim = preg_replace($claim_link_pattern, '\2', $sub['r4_claim']);
			 	$pub = preg_replace($claim_link_pattern, '\1', $sub['r4_claim']);
		 	}
		 	$link = "<a href=\"library/publications.php?pub=".$pub."\" target=\"_blank\">".$pub."</a>";
			$claim_link = $link.$claim;
			$sub_values[]= "<tr class=\"$contribution\"><td class=\"important\">".$sub['pg']."</td><td>".$sub['pgc']."</td><td class=\"important\">".$sub['n1']."</td><td>".$sub['n2']."</td><td class=\"important\">".$sub['n3']."</td><td>".$sub['r3']."</td><td>".$sub['n4']."</td><td>".$sub['r4']."</td><td class=\"important\">".$values['name']."</td><td>".$claim_link."</td></tr>";
			}
		}
		$correlations = implode($sub_values);
		$array[] = 		
			"<div id=\"correlation_desc_".$i."\" class=\"hidden\">
				<h4>".$values['name']."</h4>
				<p>".$values['desc']."</p>
				<p>Correlated via:</p>
				<table border=1 cellspacing=0><tr>
					<th>Project Goal</th>
					<th>Goal Class</th>
					<th>Library Goal</th>
					<th>Action</th>
					<th>Design Alternative</th>
					<th>+ / -</th>
					<th>Action</th>
					<th>+ / -</th>
					<th>Correlated Goal</th>
					<th>Claim</th>
					</tr>".$correlations."</table>
			</div>";
	}
	return implode($array);
}

function corr_goal_print ($contrib, $goal_name_id){
	global $ActiveProject;
	$correlation_id_pattern = '/(\d+)_(.*)/';
	$goal = preg_replace($correlation_id_pattern, '\2', $goal_name_id);
	$id = preg_replace($correlation_id_pattern, '\1', $goal_name_id);
	$front_matter = "<input type=\"checkbox\" class=\"\" name=\"correlation_add_".$id."\" value=\"".$id."\"><label for=\"correlation_add_".$id."\">".$goal."</label>";
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
?>
