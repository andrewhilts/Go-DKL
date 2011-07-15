<?php
if(isset($_GET['pub'])){
$publication = $_GET['pub'];

include('../db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){
include('../sql_io.php');

$claim_SQL = "SELECT n1.name as goal, n2.name as action, n3.name as design, n4.name as actor, pub.author as author, claims.source_claim as claim_num, claims.claim_type as claim_type, r1.type FROM publications as pub INNER JOIN claims on pub.author = claims.publication INNER JOIN relationships as r1 ON claims.claim_id = r1.claim_id INNER JOIN nodes as n1 ON r1.parent = n1.id INNER JOIN nodes as n2 ON r1.child = n2.id INNER JOIN relationships as r2 ON claims.claim_id = r2.claim_id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN relationships as r3 ON claims.claim_id = r3.claim_id INNER JOIN nodes as n4 ON r3.child = n4.id WHERE n1.type = 'Soft Goal' AND n2.type = 'Action' AND n3.type = 'Design Decision' AND n4.type = 'Actor' AND pub.author = '$publication';";

$pub_data = SQL_query($claim_SQL);

print "<h2>".$publication."</h2>";
print "<table width=100% border=1><tr><th>Claim #</th><th>Claim Type</th><th width=20%>Goal</th><th width=10%>Contribution</th><th width=20%>Action</th><th>Design Alternative</th></tr>\n";
foreach ($pub_data as $i => $pub_data) {
	print "<tr><td>".$pub_data[5]."</td><td>".$pub_data[6]."</td><td>".$pub_data[0]."</td><td>".$pub_data[7]."</td><td>".$pub_data[1]."</td><td>".$pub_data[2]."</td></tr>";
}
print "</table>";
}
}
else {
print "no publication selected.";
}
