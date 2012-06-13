<?php
/**
 * Shared functions used to determine the current user context. 
 * Parses the current URL to determine the active project, 
 * active project goal, project model etc.
 */
function active_project(){
if((isset($_GET['project']))&&($_GET['project']!=="")){
    $ActiveProject = urldecode($_GET['project']);
}
else if((isset($_POST['project']))&&($_POST['project']!=="")){
    $ActiveProject = $_POST['project_name'];
}
else{
    redirect("index.php?message=goal");
}
    return $ActiveProject;
}

function active_goal($ActiveProject){
if(((isset($_GET['projgoal']))||(isset($_POST['projgoal'])))&&($_GET['projgoal']!=="")||($_GET['projgoal']!=="")){
    if(isset($_GET['projgoal'])){
        $ActiveGoal['id'] = $_GET['projgoal'];
    }
    else if(isset($_POST['projgoal'])){
        $ActiveGoal['id'] = $_POST['projgoal'];
    }
    $ActiveGoal_name = SQL_query("select goal_name from project_goals where id='".$ActiveGoal['id']."' LIMIT 1;");
    $ActiveGoal['name'] = $ActiveGoal_name[0][0];
    return $ActiveGoal;
}
else{
    redirect("goal_selection.php?project=".$ActiveProject."&message=association");
}
}

function active_classes(){
if(isset($_POST['claim_classes'])){
    $ActiveClasses = $_POST['claim_classes'];
    return $ActiveClasses;
}
else{
    redirect("goal_association.php?project=".$ActiveProject."&projgoal=".$ActiveGoal['id']."&message=claim_error");
}
}

function active_projmod($ActiveProject){
if(isset($_GET['projmod'])){
    $projmod = $_GET['projmod'];
    return $projmod;
}
else{
    redirect("project_model_management.php?project=".$ActiveProject."&message=nomodelselected");
}
}

function projmodname($ActiveProject){
if((isset($_POST['projmod_name']))&&($_POST['projmod_name']!=="")){
    $projmod_name = $_POST['projmod_name'];
    return $projmod_name;
}
else{
    redirect("project_model_management.php?project=".$ActiveProject."&message=projmodnameerror");
}
}

function goal_selections(){
if(isset($_POST['project_goal_class_library_goal'])){
    $PGCLG = $_POST['project_goal_class_library_goal'];
    return $PGCLG;
}
else{
    redirect("goal_browse.php?project=".$ActiveProject."&projgoal=".$ActiveGoal['id']."&message=lib_goal_select_error");
}
}

function redirect($url){
header("Location: $url");
}

function redirect_msg(){
print "<div id=\"redirect_msg\">";
switch($_GET['message']){
case "association":
    print "Redirected here because there was no active goal.";
    break;
case "goal":
    print "Redirected here because there was no active project.";
    break;
case "associations_no_changes":
        if(isset($retrieved_classes)){
        	print "No changes made. <a href=\"goal_selection.php?project=".$ActiveProject."\">Associate another goal?</a>";
		}
		else{
			print "Still no associations.";
    	}
    break;
case "associations_deleted":
    print "Associations deleted. Now Associate another goal.";
    break;
case "associations_inserted":
    print "Associations added. Now Associate another goal.";
    break;
case "lib_goal_select_error":
	print "No goals selected.";
	break;
case "pgclgsaved":
	print "Goal selections saved.";
	break;
case "corrs_saved":
	print "Selected correlated Goals added to project.";
	break;
case "nomodelselected":
	print "No model selected. Choose a model.";
	break;
case "projmodnameerror":
	print "No name provided. Please try again.";
	break;
case "projmodcreated":
	print "Model Created. Try configuring it to suit your context.";
	break;
case "projmodconfigured":
	print "Model Configuration saved. Be sure to check out each goals Q7 model for a different perspective.";
	break;
case "newproject":
	print "Project created.";
	break;
}
print "</div>";
}

$site_name = "Design Knowledge Library";
?>
