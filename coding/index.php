<?php
$formSection = array();
$formSection[] = array("<span>System Goal</span>", "SysGoal", 60, 230);
$formSection[] = array("Relationship between <span>System Goal</span> and <span>User Goal</span>", "RelSysGoal-UserGoal", 60, 715);
$formSection[] = array("<span>User Goal</span>", "UserGoal", 60, 1195);
$formSection[] = array("Relationship between <span>intended usage</span> and <span>designer Goal</span>", "RelIntUse-DesGoal", 375, 20);
$formSection[] = array("<span>Designer</span>", "Des", 225, 20);
$formSection[] = array("<span>Intended Usage</span>", "IntUse", 515, 20);
$formSection[] = array("Relationship between <span>actual usage</span> and <span>User Goal</span>", "RelActUse-UserGoal", 365, 1430);
$formSection[] = array("<span>User", "User</span>", 215, 1430);
$formSection[] = array("<span>Actual Usage</span>", "ActUse", 505, 1430);
$formSection[] = array("Relationship between <span>actual usage</span> and <span>System Goal</span>", "RelActUse-SysGoal", 645, 1060);
$formSection[] = array("<span>Design Decision</span> or <span>Policy</span>", "Design", 825, 1060);
$formSection[] = array("Relationship between <span>intended usage</span> and </span>User Goal</span>", "RelIntUse-ActGoal", 675, 170);
$formSection[] = array("Relationship between <span>Intended Usage</span> and <span>Actual Usage</span>", "RelIntUse-ActUse", 815, 390);

function formSection ($FullName, $AbbrevName, $top, $left) {
	return "<div id=\"".$AbbrevName."\" style=\"top:".$top."px; left:".$left."px;\">\n\t<h2>".$FullName."</h2>\n\t<input type=\"text\" name=\"".$AbbrevName."Title\" size=15/>\n\t\t<label for=\"".$AbbrevName."Title\">Title</label>\n\t<textarea name=\"".$AbbrevName."Desc\" cols=38></textarea>\n\t\t<label for=\"".$AbbrevName."Desc\">Description</label>\n</div>\n";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
     "http://www.w3.org/TR/html4/transitional.dtd">
<html>
<head>
  <meta HTTP-EQUIV=CONTENT-TYPE CONTENT="text/html; charset=utf-8">
  <title>Coding</title>
  <style type="text/css">
  body {background:url('coding.png') 10px 7px no-repeat; font-size:12px; font-family:arial;}
  span {font-family:monospace; color:red;}
  h1 span {color:black; font-family:arial; color:#222; font-size:0.8em; padding:0 0 0 10px;}
  h2 {font-size:1em; margin:0; padding:0;}
  div {width:360px; padding:20px; height:90px; position:absolute;}
</style>
</head>
<body>
<h1>Coding Scheme<span>- Delib Design Knowledge Structuring Framework</h1>
<form name="precedent-form">
<?php
foreach ($formSection as $i => $value) {
print formSection($formSection[$i][0],$formSection[$i][1],$formSection[$i][2],$formSection[$i][3]);
}
?>
</form>
</body>
</html>
