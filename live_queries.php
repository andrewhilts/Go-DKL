<?php

function output_pub_list ($ActiveProjMod) {
   return "select publications.authors_full, publications.yearpub, publications.title, publications.venue, publications.url, concat(concat('<ul>',group_concat(distinct '<li>', nodes.name SEPARATOR '')),'</ul>'), count(*), author from projmod_rels INNER JOIN claims on claim_lib = claim_id INNER JOIN nodes on parent = nodes.id INNER JOIN publications on claims.publication = publications.author where projmod_id =$ActiveProjMod AND publication<>'' AND (nodes.type='Soft Goal' OR nodes.type='Design Decision') group by publication order by count(*) DESC;";
}


function select_classes_SQL ($ActiveGoal) {
	return "
		SELECT 
			claim_class_types.id, 
			claim_class_types.type, 
			project_goal_id 
		FROM project_goal_classes 
		INNER JOIN claim_class_types ON  		
			project_goal_classes.goal_class_id = claim_class_types.id
		WHERE project_goal_id='$ActiveGoal';
		";
}

function select_library_goals_SQL($ActiveGoal) {
	return "
		SELECT 
			library_goal_id 
		FROM project_goal_class_library_goal 
		WHERE project_goal_id='$ActiveGoal';
		";
}

function select_projects_SQL () {
	return "SELECT * FROM projects ORDER BY name;";
}


function select_project_goals_SQL($ActiveProject) {
	return "SELECT `goal_name`, `id` FROM `project_goals` WHERE project_name = '".$ActiveProject."' ORDER BY goal_name;";
}

function select_class_library_goals_SQL ($class_type_array) {

	if ($class_type_array) {
		 foreach ($class_type_array as $i => $value) {
		     if (!$class_type) {
		         $class_type = $class_type_array[$i];
		     } else {
		         $class_type = $class_type . ", " . $class_type_array[$i];
		     }
		     $class_type_array[$i] = "claim_classes.class_type = '" . $class_type_array[$i] . "'";
		 }
		 $class_types = implode(" OR ", $class_type_array);
	}

	return "SELECT nodes.name as goal, claim_classes.class_type as class, nodes.id as id FROM `nodes` INNER JOIN relationships ON relationships.parent = nodes.id INNER JOIN claim_classes ON relationships.claim_id = claim_classes.claim_id WHERE nodes.type='Soft Goal' AND ($class_types) GROUP BY class, goal;";
}

function select_library_claim_classes_SQL() {
	return "select * from `claim_class_types` group by type";
}

function select_descriptions_SQL() {
	return "SELECT nodes.name as goal, nodes.id as id, nodes.description as description, claim_classes.class_type as class, claims.source_claim, claims.publication, claims.claim_type FROM `nodes` RIGHT JOIN (relationships, claim_classes, claims) ON (relationships.parent = nodes.id AND relationships.claim_id = claim_classes.claim_id AND relationships.claim_id = claims.claim_id) WHERE nodes.type='Soft Goal' GROUP BY goal;";
}

function select_descriptions_projmod_SQL($ActiveProjmod) {
	return "SELECT distinct nodes.name as goal, nodes.id as id, nodes.description as description, claim_classes.class_type as class, claims.source_claim, claims.publication, claims.claim_type FROM `nodes` RIGHT JOIN (relationships, claim_classes, claims) ON ((relationships.parent = nodes.id OR relationships.child = nodes.id) AND relationships.claim_id = claim_classes.claim_id AND relationships.claim_id = claims.claim_id) INNER JOIN projmod_nodes on nodes.id = projmod_nodes.id WHERE projmod_nodes.projmod_id=$ActiveProjmod group by id order by id;";
}

function select_project_goal_lib_goal_SQL ($ActiveGoal, $values) {
	return "SELECT library_goal_id, checked FROM project_goal_class_library_goal INNER JOIN project_goal_classes ON project_goal_class_library_goal.project_goal_id=project_goal_classes.project_goal_id WHERE project_goal_class_library_goal.project_goal_id='$ActiveGoal' AND project_goal_classes.goal_class_id='$values';";
}

function select_project_goal_lib_goal_SQL2 ($ActiveGoal, $values, $ActiveGoalClass) {

	return "
	SELECT nodes.id, nodes.name as goal, class_type, claims.claim_id, nodes.description, claims.source_claim, claims.publication, claims.claim_type, (select rationale from project_goal_class_library_goal where (checked=1 OR checked=0) and project_goal_id=$ActiveGoal and library_goal_id=nodes.id and goal_class='$values') as rationale, (select checked from project_goal_class_library_goal where checked=1 and project_goal_id=$ActiveGoal and library_goal_id=nodes.id and goal_class='$values') as checked 
from nodes inner join relationships on nodes.id = relationships.parent inner join claims on relationships.claim_id = claims.claim_id right join (claim_classes, claim_class_types) on (claims.claim_id = claim_classes.claim_id and claim_class_types.id = claim_classes.class_type) where nodes.id IS NOT NULL AND claim_classes.class_type='$values' AND nodes.type='Soft Goal' group by nodes.id order by checked DESC, nodes.name ASC;
	";
/*	return "
	select nodes.id, nodes.name as goal, class_type, project_goal_id, checked, claims.claim_id, nodes.description, claims.source_claim, claims.publication, claims.claim_type, rationale
	from project_goal_class_library_goal right join nodes on nodes.id = library_goal_id 
	inner join relationships on nodes.id = relationships.parent
	inner join claims on relationships.claim_id = claims.claim_id
	right join (claim_classes, claim_class_types) on (claims.claim_id = claim_classes.claim_id and claim_class_types.id = claim_classes.class_type)
	where nodes.id IS NOT NULL
	AND claim_classes.class_type='$values'
	AND (project_goal_id = '$ActiveGoal' OR project_goal_id IS NULL)
	AND nodes.type = 'Soft Goal' AND goal_class='$ActiveGoalClass'
	group by name, claim_class_types.type
	;";
*/
}

function select_project_goal_correlations_SQL($ActiveProject) {
	return "
	select 
	    distinct r3.type  as 'df', 
	    r4.type  as 'dsf', 
	    n5.name as 'dsa', 
	    n5.id as 'id', 
	    n5.description as 'desc', 
	    r3.claim_id as 'r3_c', 
	    r4.claim_id as 'r4_c', 
	    n1.name as 'n1', 
	    n2.name as 'n2', 
	    n3.name as 'n3', 
	    n4.name as 'n4', 
	    r4.claim_id as 'r4_claim',
	    project_goals.goal_name, 
	    claim_class_types.type 
    FROM project_goal_class_library_goal as lib_goals 
    INNER JOIN relationships as r1 
        ON lib_goals.library_goal_id = r1.parent 
    INNER JOIN relationships as r2 
        ON r1.child = r2.parent 
    INNER JOIN nodes as n1 
        ON r1.parent = n1.id 
    INNER JOIN nodes as n2 
        ON r1.child = n2.id 
    INNER JOIN nodes as n3 
        ON r2.child = n3.id 
    INNER JOIN project_goals 
        ON lib_goals.project_goal_id = project_goals.id 
    INNER JOIN relationships as r3 
        ON n3.id = r3.child 
    INNER JOIN nodes as n4 
        ON r3.parent = n4.id 
    INNER JOIN relationships as r4 
        ON n4.id = r4.child 
    INNER JOIN nodes as n5 
        ON r4.parent = n5.id 
    INNER JOIN claim_class_types 
        ON lib_goals.goal_class = claim_class_types.id 
    WHERE n1.type='Soft Goal' 
        AND r1.type='helps' 
        AND n2.type='Action' 
        AND n3.type = 'Design Decision' 
        AND n5.type='Soft Goal' 
        AND n4.type='Action' 
        AND project_goals.project_name = '$ActiveProject' 
        AND checked=1 
        AND r1.claim_id NOT LIKE 'c\_%' 
        AND r2.claim_id NOT LIKE 'c\_%' 
        AND r1.claim_id <> 'correlation' 
        AND r2.claim_id <> 'correlation' 
        AND r1.claim_id <> r4.claim_id 
        AND NOT EXISTS (
            SELECT
                sub_lib_goals.library_goal_id 
            FROM project_goal_class_library_goal as sub_lib_goals 
            INNER JOIN project_goals as sub_pg 
               ON sub_lib_goals.project_goal_id = sub_pg.id 
            WHERE library_goal_id = n5.id 
            AND (
                sub_pg.project_name='$ActiveProject' 
                AND sub_lib_goals.checked=1
                ) 
            LIMIT 1) 
        GROUP BY 
            n5.name,
            r4.type 
        ORDER BY
            n5.name;";
}

function select_active_design_alternatives_SQL ($ActiveGoal, $order_by2, $order_dir2) {
	return "select distinct project_goals.goal_name as 'project goal', claim_class_types.type as 'goal class', n1.name as 'Soft Goal', r1.type as 'contribution', n2.name as 'Action', n3.name as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id INNER JOIN claim_class_types on lib_goals.goal_class = claim_class_types.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND lib_goals.project_goal_id = '$ActiveGoal' AND checked=1 AND r1.claim_id NOT LIKE 'c\_%' AND r2.claim_id NOT LIKE 'c\_%' AND r1.claim_id <> 'correlation' AND r2.claim_id <> 'correlation' ORDER BY `$order_by2` $order_dir2;";
}

function active_projmod_goal_slice ($ActiveProjMod, $active_projmod_goal, $action, $filename) {
if ($action == "select"){
$export_boolean = "";
$qualifier = "";
$qualifier2 = ", n5.id";
}
else if ($action == "export"){
$export_boolean = "INTO OUTFILE '/tmp/".$filename.".csv'";
$qualifier = "AND lib_goals.library_goal_id='$active_lib_goal'";
$qualifier2 = "";
}

return "SELECT distinct
	project_goals.goal_name as 'pg',
	claim_class_types.type as 'gc',
	n5.name as 'lg',
	r4.contrib as 'c1',
	n4.name as 'a', 
	r3.contrib as 'c2',
	n3.name as 'd'
FROM projmod_rels AS r1 
INNER JOIN projmod_rels as r2 
	ON (
		r1.child = r2.parent AND 
		r1.projmod_id=r2.projmod_id
	) 
INNER JOIN projmod_rels as r3
	ON (
		r2.child = r3.child AND 
		r2.projmod_id=r3.projmod_id
	) 
INNER JOIN projmod_rels as r4
	ON (
		r3.parent = r4.child AND 
		r3.projmod_id=r4.projmod_id
	) 
INNER JOIN projmod_pcg 
	ON (
		r1.parent = projmod_pcg.lg AND 
		r1.projmod_id=projmod_pcg.projmod_id
	)
INNER JOIN 
    nodes as n1 
    on r1.parent = n1.id 
INNER JOIN nodes as n2 
    on r1.child = n2.id 
INNER JOIN nodes as n3 
    ON r2.child = n3.id
INNER JOIN nodes as n4 
    on r3.parent = n4.id 
INNER JOIN nodes as n5 
    ON r4.parent = n5.id 
INNER JOIN project_goals 
    ON projmod_pcg.pg = project_goals.id 
INNER JOIN claim_class_types 
    on projmod_pcg.gc = claim_class_types.id 
INNER JOIN projmod_nodes as pmn1
	ON (n1.id = pmn1.id AND r1.projmod_id=pmn1.projmod_id)
INNER JOIN projmod_nodes as pmn2
	ON (n2.id = pmn2.id AND r1.projmod_id=pmn2.projmod_id)
INNER JOIN projmod_nodes as pmn3
	ON (n3.id = pmn3.id AND r1.projmod_id=pmn3.projmod_id)
INNER JOIN projmod_nodes as pmn4
	ON (n4.id = pmn4.id AND r1.projmod_id=pmn4.projmod_id)
INNER JOIN projmod_nodes as pmn5
	ON (n5.id = pmn5.id AND r1.projmod_id=pmn5.projmod_id)
WHERE r1.projmod_id=$ActiveProjMod
AND n1.id=$active_projmod_goal
AND pmn1.checked=1
AND pmn2.checked=1
AND pmn3.checked=1
AND pmn4.checked=1
AND pmn5.checked=1
AND r1.contrib <> 'NA'
AND r2.contrib <> 'NA'
AND r3.contrib <> 'NA'
AND r4.contrib <> 'NA'
ORDER BY gc, n5.name, r4.contrib, n4.name, r3.contrib, n3.name ".$export_boolean.";";

}

function export_active_design_alternatives_SQL ($ActiveGoal, $order_by2, $order_dir2, $corr_bool, $timestamp) {
	if($corr_bool){
	$correlations = " ";
	$corr = "_nocorrelations";
	}
	else{
	$correlations = " AND r1.claim_id NOT LIKE 'c\_%' AND r2.claim_id NOT LIKE 'c\_%' AND r1.claim_id <> 'correlation' AND r2.claim_id <> 'correlation' ";
	$corr = "_correlations";
	}
	return "select distinct project_goals.goal_name as 'project goal', claim_class_types.type as 'goal class', n1.name as 'Soft Goal', r1.type as 'contribution', n2.name as 'Action', r2.type as 'contribution2', n3.name as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id INNER JOIN claim_class_types on lib_goals.goal_class = claim_class_types.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND lib_goals.project_goal_id = '$ActiveGoal' AND checked=1".$correlations."ORDER BY `$order_by2` $order_dir2 INTO OUTFILE '/tmp/goal_".$ActiveGoal.$timestamp.$corr.".csv';";
}

function select_design_alternatives_SQL($ActiveProject, $order_by, $order_dir) {
	return "select distinct project_goals.goal_name as 'project goal', claim_class_types.type as 'goal class', n1.name as 'Soft Goal', r1.type as 'contribution', n2.name as 'Action', n3.name as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id INNER JOIN claim_class_types on lib_goals.goal_class = claim_class_types.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND project_goals.project_name = '$ActiveProject' AND checked=1 AND r1.claim_id NOT LIKE 'c\_%' AND r2.claim_id NOT LIKE 'c\_%' AND r1.claim_id <> 'correlation' AND r2.claim_id <> 'correlation' ORDER BY `$order_by` $order_dir;";
}

function export_design_alternatives_SQL($ActiveProject, $order_by, $order_dir, $timestamp) {
	return "select distinct project_goals.goal_name as 'project goal', claim_class_types.type as 'goal class', n1.name as 'Soft Goal', r1.type as 'contribution', n2.name as 'Action', r2.type as 'contribution2', n3.name as 'Design Decision' from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id INNER JOIN claim_class_types on lib_goals.goal_class = claim_class_types.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND project_goals.project_name = '$ActiveProject' AND lib_goals.goal_class='6' AND checked='1' ORDER BY `$order_by` $order_dir INTO OUTFILE '/tmp/".$timestamp."_correlations.csv';";
}

function design_alternatives_no_corr_SQL($Active, $order_by, $order_dir, $timestamp, $action, $type) {
	
	if($type == "goal"){
	$qualifier = "lib_goals.project_goal_id = '".$Active."'";
	}
	elseif ($type=="project"){
	$qualifier = "project_goals.project_name = '".$Active."'";
	}
	
	if($action=="populate"){
	    mysql_query("CREATE TEMPORARY table design_alternatives (id int(10));");
	    $select = "insert into design_alternatives (id) select distinct n3.id";
	    $footer = ";";
	}
	elseif($action=="export"){
	    $select = "
	    select distinct 
	        project_goals.goal_name as 'project goal', 
	        claim_class_types.type as 'goal class', 
	        n1.name as 'Soft Goal', 
	        r1.type as 'contribution', 
	        n2.name as 'Action', 
	        r2.type as 'contribution2', 
	        n3.name as 'Design Decision'";
        $footer = "ORDER BY `$order_by` $order_dir 
    INTO OUTFILE '/tmp/".$timestamp."_nocorrelations.csv';";
    }
	$conditions = "
	FROM project_goal_class_library_goal as lib_goals 
	INNER JOIN relationships as r1 
	    ON lib_goals.library_goal_id = r1.parent 
    INNER JOIN relationships as r2 
        ON r1.child = r2.parent 
    INNER JOIN nodes as n1 
        on r1.parent = n1.id 
    INNER JOIN nodes as n2 
        on r1.child = n2.id 
    INNER JOIN nodes as n3 
        ON r2.child = n3.id 
    INNER JOIN project_goals 
        ON lib_goals.project_goal_id = project_goals.id 
    INNER JOIN claim_class_types 
        on lib_goals.goal_class = claim_class_types.id 
    WHERE 
        n1.type='Soft Goal' 
        AND n2.type='Action' 
        AND n3.type = 'Design Decision' 
        AND ".$qualifier." 
        AND checked='1' 
        AND r1.claim_id NOT LIKE 'c\_%' 
        AND r2.claim_id NOT LIKE 'c\_%' 
        AND r1.claim_id <> 'correlation' 
        AND r2.claim_id <> 'correlation' 
        ";
   return $select.$conditions.$footer;
}

function export_no_corr_designs_corr_relations($Active, $order_by, $order_dir, $timestamp, $type, $export) {
//change to ensure that included correlations are within the range of claim_types selected for the project?
	if($type == "goal"){
	$qualifier = "lib_goals.project_goal_id = '".$Active."'";
	$file_prefix = "goal_".$Active."_";
	}
	else{
	$qualifier = "project_goals.project_name = '".$Active."'";
	$file_prefix = "";
	}
	if($export == true){
	$export_bool = "INTO OUTFILE '/tmp/".$file_prefix.$timestamp."_special.csv'";
	}
	else{
	$export_bool = "";
	}
	$prereqs = design_alternatives_no_corr_SQL($Active, $order_by, $order_dir, $timestamp, "populate", $type);
	mysql_query($prereqs);
	
	return "
    select distinct 
        project_goals.goal_name as 'pg',
        claim_class_types.type as 'gc', 
        n1.name as 'lg', 
        r1.type as 'c1',
        n2.name as 'a', 
        r2.type as 'c2', 
        n3.name as 'd',
        n1.id as 'lg_id',
        n2.id as 'a_id',
        n3.id as 'd_id',
        lib_goals.project_goal_id as 'pg_id',
        lib_goals.goal_class as 'gc_id',
        r1.claim_id as 'r1_cid',
        r2.claim_id as 'r2_cid'
    FROM 
        project_goal_class_library_goal as lib_goals 
    INNER JOIN 
        relationships as r1 
        ON lib_goals.library_goal_id = r1.parent 
    INNER JOIN 
        relationships as r2 
        ON r1.child = r2.parent 
    INNER JOIN 
        nodes as n1 
        on r1.parent = n1.id 
    INNER JOIN nodes as n2 
        on r1.child = n2.id 
    INNER JOIN nodes as n3 
        ON r2.child = n3.id 
    INNER JOIN project_goals 
        ON lib_goals.project_goal_id = project_goals.id 
    INNER JOIN claim_class_types 
        on lib_goals.goal_class = claim_class_types.id 
    WHERE n1.type='Soft Goal' 
        AND n2.type='Action' 
        AND n3.type = 'Design Decision' 
        AND EXISTS (select id from design_alternatives where design_alternatives.id = n3.id)
    AND ".$qualifier."  
    AND checked='1'
     ORDER BY `$order_by` $order_dir $export_bool;
    ";
}

function node_claim_classes_SQL($node_id){
	return "select claim_class_types.id, claim_class_types.type from nodes inner join relationships on nodes.id = relationships.parent inner join claim_classes on relationships.claim_id = claim_classes.claim_id inner join claim_class_types on claim_classes.class_type = claim_class_types.id where nodes.id='$node_id' group by claim_class_types.type";
}

//INSERTS
function insert_project_goal_classes_SQL ($project_goal_class_types_values) {
	return "
		INSERT IGNORE INTO 
			project_goal_classes (`project_goal_id`, `goal_class_id`) 			VALUES $project_goal_class_types_values;";
}

function projmod_retrieve ($projmod_id, $order_by, $order_dir){
	return "
		SELECT distinct
		    project_goals.goal_name as 'pg',
		    claim_class_types.type as 'gc', 
			n1.name as 'lg', 
			r1.contrib as 'c1', 
			n2.name as 'a', 
			r2.contrib as 'c2', 
			n3.name as 'd',
		    n1.id as 'lg_id',
		    n2.id as 'a_id',
		    n3.id as 'd_id',
		    projmod_pcg.pg as 'pg_id',
		    projmod_pcg.gc as 'gc_id',
		    r1.claim_lib as 'r1_cid',
		    r2.claim_lib as 'r2_cid',
		    r1.contrib_lib as 'c1_lib',
		    r2.contrib_lib as 'c2_lib',
		    pmn1.checked as 'n1_checked',
		    pmn2.checked as 'n2_checked',
		    pmn3.checked as 'n3_checked'
		FROM projmod_rels AS r1 
		INNER JOIN projmod_rels as r2 
			ON (
				r1.child = r2.parent AND 
				r1.projmod_id=r2.projmod_id
			) 
		INNER JOIN projmod_pcg 
			ON (
				r1.parent = projmod_pcg.lg AND 
				r1.projmod_id=projmod_pcg.projmod_id
			)
		INNER JOIN 
		    nodes as n1 
		    on r1.parent = n1.id 
		INNER JOIN nodes as n2 
		    on r1.child = n2.id 
		INNER JOIN nodes as n3 
		    ON r2.child = n3.id 
		INNER JOIN project_goals 
		    ON projmod_pcg.pg = project_goals.id 
		INNER JOIN claim_class_types 
		    on projmod_pcg.gc = claim_class_types.id 
	   INNER JOIN projmod_nodes as pmn1
	    	ON (n1.id = pmn1.id AND pmn1.projmod_id=r1.projmod_id)
    	INNER JOIN projmod_nodes as pmn2
	    	ON (n2.id = pmn2.id AND pmn2.projmod_id=r1.projmod_id)
    	INNER JOIN projmod_nodes as pmn3
	    	ON (n3.id = pmn3.id AND pmn3.projmod_id=r1.projmod_id)
		WHERE r1.projmod_id=$projmod_id
		ORDER BY `$order_by` $order_dir;
		";
}
function projmod_retrieve2 ($projmod_id, $order_by, $order_dir){
	return "
		SELECT distinct
		    project_goals.goal_name as 'pg',
		    claim_class_types.type as 'gc', 
			n1.name as 'lg', 
			r1.contrib as 'c1', 
			n2.name as 'a', 
			r2.contrib as 'c2', 
			n3.name as 'd',
		    n1.id as 'lg_id',
		    n2.id as 'a_id',
		    n3.id as 'd_id',
		    projmod_pcg.pg as 'pg_id',
		    projmod_pcg.gc as 'gc_id',
		    r1.claim_lib as 'r1_cid',
		    r2.claim_lib as 'r2_cid',
		    r1.contrib_lib as 'c1_lib',
		    r2.contrib_lib as 'c2_lib',
		    pmn1.checked as 'n1_checked',
		    pmn2.checked as 'n2_checked',
		    pmn3.checked as 'n3_checked'
		FROM projmod_rels AS r1 
		INNER JOIN projmod_rels as r2 
			ON (
				r1.child = r2.parent AND 
				r1.projmod_id=r2.projmod_id
			) 
		INNER JOIN projmod_pcg 
			ON (
				r1.parent = projmod_pcg.lg AND 
				r1.projmod_id=projmod_pcg.projmod_id
			)
		INNER JOIN 
		    nodes as n1 
		    on r1.parent = n1.id 
		INNER JOIN nodes as n2 
		    on r1.child = n2.id 
		INNER JOIN nodes as n3 
		    ON r2.child = n3.id 
		INNER JOIN project_goals 
		    ON projmod_pcg.pg = project_goals.id 
		INNER JOIN claim_class_types 
		    on projmod_pcg.gc = claim_class_types.id 
	   INNER JOIN projmod_nodes as pmn1
	    	ON (n1.id = pmn1.id AND pmn1.projmod_id=r1.projmod_id)
    	INNER JOIN projmod_nodes as pmn2
	    	ON (n2.id = pmn2.id AND pmn2.projmod_id=r1.projmod_id)
    	INNER JOIN projmod_nodes as pmn3
	    	ON (n3.id = pmn3.id AND pmn3.projmod_id=r1.projmod_id)
		WHERE r1.projmod_id=$projmod_id
		ORDER BY `$order_by` $order_dir LIMIT 50;
		";
}
function projmod_report($projmod_id, $order_by, $order_dir){
	return "SELECT distinct
		    n3.name as 'd',
		    claim_class_types.type as 'gc',
		    n1.name as 'lg',
		    r1.contrib as 'c1', 
			 n2.name as 'a', 
			 r2.contrib as 'c2',
		    n1.id as 'lg_id',
		    n2.id as 'a_id',
		    n3.id as 'd_id',
		    n3.description as 'd_desc',
		    projmod_pcg.pg as 'pg_id',
		    projmod_pcg.gc as 'gc_id',
		    r1.claim_lib as 'r1_cid',
		    r2.claim_lib as 'r2_cid',
		    r1.contrib_lib as 'c1_lib',
		    r2.contrib_lib as 'c2_lib',
		    pmn1.checked as 'n1_checked',
		    pmn2.checked as 'n2_checked',
		    pmn3.checked as 'n3_checked',
		    publications.authors_full as 'author',
		    publications.yearpub as 'year',
		    publications.author as 'pubid'
		FROM projmod_rels AS r1 
		INNER JOIN projmod_rels as r2 
			ON (
				r1.child = r2.parent AND 
				r1.projmod_id=r2.projmod_id
			) 
		INNER JOIN projmod_pcg 
			ON (
				r1.parent = projmod_pcg.lg AND 
				r1.projmod_id=projmod_pcg.projmod_id
			)
		INNER JOIN 
		    nodes as n1 
		    on r1.parent = n1.id 
		INNER JOIN nodes as n2 
		    on r1.child = n2.id 
		INNER JOIN nodes as n3 
		    ON r2.child = n3.id 
		INNER JOIN project_goals 
		    ON projmod_pcg.pg = project_goals.id 
		INNER JOIN claim_class_types 
		    on projmod_pcg.gc = claim_class_types.id 
	   INNER JOIN projmod_nodes as pmn1
	    	ON (n1.id = pmn1.id AND pmn1.projmod_id=r1.projmod_id)
    	INNER JOIN projmod_nodes as pmn2
	    	ON (n2.id = pmn2.id AND pmn2.projmod_id=r1.projmod_id)
    	INNER JOIN projmod_nodes as pmn3
	    	ON (n3.id = pmn3.id AND pmn3.projmod_id=r1.projmod_id)
    	INNER JOIN claims as c1 on r1.claim_lib=c1.claim_id
    	INNER JOIN claims as c2 on r2.claim_lib=c2.claim_id
    	INNER JOIN publications on cp2.publication = publications.author
		WHERE r1.projmod_id=$projmod_id
	

		AND r1.contrib<>'NA'
		AND r2.contrib<>'NA'
		AND pmn1.checked=1
		AND pmn2.checked=1
		AND pmn3.checked=1
		ORDER BY $order_by $order_dir;";
	}
function active_projmod_complete($projmod_id, $order_by, $order_dir, $filename){
	return "
		SELECT distinct
		    project_goals.goal_name as 'pg',
		    claim_class_types.type as 'gc', 
			n1.name as 'lg', 
			r1.contrib as 'c1', 
			n2.name as 'a', 
			r2.contrib as 'c2', 
			n3.name as 'd'
		FROM projmod_rels AS r1 
		INNER JOIN projmod_rels as r2 
			ON (
				r1.child = r2.parent AND 
				r1.projmod_id=r2.projmod_id
			) 
		INNER JOIN projmod_pcg 
			ON (
				r1.parent = projmod_pcg.lg AND 
				r1.projmod_id=projmod_pcg.projmod_id
			)
		INNER JOIN 
		    nodes as n1 
		    on r1.parent = n1.id 
		INNER JOIN nodes as n2 
		    on r1.child = n2.id 
		INNER JOIN nodes as n3 
		    ON r2.child = n3.id 
		INNER JOIN project_goals 
		    ON projmod_pcg.pg = project_goals.id 
		INNER JOIN claim_class_types 
		    on projmod_pcg.gc = claim_class_types.id 
	   INNER JOIN projmod_nodes as pmn1
	    	ON (n1.id = pmn1.id AND pmn1.projmod_id=r1.projmod_id)
    	INNER JOIN projmod_nodes as pmn2
	    	ON (n2.id = pmn2.id AND pmn2.projmod_id=r1.projmod_id)
    	INNER JOIN projmod_nodes as pmn3
	    	ON (n3.id = pmn3.id AND pmn3.projmod_id=r1.projmod_id)
		WHERE r1.projmod_id=$projmod_id
		AND r1.contrib<>'NA'
		AND r2.contrib<>'NA'
		AND pmn1.checked=1
		AND pmn2.checked=1
		AND pmn3.checked=1
		ORDER BY `$order_by` $order_dir
		INTO OUTFILE '/tmp/".$filename.".csv';
		";
}
?>
