<?php
print "Please wait for your download to begin...";
$csv = "file:///tmp/".$_GET["timestamp"].".csv";
$proj_time = $_GET["timestamp"];
$goal_table = array();

	$row = 1;
	if (($handle = fopen($csv, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
			$num = count($data);
			$row++;
			for ($c=0; $c < $num; $c++) {
				$goal_table[$row][$c]=$data[$c];
			}
		}
		fclose($handle);
}

$project_goals = array();
$goal_classes = array();
$library_goals = array();
$contributions = array();
$actions = array();
$affordances = array();
$design_decisions = array();
$x=0;
foreach($goal_table as $i => $claim){
	$project_goals[$x]=$claim[0];
	$goal_classes[$x]=$claim[1];
	$library_goals[$x]=$claim[2];
	$contributions[$x]=$claim[3];
	$actions[$x]=$claim[4];
	$affordances[$x]="helps";
	$design_decisions[$x]=$claim[5];
	
	$x++;
}
/*
print $project_goals[0];
print $goal_classes[0];
print $library_goals[0];
print $contributions[0];
print $actions[0];
print $affordances[0];
print $design_decisions[0];
*/
$output = "";
$project_goals_U = array_unique($project_goals);
foreach($project_goals_U as $i => $pg){
$pg_lib_goals = array();
		foreach($library_goals as $jdf => $goals){
			if($pg == $project_goals[$jdf]){
				$pg_lib_goals[] = "\n\t\"".$library_goals[$jdf]."\" => + \"".$project_goals[$jdf]."\"";
			}
		}
	$pg_lib_goals_U = array_unique($pg_lib_goals);
	foreach($pg_lib_goals_U as $k => $lib_goals_U){
		$output = $output.$lib_goals_U;
	}
}

/*
$goal_classes_U = array_unique($goal_classes);
foreach($goal_classes_U as $i => $gc){
$gc_lib_goals = array();
		foreach($library_goals as $j => $goals){
			if($gc == $goal_classes[$j]){
				$gc_lib_goals[] = "\n\t\"".$library_goals[$j]."\" => + \"".$goal_classes[$j]."\"";
			}
		}
	$gc_lib_goals_U = array_unique($gc_lib_goals);
	foreach($gc_lib_goals_U as $k => $lib_goals_U){
		$output = $output.$lib_goals_U;
	}
}
*/
$output = $output."\n//Actions - Goals\n";

$lib_goals_U = array_unique($library_goals);
foreach($lib_goals_U as $p => $lg){
$lg_actions = array();
$output = $output."\n//".$lg;
	foreach($actions as $spa => $actoids){
		if($lg == $library_goals[$spa]){
			switch ($contributions[$spa]) {
				case "makes":
					$contrib = "++";
				break;
				case "helps":
					$contrib = "+";
				break;
				case "hurts":
					$contrib = "-";
				break;
				case "breaks":
					$contrib = "--";
				break;
				}
			$lg_actions[] = "\n\t\"".$actoids."\" => ".$contrib." \"".$lg."\"";
		}
	}
		$lg_actions_U = array_unique($lg_actions);
	foreach($lg_actions_U as $l => $actions_U){
		$output = $output.$actions_U;
	}
}

$output = $output."\n//Designs - Actions\n";

$actionsacc_U = array_unique($actions);
foreach($actionsacc_U as $i => $acacac){
$acacac_designs = array();
		foreach($design_decisions as $fse => $goals){
			if($acacac == $actions[$fse]){
				$acacac_designs[] = "\n\t\"".$design_decisions[$fse]."\" => ++ \"".$actions[$fse]."\"";
			}
		}
	$acacac_designs_U = array_unique($acacac_designs);
	foreach($acacac_designs_U as $sdk => $acacac_desdes_U){
		$output = $output.$acacac_desdes_U;
	}
}


$output = $output."\n\"Dummy\"{ & ;";
foreach($project_goals_U as $k => $pgg_U){
		$output = $output."\n\t\"".$pgg_U."\"";
}
foreach($lib_goals_U as $r => $lgg_U){
		$output = $output."\n\t\"".$lgg_U."\"";
}
foreach($actionsacc_U as $ui => $acac_U){
		$output = $output."\n\t\"".$acac_U."\"";
}
$output = $output."\n}";

$newQ7File = $proj_time.".q7";
$fh = fopen($newQ7File, 'w') or die("can't open file");
fwrite($fh, $output);
fclose($fh);
header('Content-disposition: attachment; filename='.$newQ7File);
header('Content-type: text/csv');
readfile($newQ7File);
/*
"Plan comparison be facilitated" { & ;
	"information_management"
	"discussion_support"
	"community_building"
	"deliverable"
}
"Dummy" { | ;
	"relevant information be accessible"
	"efficiently aggregate content"
	"group rule followers"
	"advertise value of standards"
	"collaborative filtering"
	"prioritize content"
	"relevant information be accessible"
	"adhere to standard process"
	}
"efficiently aggregate content" => + "information_management"
	"adhere to standard process" => + "efficiently aggregate content"
		"group rule followers" => + "adhere to standard process"
		"advertise value of standards" => + "adhere to standard process"
"relevant information be accessible" => ++ "information_management"
	"prioritize content" => + "relevant information be accessible"
	"collaborative filtering" => + "prioritize content"
	

*/
