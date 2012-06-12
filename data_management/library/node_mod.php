<?php
if ($_SERVER['HTTP_HOST']==='localhost') {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}
  include('../db-permissions.php');
  $db_handle = mysql_connect($hostname, $username, $password);
  $db_found = mysql_select_db($database, $db_handle);
if ($db_found) {
include('../forms.php');
include('../sql_io.php');
?>
<style type="text/css">
.count {color:#666;}
.node {font-family:monospace; font-size:1.6em; color:navy; font-weight:bold;}
.pub_name {font-weight:bold;}
#submit {background-color:#ded; padding:20px 10px; position:fixed; top:10px; right:10px; border:1px solid #ccc;}
</style>
<h1>Library</h1>
<h2>Modify Nodes</h2>
<?php
$soft_goal_SQL = "SELECT nodes.name, nodes.description, group_concat(distinct ' ', claims.publication), nodes.id, group_concat(distinct '</td><td>', claims.claim_id), group_concat(distinct '</td><td>', claims.claim_type) FROM nodes RIGHT JOIN (relationships, claims) on (nodes.id = relationships.parent AND relationships.claim_id = claims.claim_id) WHERE nodes.type = 'Soft Goal' GROUP BY nodes.name ORDER BY claims.publication;";
$soft_goals = SQL_query($soft_goal_SQL);

$design_decision_SQL = "SELECT nodes.name, nodes.description, group_concat(distinct ' ', claims.publication), nodes.id,  group_concat(distinct ' ', claims.claim_id), group_concat(distinct ' ', claims.claim_type) FROM nodes RIGHT JOIN (relationships, claims) on (nodes.id = relationships.child AND relationships.claim_id = claims.claim_id) WHERE nodes.type = 'Design Decision' GROUP BY nodes.name ORDER BY claims.publication;";
$design_decisions = SQL_query($design_decision_SQL);

$Action_SQL = "SELECT nodes.name, nodes.description, group_concat(distinct ' ', claims.publication), nodes.id,  group_concat(distinct ' ', claims.claim_id), group_concat(distinct ' ', claims.claim_type) FROM nodes RIGHT JOIN (relationships, claims) on (nodes.id = relationships.child AND relationships.claim_id = claims.claim_id) WHERE nodes.type = 'Action' GROUP BY nodes.name ORDER BY claims.publication;";
$actions = SQL_query($Action_SQL);
?>
<h3><?php print $project_info[0][0];?></h3>

<form name="mod_nodes" action="nodes_alter.php" method="post">
<div id="submit">
<input type="submit" value="Update Nodes"/>
</div>
<?php
function node_mod_form($node_array, $node_name, $node_id){
	print "<h3 id=\"".$node_id."\">".$node_name."s</h3>";
	print "<hr style=\"clear:both; color:#ddd;\"/>";
	foreach ($node_array as $i => $node) {
		print "
		<table>
		<tr>
		<td width=280><p>".$node_name.": <span class=\"count\">N-".$node[3]."</span><p><span class=\"node\">".$node[0]."</span></td>
		<td><label for=\"".$node[3]."\">Name:</label><br>
		<input type=\"text\" name=\"".$node[3]."\" value=\"".$node[0]."\" size=40/></td></tr>";
		print "
		<tr>
		<td>Found in: <span class=\"pub_name\">".$node[2]."</span><br><table><tr><td>".$node[4]."</td></tr><tr><td>".$node[5]."</td></tr></table></td>
		<td><label for=\"".$node[3]."_description\">Description:</label><br>
		<textarea name=\"".$node[3]."_description\" rows=\"5\" cols=\"70\">".$node[1]."</textarea></td></tr>
		<tr><td colspan=3><hr></td></tr>
		</table>";
	}
}
node_mod_form($soft_goals, "Soft Goal", "soft_goals");
node_mod_form($actions, "Action", "actions");
node_mod_form($design_decisions, "Design Decision", "design_decisions");
?>

<hr style="clear:both; color:#ddd;"/>
<input type="submit" value="Modify &quot;<?php print $project_info[0][0];?>&quot;"/>
</form>
<?php

}
else {
print "db not found";
}
?>
