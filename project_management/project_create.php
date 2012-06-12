<?php
include('../menu/shared.php');
?>
<html>
<head>
    <title><?php print $site_name;?>- Goal Association</title>
    <link rel="stylesheet" href="../css/default.css" type="text/css"/>
    <script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
  $(document).ready(function(){ 
  $("#menu tr:even").addClass('alternate');
});
</script>
</head>
<body>
<?php
if(isset($_GET['message'])){
redirect_msg();
}
?>

<h1><?php print $site_name; ?></h1>
<?php include('../menu/menu.php');?> 
<div id="scenario_goal" class="bigDiv">
<h2>New Project</h2>
<div>
<P>Provide a name, description, and a short list of goals (recommended ~5; max 20).

<form name="new_project" action="../engine/form_processing.php?project=new&type=new_project" method="post">
<label for="project_name" style="width:100px; display:block; float:left; margin:5px 0 0 0">Project Name</label><input type="text" name="project_name"/>
<hr style="clear:both; color:#ddd;"/>
<br><label for="project_description" style="width:100px; display:block; float:left; clear:both; margin:5px 0 0 0">Project Description</label><textarea name="project_description" rows="5" cols="50">
</textarea>
<?php
for($x = 1; $x <=20; $x++){
print "<hr style=\"clear:both; color:#ddd;\"/><label for=\"project_goal".$x."\" style=\"width:100px; display:block; float:left; margin:5px 0 0 0\">Goal ".$x."</label><input type=\"text\" name=\"project_goal".$x."\"/>";
}
?>
<hr style="clear:both; color:#ddd;"/>
<input type="submit" value="Create Project"/>
</form>
</div>
</div>

</body>
</html>

