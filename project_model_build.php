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

$slice_SQL = export_no_corr_designs_corr_relations($ActiveProject, "pg`, `lg`, `a`, `d", "DESC", null, "project", null);
		
$slices = SQL_assoc_query($slice_SQL);
//print $slice_SQL;


?>
<html>
<head>
    <title><?php print $site_name;?>- Project Model Configuration</title>
    <link rel="stylesheet" href="default.css" type="text/css"/>
    <script type="text/javascript" src="jquery.js"></script>
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
	<script type="text/javascript" src="eval.js"></script>
</head>
<body>

<?php

if(isset($_GET['message'])){
redirect_msg();
}
?>
<h1><span class="active"><?php print $ActiveProject;?></span>: <?php print $site_name; ?></h1>
<?php include('menu.php');?>
<div id="alternatives_identification" class="bigDiv">
    <h2>STEP 5: Configure Project Model</h2>
 	<form name="project_goal_association" action="form_processing.php?type=projmod_add&project=<?php print $ActiveProject;?>" method="post">
		<input type="text" name="projmod_name" length=50/><label for="projmod_name">Create new project model!</label>
		<input type="submit" value="Create Model"/>
    </form>
</div>
</body>
</html>
<?php
}
