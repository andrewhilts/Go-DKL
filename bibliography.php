<?php

//Step(s) of associating Actions, Design Alternatives?


//print_r($_POST);

if ($_SERVER['HTTP_HOST']==='localhost') {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}

include('menu/shared.php');

include('admin/db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){
include('engine/live_queries.php');
include('admin/sql_io.php');
include('html_templates/forms.php');
$report = SQL_query("select publications.authors_full, publications.yearpub, publications.title, publications.venue, publications.url FROM publications order by author;");

?>
<html>
<head>
    <title><?php print $site_name;?>- Bibliography</title>
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
<h1><?php print $site_name;?>- Bibliography</h1>
<div class="bigDiv">
   <div id="goal_classes">
   <table id="report">
   <tr>
   <th>Publication</th>
   </tr>    
   <?php
   foreach($report as $i => $item){
      print "<tr>";
      print "<td><a href=\"".$item[4]."\" title=\"External link to ".$item[0]." publication source\"><b>".$item[0]." (".$item[1].") </b>".$item[2].". <i>".$item[3]."</i>";
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
