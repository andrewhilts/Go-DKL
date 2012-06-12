<?php
function menu_item($text, $title, $href, $status) {
if(isset($status)){$status="class=\"".$status."\"";}
if(isset($href)){
   return "<li ".$status."><a href=\"".$href."\" title=\"".$title."\">".$text."</a>";
}
else{
   return "<li ".$status.">".$text;
}
}
function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
$thisPage = curPageName();
$index_item = menu_item("<img src=\"img/house_go.png\" alt=\"home icon\">", "Projects", "index.php", "index");
if(isset($ActiveProject)){
$goal_selection_item = menu_item("Select Project Goal", "Select Project Goal", "goal_selection.php?project=".$ActiveProject, null);
$correlations_item = menu_item("Correlated Goals", "Correlated Goals", "goal_correlations.php?project=".$ActiveProject, null);
$project_model_create_item = menu_item("Project Models", "Project Models", "project_model_management.php?project=".$ActiveProject, null);
if(isset($ActiveGoal['id'])){
   $goal_association_item = menu_item("Associate Project Goal", "Associate Project Goal", "goal_association.php?project=".$ActiveProject."&projgoal=".$ActiveGoal['id'], null);
   $goal_browse_item = menu_item("Browse Library Goals", "Browse Library Goals", "goal_browse.php?project=".$ActiveProject."&projgoal=".$ActiveGoal['id'], null);
}
if(isset($ActiveProjMod)){
$projmod_config_item= menu_item("Configure Project Model", "Configure Project Model", null, "current");
$projmod_report_item= menu_item("Project Model Report", "Project Model Report", null, "current");
}
}
switch($thisPage){
   case "index.php":
      $index_item = menu_item("<img src=\"img/house_go.png\" alt=\"home icon\">", "Projects", "index.php", "index");
      $goal_selection_item = menu_item("Select Project Goal", "Select Project Goal", null, "inactive");
      $correlations_item = menu_item("Correlated Goals", "Correlated Goals", null, "inactive");
      $project_model_create_item = menu_item("Project Models", "Project Models", null, "inactive");
      break;
   case "goal_selection.php":
      $goal_selection_item = menu_item("Select Project Goal", "Select Project Goal", null, "current");
      break;
   case "goal_correlations.php":
      $correlations_item = menu_item("Correlated Goals", "Correlated Goals", null, "current");
      break;
   case "project_model_management.php":
      $project_model_create_item = menu_item("Project Models", "Project Models", null, "current");
      break;
   case "goal_association.php":
      $goal_selection_item = menu_item("Select Project Goal", "Select Project Goal", "goal_selection.php?project=".$ActiveProject, "parent");
      $goal_association_item = menu_item("Associate Project Goal", "Associate Project Goal", null, "current");
      break;
   case "goal_browse.php":
      $goal_selection_item = menu_item("Select Project Goal", "Select Project Goal", "goal_selection.php?project=".$ActiveProject, "parent");
      $goal_browse_item = menu_item("Browse Library Goals", "Browse Library Goals", null, "current");
      break;
   case "project_model.php":
      $project_model_create_item = menu_item("Project Models", "Project Models", "project_model_management.php?project=".$ActiveProject, "parent");
      break;
   case "project_model_report.php":
      $project_model_create_item = menu_item("Project Models", "Project Models", "project_model_management.php?project=".$ActiveProject, "parent");
      break;
}
?>
<div id="menu">
   <ul>
   <?php
      print $index_item;
      if($thisPage!=="index.php"){
      print $goal_selection_item;
      print $correlations_item;
      print $project_model_create_item;
      }
   ?>
   </ul>
         
         <?php
         if(isset($ActiveGoal['id'])){
         print "<ul id=\"sub\">";
         print $goal_association_item;
         print $goal_browse_item;
         print "</ul>";
         }
         if((isset($ActiveProjMod))&&($thisPage=="project_model.php")){
         print "<ul id=\"sub2\">";
         print $projmod_config_item;
         print "</ul>";
         }
         if((isset($ActiveProjMod))&&($thisPage=="project_model_report.php")){
         print "<ul id=\"sub2\">";
         print $projmod_report_item;
         print "</ul>";
         }
         ?>
</div>
