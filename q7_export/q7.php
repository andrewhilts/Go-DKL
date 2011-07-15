<?php
/*
This script loops through an array of retrieved records, see the query in ../form_processing.php that creates a csv of retrieved records. This csv is looped through.

IMPORTANT NOTE:

All models queried in this database are exactly 5 nodes deep. There are highest level trunk nodes -- "goal classes", which project goals are linked to. Library goals are then linked to project goals, and Actions are linked to Library goals. Finally, design features are linked to actions.

In this case, the goal classes are decomposed by topic. So, there can be various project goals that are instances of the goal classes. EG: Community Building[encourage climate of collaboration]
*/
if ($_SERVER['HTTP_HOST']==='localhost') {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}
$csv = "file:///tmp/".$_GET["filename"].".csv";
$proj_time = $_GET["filename"];
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
	$affordances[$x]=$claim[5];
	$design_decisions[$x]=$claim[6];
	
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
$super_classes = array();
$project_goals_U = array_unique($project_goals);
foreach($project_goals_U as $i => $pg){
//Adding "may" in front of a string's name guarantees it will be parsed as a Soft Goal
$output = $output."\n\"May ".$pg."\"{ & ;";
$pg_classes = array();
	foreach($goal_classes as $j => $classes){
		if($pg == $project_goals[$j]){
		$pg_classes[$j] = "\n\t\"May ".$goal_classes[$j]." [".$pg."]\"";
		$super_classes[$goal_classes[$j]][] = $goal_classes[$j]." [".$pg."]";
		}
	}
	$pg_classes_U = array_unique($pg_classes);
	foreach($pg_classes_U as $k => $classes_U){
	$output = $output.$classes_U;
	};
$output = $output."\n}\n";
}

$super_classes_U = array_unique($super_classes);

foreach ($super_classes as $i => $super_class){
$output= $output."\n\"May ".$i."\"{ & ;";
unset($class_list);
unset($class_list_U);
	foreach($super_class as $j => $classes){
	$class_list[] = $classes;
	}
	$class_list_U=array_unique($class_list);
	foreach($class_list_U as $k => $class){
	$output= $output."\n\t\"May ".$class."\"";
	}
$output= $output."\n}\n";
}


$goal_classes_U = array_unique($goal_classes);
foreach($goal_classes_U as $i => $gc){
$gc_lib_goals = array();
		foreach($library_goals as $j => $goals){
			if($gc == $goal_classes[$j]){
				$gc_lib_goals[] = "\n\t\"May ".$library_goals[$j]."\" => + \"May ".$goal_classes[$j]." [".$project_goals[$j]."]\"";
			}
		}
	$gc_lib_goals_U = array_unique($gc_lib_goals);
	foreach($gc_lib_goals_U as $k => $lib_goals_U){
		//add unique relationships between library goals and type/topic goal class[project goal]
		$output = $output.$lib_goals_U;
	}
}

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
				case "++":
					$contrib = "++";
					break;
				case "--":
					$contrib = "--";
					break;
				case "+":
					$contrib = "+";
					break;
				case "-":
					$contrib = "-";
					break;
				case "NA":
					$contrib = "+";
					break;
				case "?":
					$contrib = "+";
					break;
				case "AND":
					$contrib = "+";
					break;
				case "OR":
					$contrib = "+";
					break;
				}
			$lg_actions[] = "\n\t\"".$actoids."\" => ".$contrib." \"May ".$lg."\"";
		}
	}
		$lg_actions_U = array_unique($lg_actions);
	foreach($lg_actions_U as $l => $actions_U){
		//add unique relationships between actions and library goals
		$output = $output.$actions_U;
	}
}

$output = $output."\n//Designs - Actions\n";

$actionsacc_U = array_unique($actions);
foreach($actionsacc_U as $i => $acacac){
$acacac_designs = array();
		foreach($design_decisions as $fse => $goals){
			if($acacac == $actions[$fse]){
				switch ($affordances[$fse]) {
					case "affords":
						$contrib = "++";
					break;
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
					case "++":
						$contrib = "++";
						break;
					case "--":
						$contrib = "--";
						break;
					case "+":
						$contrib = "+";
						break;
					case "-":
						$contrib = "-";
						break;
					case "NA":
						$contrib = "+";
						break;
					case "?":
						$contrib = "+";
						break;
					case "AND":
						$contrib = "+";
						break;
					case "OR":
						$contrib = "+";
						break;
				}
				$acacac_designs[] = "\n\t\"Do ".$design_decisions[$fse]."\" => ".$contrib." \"".$actions[$fse]."\"";
			}
		}
	$acacac_designs_U = array_unique($acacac_designs);
	foreach($acacac_designs_U as $sdk => $acacac_desdes_U){
		//add unique relationships between design features and actions
		$output = $output.$acacac_desdes_U;
	}
}

//Dummy agent needs to be created to open OME doesn't make all project goals into actors. Once the diagram has been generated by open ome, the dummy actor can be deleted.
$output = $output."\n\"Dummy\"{ & ;";
foreach($project_goals_U as $i => $pg){
    $output = $output."\n\t\"May ".$pg."\"";
}
foreach($goal_classes_U as $k => $cl_U){
		$output = $output."\n\t\"May ".$cl_U."\"";
}
foreach($lib_goals_U as $r => $lgg_U){
		$output = $output."\n\t\"May ".$lgg_U."\"";
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
