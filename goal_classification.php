<?php
include('shared.php');
$ActiveProject = active_project();

include('db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){
include('live_queries.php');
include('sql_io.php');
include('forms.php');

$ActiveGoal = active_goal($ActiveProject);

$library_claim_classes_SQL = 	select_library_claim_classes_SQL();
$select_classes_SQL = select_classes_SQL($ActiveGoal['id']);
$retrieved_classes = SQL_query($select_classes_SQL);
?>
<html>
<head>
    <title><?php print $site_name;?>- Goal Association</title>
    <link rel="stylesheet" href="default.css" type="text/css"/>
    <link rel="stylesheet" href="jquery.multiselect2side.css" type="text/css"/>
    
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="jquery.multiselect2side.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("option:even").addClass('alternate');
            $("#claim_classes").multiselect2side();
        });
    </script>
</head>
<body>
<h1><span class="active_project"><?php print $ActiveProject;?></span>: <?php print $site_name; ?></h1>
<?php include('menu.php');?>
<div id="scenario_goal" class="bigDiv">
    <h2>STEP 2: Associate Project Goal</h2>
<div id="goal_classes">
        <h3>Associate the active goal with one or more aggregate goals.</span></h3>
        <p>Active Goal: <?php print $ActiveGoal['name'];?>
        <p>
        <?php
        print formlist("claim_classes", $retrieved_classes,$library_claim_classes_SQL, "type", "id", true);
        ?>
        <br>
        <input type="radio" name="ACTION_goal_classes" value="next" checked> No changes to scenario goal classificaiton.<br>
        <input type="radio" name="ACTION_goal_classes" value="insert"> Add selected classes to scenario goal classification.<br>
        <input type="radio" name="ACTION_goal_classes" value="delete"> Remove selected classes from scenario goal classification.<br>
        <input type="submit" value="Save Goal Association"></div>
        </form>
    </div>
    
</div>
</body>
</html>
<?php
}
