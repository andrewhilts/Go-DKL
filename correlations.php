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

$project_goal_correlations_SQL = select_project_goal_correlations_SQL($ActiveProject);
$project_goal_correlations = SQL_query($project_goal_correlations_SQL);
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

$helped_cor_goals=array_unique($helped_cor_goals);
$hurt_cor_goals=array_unique($hurt_cor_goals);
$all_cor_goals = array_merge($helped_cor_goals, $hurt_cor_goals);
$conflict_cor_goals = array_unique(array_diff_assoc($all_cor_goals,array_unique($all_cor_goals)));
if($conflict_cor_goals){
	$hurt_cor_goals=array_diff($hurt_cor_goals,$conflict_cor_goals);
	$helped_cor_goals=array_diff($helped_cor_goals,$conflict_cor_goals);
}

?>
<html>
<head>
    <title><?php print $site_name;?>- Correlated Goal Exploration</title>
    <link rel="stylesheet" href="default.css" type="text/css"/>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="js.js"></script>
    	<script type="text/javascript" src="custom-form-elements.js"></script>
    	    		<script>
		     $(document).ready(function(){
			     /*  $(".styled").parent().click(function(){
			         checkbox=$(this).children('input[type=checkbox]');
                  name = $(checkbox).attr("name");
                  dropdown = name.replace("correlation_add_", "correlation_dropdowns_");
                  if($(checkbox).attr('checked')){
                  document.getElementById(dropdown).className = 'visible';
                  }
                  else{
                  document.getElementById(dropdown).className = 'hidden';
                  }
			         
			      });*/
			      $('input[type=checkbox]').click(function(){;
                  name = $(this).attr("name");
                  dropdown = name.replace("correlation_add_", "correlation_dropdowns_");
                  if($(this).attr('checked')){
                  document.getElementById(dropdown).className = 'visible';
                  }
                  else{
                  document.getElementById(dropdown).className = 'hidden';
                  }
			         
			      });
		      });
	</script>
    
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
    <h2>STEP 2: Explore Correlated Goals</h2>
    <p>The following are recommended Design Alternatives that help satisfy project goals that also have unaccounted-for contributions.
    <p class="spanned"><span><?php print $suggested_designs;?></span></p>
    <p class="spanned"><br>The below list are goals from the design library that have not been included in your project. However, the recommended design alternatives for your project have been found to contribute to these goals. Consider whether they are relevant to include them for goal analysis.
	<h3>Select any goals you would like to include in your model.</h3>
	<div id="correlations_description_box">
		<?php print cor_descriptions($cor_metadata);?>
	</div>
	<form name="correlations" action="form_processing.php?type=correlation_add&project=<?php print $ActiveProject;?>" method="post">
		<div id="correlation_list">
			<h3>Positive Correlations</h3>
			<?php	
			foreach ($helped_cor_goals as $i => $goal_name_id){
			 		corr_goal_print("helps", $goal_name_id);
			 	}
		 	?>
			<h3>Conflicting Correlations</h3>
			<?php
			foreach ($conflict_cor_goals as $i => $goal_name_id){
			 		corr_goal_print("conflict", $goal_name_id);
	 		}
	 		?>
			<h3>Negative Correlations</h3>
			<?php
			foreach ($hurt_cor_goals as $i => $goal_name_id){
			 		corr_goal_print("hurts", $goal_name_id);
			 	}
		 	?>
	 	</div>
 		<br>
        <input type="submit" value="Include Selected Goals!"></div>
	</form>
</div>
</body>
</html>
<?php
}
