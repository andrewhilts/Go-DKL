<?php

//Step(s) of associating Actions, Design Alternatives?


//print_r($_POST);

if ($_SERVER['HTTP_HOST']==='localhost') {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}

include('menu/shared.php');
$ActiveProject = active_project();
$ActiveProjMod = active_projmod($ActiveProject);
include('admin/db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){
include('engine/live_queries.php');
include('admin/sql_io.php');
include('html_templates/forms.php');
$slice_SQL = projmod_report($ActiveProjMod, "`d`, `lg`, `a`", "DESC");
$ActiveProjModName = SQL_query("SELECT projmod_name as name from projmods WHERE projmod_id=$ActiveProjMod;");
$ActiveProjModName = $ActiveProjModName[0][0];
$report_SQL = output_pub_list($ActiveProjMod);
$report = SQL_query($report_SQL);

?>
<html>
<head>
    <title><?php print $site_name;?>- Project Model Configuration</title>
    <link rel="stylesheet" href="css/default.css" type="text/css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/js.js"></script>
</head>
<body>

<?php

if(isset($_GET['message'])){
redirect_msg();
}

?>
<h1><span class="active"><?php print $ActiveProject;?></span>: <?php print $site_name; ?></h1>
<?php include('menu/menu.php');?>
<div class="bigDiv">
    <h2>Project Model Reports - &quot;<?php print $ActiveProjModName; ?>&quot;</h2>
   <div id="goal_classes">
   <p><a href="#designs">Related Design Alternatives</a> | <a href="#report">Relevant publications</a>
   <?php
   
   function plus_minus_contrib($c){
	switch($c) {
		case "helps":
			return 1;
			break;
		case "hurts":
			return -1;
			break;   
		case "affords":
			return 1;
			break;
		case "AND":
			return 1;
			break;
		case "OR":
			return 1;
			break;
		case "++":
			return 1;
			break;
		case "+":
			return 1;
			break;
		case "?":
			return 0;
			break;
		case "-":
			return -1;
			break;
		case "--":
			return -1;
			break;
		case "NA":
			return 0;
			break;
	}
}
      $slices = SQL_assoc_query($slice_SQL);
      $i=0;
      $last_d = "foo";
      $last_gc = "foo";
      $last_lg = "foo";
      print "<h3 id=\"designs\">Related Design Alternatives</h3><table>";
      ?>
      <th>Design Feature</th><th>Description and Related Goals</th>
      <?php
      while($slice = mysql_fetch_assoc($slices)) {
      if ($slice['d']!=$last_d){print "</tr><tr><td class=\"design\">".$slice['d']."</td><td ><span class=\"desc\">".$slice['d_desc']."</span>&nbsp;(<a href=\"#".$slice['pubid']."\" title=\"See reference\">".$slice["author"].", ".$slice['year']."</a>)<p>";}
      //if ($slice['gc']!=$last_gc){print "<p>".$slice['gc']."</p>";}
      if ($slice['lg']!=$last_lg){
         $c1 = plus_minus_contrib($slice['c1']);
         $c2 = plus_minus_contrib($slice['c2']);
         $cc = $c1+$c2;
         if($cc>0){$style="helps";}
         else if($cc<0){$style="hurts";}
         else {$style="conflict";}
         print "<span class=\"".$style."\">".$slice['lg']."</span>&nbsp;&nbsp;";}
      $last_d = $slice['d'];
      $last_gc = $slice['gc'];
      $last_lg = $slice['lg'];
      $i++;
      } 
   print "</table>";
   ?>
  <h3 id="report">Relevant Publications</h3>
      <table >
   <tr>
   <th>References</th><th width=40%>Publication</th><th width="50%">Selected Goals mentioned</th>
   </tr> 
   <?php
   foreach($report as $i => $item){
      print "<tr>";
      print "<td class=\"number\" id=\"".$item[7]."\">".$item[6]."</td><td><a href=\"".$item[4]."\" title=\"External link to ".$item[0]." publication source\"><b>".$item[0]." (".$item[1].") </b>".$item[2].". <i>".$item[3]."</i>.<td>".$item[5]."</td>";
      print "</tr>";
   }
   ?>
   </table>
    </div>
    
</div>
</body>
</html>
<?php
}
