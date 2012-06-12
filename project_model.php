<?php

//print_r($_POST);

if ($_SERVER['HTTP_HOST']==='localhost') {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}

include('menu/shared.php');
$ActiveProject = active_project();
$ActiveProjMod = active_projmod($ActiveProject);

//Make sure to set up your db-permissions

include('admin/db-permissions.php');
$db_handle = mysql_connect($hostname, $username, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found){
include('engine/live_queries.php');
include('admin/sql_io.php');
include('html_templates/forms.php');

//Retrieve project model, see "projmod_retrieve" function in 'live_queries.php'
$slice_SQL = projmod_retrieve ($ActiveProjMod, "gc`, `lg`, `a`, `d", "DESC");

//$slice_SQL = projmod_retrieve ($ActiveProjMod, "pg`, `lg`, `a`, `d", "DESC");

$ActiveProjModName = SQL_query("SELECT projmod_name as name from projmods WHERE projmod_id=$ActiveProjMod;");

$ActiveProjModName = $ActiveProjModName[0][0];
//Retrieve Descriptions for all items in project model.
$all_projmod_descriptions_SQL = select_descriptions_projmod_SQL($ActiveProjMod);

//$slice_SQL = export_no_corr_designs_corr_relations($ActiveProject, "pg`, `lg`, `a`, `d", "DESC", null, "project", null);
		
$slices = SQL_assoc_query($slice_SQL);
//print $slice_SQL;
$id=0;
$contrib_style = "";

//convert various contribution labels into simpler ones
function plus_minus_contrib($c){
	global $contrib_style;
	switch($c) {
		case "helps":
			$contrib_style = "helps";
			return "+";
			break;
		case "hurts":
			$contrib_style = "hurts";
			return "-";
			break;   
		case "affords":
			$contrib_style = "helps";
			return "++";
			break;
		case "AND":
			$contrib_style = "helps";
			return "AND";
			break;
		case "OR":
			$contrib_style = "helps";
			return "OR";
			break;
		case "++":
			$contrib_style = "helps";
			return "++";
			break;
		case "+":
			$contrib_style = "helps";
			return "+";
			break;
		case "?":
			$contrib_style = "";
			return "?";
			break;
		case "-":
			$contrib_style = "hurts";
			return "-";
			break;
		case "--":
			$contrib_style = "hurts";
			return "--";
			break;
		case "NA":
			$contrib_style = "";
			return "NA";
			break;
	}
}

//create HTML form selectors to enable contribution value modification
function contrib_selector($contrib, $id, $style){
	if($style=="right"){
	$class="alternatives_id";
	}
	else{
	$class="small";
	}
 		$o1 = "<option>AND</option>\n";
        $o2 = "<option>OR</option>\n";
        $o3 = "<option>++</option>\n";
        $o4 = "<option>+</option>\n";
        $o5 = "<option>?</option>\n";
        $o6 = "<option>-</option>\n";
        $o7 = "<option>--</option>\n";
        $o8 = "<option>N/A</option>\n";
 
     switch ($contrib) {
            case "AND":
                $o1 = "<option selected class=\"selected\">AND</option>\n";
                break;
            case "OR":
                $o2 = "<option selected class=\"selected\">OR</option>\n";
                break;
            case "++":
                $o3 = "<option selected class=\"selected\">++</option>\n";
                break;
            case "+":
                $o4 = "<option selected class=\"selected\">+</option>\n";
                break;
            case "?":
                $o5 = "<option selected class=\"selected\">?</option>\n";
                break;
            case "-":
                $o6 = "<option selected class=\"selected\">-</option>\n";
                break;
            case "--":
                $o7 = "<option selected class=\"selected\">--</option>\n";
                break;
            case "NA":
                $o8 = "<option selected class=\"selected\" value=\"NA\">N/A</option>\n";
                break;
     }
     
     return "
        <select name=\"".$id."\" class=\"".$class."\" title=\"Alter the contribution value this node provides to its parent\">
        ".$o1.$o2.$o3.$o4.$o5.$o6.$o7.$o8."
        </select>
     ";
 }

//create checklists to enable node selection and deselection. 
function checky($id,$check_status){
	if($check_status=="1"){
	$checked="checked";
	}
	else{
	$checked="";
	}
	$random = rand (1, 99999);
    return "<input type=\"hidden\" name=\"".$id."_".$random."\" value=0><input type=\"checkbox\" class=\"\" style=\"float:right;\" ".$checked." name=\"".$id."_".$random."\" id=\"".$id."\" value=1 title=\"Include or exclude this node in your project model\">";
}

?>
<html>
<head>
    <title><?php print $site_name;?>- Project Model Configuration</title>
    <link rel="stylesheet" href="css/default.css" type="text/css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/js.js"></script>
	<link rel="stylesheet" href="css/treeview.css" type="text/css" />
	<script type="text/javascript" src="js/treeview.js"></script>
	    	<script type="text/javascript" src="js/custom-form-elements.js"></script>
	<script>
	    //Render tree list
		$(document).ready(function(){
			$("#alternatives_analysis_container").treeview({
				animated: "fast",
				persist: "location",
				collapsed: true,
				unique: true
			});
         $("#instructions_btn").click(function(){$("#instructions").toggle()});
		});
	</script>
    <!-- Evaluation label propagation rules-->
	<script type="text/javascript" src="js/eval.js"></script>
</head>
<body>

<?php

if(isset($_GET['message'])){
redirect_msg();
}
?>
<h1><span class="active"><?php print $ActiveProject;?></span>: <?php print $site_name; ?></h1>
<?php include('menu/menu.php');?>
<div id="alternatives_identification" class="bigDiv">
    <h2>STEP 3.1: Configure Project Model - &quot;<?php print $ActiveProjModName; ?>&quot;</h2>
<div id="goal_classes">
        <p>Below is a hierarchical tree list that contains the <?php print $ActiveProject;?> project's initially-defined goals, which are related to the included goals that were selected. From there, the library has retrieved various other goals and design alternatives that may impact the project's goals.</p>
        <input type=reset value="Instructions" id="instructions_btn">
        <ul id="instructions" style="display:none;">
        	<li>Each list item may be removed from the model by unchecking its box: <input type="checkbox" checked />. Note that the same item may be a child of multiple list items, and that deselecting one instance of that item will deselect all of them. If you wish to omit a single instance of an item, modify its contribution value to N/A (see below).
        	<li>Each list item has a contribution value, which is passed to its parent item. The value retrieved from the library is indicated as the default selection in the dropdown list (eg: <?php print contrib_selector("++", "demo", "small");?>), which you may reconfigure to suit your context.
        	<li>Each included goal has a corresponding <img src="img/softgoal.gif" width=30 alt="soft goal"> button, which when clicked generates a goal-specific model slice. You will be able to see all design alternatives that contribute to the selected goal, as well as all other goals that are affected by those designs. This model slicing is meant to facilitate analysis of individual design alternatives in relation to project goals.
        	<li>Click on the <span class="more_info_btn">&#9654;</span> for more information about each list item, like its description and the sources in which it has been found.
        </ul>
       
        <div id="alternatives_status">notes</div>
        <!--- Tree list is contained within an HTML form, changes to tree list controls can be submitted to the server script below and processed. In this way, changes to the model can be saved. This is sometimes a bit buggy, which is why there appear to be more negative contributions in project models than are found the original data set.--->
        <form name="project_model" action="engine/form_processing.php?type=projmod_config&project=<?php print $ActiveProject;?>&projmod=<?php print $ActiveProjMod;?>" method="post">
        <div id="descriptions_box">
		  <?php 
		      //print $all_projmod_descriptions_SQL;
		      print descriptions($all_projmod_descriptions_SQL, "");
		  ?>
        </div>
		    <?php
		print "<div style=\"width:500px\">\n<ul id=\"alternatives_analysis_container\">";
		 
    	$last_pg = "foo";
    	$last_lg = "foo";
    	$last_a = "foo";
    	$last_d = "foo";

        //Add a "more info" button for each node, to display its description in box
    	function moreinfo ($id, $extra) {
    	    return "<div class=\"passive\" id=\"desc_btn_".$id."-".$extra."\" onclick=\"toggle('desc_".$id."', 'descriptions','desc_btn_".$id."-".$extra."'); toggle2('desc_btn_".$id."-".$extra."','button');\" title=\"Click for more information.\">&#9654;</div>";
    	}
    	$ii=0;
    	
    	/* 	
    	
    	IMPORTANT NOTE:

        All models queried in this database are exactly 5 nodes deep. There are highest level trunk nodes -- "goal classes", which project goals are linked to. Library goals are then linked to project goals, and Actions are linked to Library goals. Finally, design features are linked to actions.
        
        
        Below is the Main loop over the retrieved table. Converts it into hierarchical, unordered list. The loop only works because the retrieved array is presorted based on the hierarchical categories. As shown on line 23, the query sorts by `gc`, `lg`, `a`, `d", DESC. So, first we have all contribution relationships with the same goal class, and within that subset we have contributions sorted by library goal, then action, then design. in this way we can loop over the table and generate trees. 
        
        The loop also adds many interface items, sorry about the messy code!
    	
*/
    	
    	while($slice = mysql_fetch_assoc($slices)) {
    	   $ii++;
    	    $a_id = $slice["a_id"];
       		$d_id = $slice["d_id"];
	        $lg_id = $slice["lg_id"];
    	    if(($last_pg=="foo")||($slice["gc"]!==$last_pg)){
    	        if($last_pg!=="foo"){
    	        print "\n\t\t\t\t\t</ul>\n\t\t\t\t</li>\n\t\t\t</ul>\n\t\t</li>\n\t</ul>\n</li>";
    	        }
    	        $last_lg = "foo";
    	        $last_a = "foo";
    	        $last_d = "foo";
    	        print "\n<li><span>".$slice["gc"]."</span>\n\t<ul>"; 
    	    }
    	    if(($last_lg=="foo")||($slice["lg"]!==$last_lg)){
    	        if($last_lg!=="foo"){
    	        print "\n\t\t\t\t\t</ul>\n\t\t\t\t</li>\n\t\t\t</ul>\n\t\t</li>";
    	        }
    	        $last_a = "foo";
    	        $last_d = "foo";
    	        
    	        
		        $contrib_selector = contrib_selector("++", $slice['gc_id']."_".$lg_id, "right");
		            $checkbox = checky("check_".$lg_id, $slice['n1_checked']);
    	        
    	        //print project goal list item and controls, including Q7 export button
    	        print "\n\t\t<li class=\"alternatives_L1\"><span>".$slice["lg"]."</span>".moreinfo($lg_id,$ii)."<img onclick=\"window.location='form_processing.php?type=projmod_goal_slice&project=".$ActiveProject."&projmod=".$ActiveProjMod."&projmod_goal_id=".$lg_id."';\" src=\"img/softgoal.gif\" class=\"q7\" title=\"Download Q7 file for goal slice of &quot;".$slice["lg"]."&quot;\" width=30 alt=\"soft goal\" style=\"float:right\"/>".$checkbox.$contrib_selector."\n\t\t\t<ul>";
    	    }
    	    if(($last_a=="foo")||($slice["a"]!==$last_a)){
    	        if($last_a!=="foo"){
    	        print "\n\t\t\t\t\t</ul>\n\t\t\t\t</li>";
    	        }
    	        $last_d = "foo";
    	        
    	        $contrib = plus_minus_contrib($slice["c1"]);
           		
           		$contrib_selector = contrib_selector($contrib, $a_id."_".$lg_id, "right");
           		$checkbox = checky("check_".$a_id, $slice['n2_checked']);
           		
    	        print "\n\t\t\t\t<li class=\"alternatives_L2\"><span>".$slice["a"]."</span>".moreinfo($a_id,$ii).$checkbox.$contrib_selector."\n\t\t\t\t\t<ul>";
    	       
    	        
    	    }
    	    if(($last_d=="foo")||($slice["d"]!==$last_d)){

           		$checkbox = checky("check_".$d_id, $slice['n3_checked']);
    	        $contrib = plus_minus_contrib($slice["c2"]);
           		$contrib_selector = contrib_selector($contrib, $d_id."_".$a_id, "right");
    	        print "\n\t\t\t\t\t\t<li class=\"alternatives_L3\"><span>".$slice["d"]."</span>".moreinfo($d_id,$ii).$checkbox.$contrib_selector."</li>";
    	    }
    	    $last_pg=$slice["gc"];
    	    $last_lg = $slice["lg"];
    	    $last_a = $slice["a"];
    	    $last_d = $slice["d"];
    	}
    	print "\n\t\t\t\t\t</ul>\n\t\t\t\t</li>\n\t\t\t</ul>\n\t\t</li>\n\t</ul>\n</li>\n</ul>\n";
?>
        <br>
        
        <!---Save project model based on form modifications in treelist--->
        <input type="submit" value="Save Project Model Configuration!"> <a href="engine/form_processing.php?type=projmod_complete_q7&project=<?php print $ActiveProject;?>&projmod=<?php print $ActiveProjMod;?>" title="Complete project Q7"><img src="img/softgoal.gif" width=50 alt="soft goal"></a></div>
        </form>

    </div>
    
</div>
</body>
</html>
<?php
}
