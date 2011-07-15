select distinct 
	project_goals.goal_name as 'project goal', 
	lib_goals.goal_class as 'goal class', 
	n1.name as 'Soft Goal', 
	r1.type as 'contribution', 
	n2.name as 'Action', 
	n3.name as 'Design Decision',
	n4.name as 'action (c)',
	r4.type as 'contritbution (c)',
	n5.name as 'Goal (c)' 
from project_goal_class_library_goal as lib_goals 
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
RIGHT JOIN relationships as r3 
	on r3.child = n3.id
RIGHT JOIN relationships as r4
	on r3.parent = r4.child
RIGHT JOIN nodes as n4
	on r3.parent = n4.id
RIGHT JOIN nodes as n5
	on r4.parent = n5.id
WHERE n1.type='Soft Goal' 
	AND n2.type='Action' 
	AND n3.type = 'Design Decision' 
	AND project_goals.project_name = 'Collaboratorium'
	AND NOT EXISTS (select library_goal_id from project_goal_class_library_goal where library_goal_id = n5.id AND project_goals.project_name = 'Collaboratorium' LIMIT 1) 
	AND checked='1';
	
	
select distinct  project_goals.goal_name as 'project goal',  lib_goals.goal_class as 'goal class',  n1.name as 'Soft Goal',  r1.type as 'contribution',  n2.name as 'Action',  n3.name as 'Design Decision', n4.name as 'action (c)', r4.type as 'contritbution (c)', n5.name as 'Goal (c)'  from project_goal_class_library_goal as lib_goals  INNER JOIN relationships as r1  ON lib_goals.library_goal_id = r1.parent  INNER JOIN relationships as r2  ON r1.child = r2.parent  INNER JOIN nodes as n1  on r1.parent = n1.id  INNER JOIN nodes as n2  on r1.child = n2.id  INNER JOIN nodes as n3  ON r2.child = n3.id  INNER JOIN project_goals  ON lib_goals.project_goal_id = project_goals.id  RIGHT JOIN relationships as r3  on r3.child = n3.id RIGHT JOIN relationships as r4 on r3.parent = r4.child RIGHT JOIN nodes as n4 on r3.parent = n4.id RIGHT JOIN nodes as n5 on r4.parent = n5.id WHERE n1.type='Soft Goal'  AND n2.type='Action'  AND n3.type = 'Design Decision'  AND project_goals.project_name = 'Collaboratorium' AND NOT EXISTS (select library_goal_id from project_goal_class_library_goal where library_goal_id = n5.id AND project_goals.project_name = 'Collaboratorium' LIMIT 1)  AND checked='1' order by project_goals.goal_name, lib_goals.goal_class, n1.name;

--Fresh
select distinct n1.name, n2.name, n3.name as 'Design Decision', r3.type, n4.name, r4.type, n5.name, r4.claim_id from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id INNER JOIN relationships as r3 on n3.id = r3.child INNER JOIN nodes as n4 on r3.parent = n4.id INNER JOIN relationships as r4 on n4.id = r4.child INNER JOIN nodes as n5 on r4.parent = n5.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND lib_goals.project_goal_id = '27' AND checked=1 AND r1.claim_id NOT LIKE 'c\_%' AND r2.claim_id NOT LIKE 'c\_%' AND r1.claim_id <> 'correlation' AND r2.claim_id <> 'correlation' AND r1.claim_id <> r4.claim_id AND NOT EXISTS (select sub_lib_goals.library_goal_id from project_goal_class_library_goal as sub_lib_goals where library_goal_id = n5.id limit 1) order by n3.name, n4.name, n5.name;

--Freshing
select distinct n1.name, n3.name as 'Design Decision', r3.type, n4.name, r4.type, n5.name, r4.claim_id from project_goal_class_library_goal as lib_goals INNER JOIN relationships as r1 ON lib_goals.library_goal_id = r1.parent INNER JOIN relationships as r2 ON r1.child = r2.parent INNER JOIN nodes as n1 on r1.parent = n1.id INNER JOIN nodes as n2 on r1.child = n2.id INNER JOIN nodes as n3 ON r2.child = n3.id INNER JOIN project_goals ON lib_goals.project_goal_id = project_goals.id INNER JOIN relationships as r3 on n3.id = r3.child INNER JOIN nodes as n4 on r3.parent = n4.id INNER JOIN relationships as r4 on n4.id = r4.child INNER JOIN nodes as n5 on r4.parent = n5.id WHERE n1.type='Soft Goal' AND n2.type='Action' AND n3.type = 'Design Decision' AND n5.type='Soft Goal' AND n4.type='Action' AND lib_goals.project_goal_id = '27' AND checked=1 AND r1.claim_id NOT LIKE 'c\_%' AND r2.claim_id NOT LIKE 'c\_%' AND r1.claim_id <> 'correlation' AND r2.claim_id <> 'correlation' AND r1.claim_id <> r4.claim_id AND NOT EXISTS (select sub_lib_goals.library_goal_id from project_goal_class_library_goal as sub_lib_goals INNER JOIN project_goals as sub_pg ON sub_lib_goals.project_goal_id = sub_pg.id where library_goal_id = n5.id AND sub_pg.project_name='Collaboratorium' limit 1) group by n5.name,r4.type order by n5.name, n4.type, n4.name, n3.name;
