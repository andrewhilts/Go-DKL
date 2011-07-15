import csv, os, sys, os.path
def csv_2_sql(inputted_csv_file, tbl, insert_sql):
	
	outfile = tbl+'.sql'
	csv_file = open(inputted_csv_file,"r")
	reader = csv.reader(csv_file, delimiter=',')
	row = reader.next()
	sql_statements = []
	i=0
	sex=0
	for row in reader:
	        sql = []
		#if i%10==0 : print tbl+" row "+str(i)
		j=0
		for data in row:
			print data
			#data = formatting(tblname, data, i)
			if data =="+":
				if row[j+1] != "":
				   le_query = "INSERT INTO relationships2 (child, type, parent, claim_id)  SELECT n1.id,'hurts',n2.id,CONCAT('correlation',CONCAT('_',claim_class_types.type)) FROM nodes as n1, nodes as n2 INNER JOIN relationships on n2.id=parent INNER JOIN claim_classes on claim_classes.claim_id = relationships.claim_id INNER JOIN claim_class_types on class_type=claim_class_types.id WHERE n1.name = '"+row[0].replace("'","\'")+"' AND n2.name='"+row[j+1].replace("'","\'")+"';\n"
				   sql.append(le_query)
				   sex=1
			elif data=="-":
				if row[j+1] != "":
					sql.append("INSERT INTO relationships2 (child, type, parent, claim_id)  SELECT n1.id,'hurts',n2.id,CONCAT('correlation',CONCAT('_',claim_class_types.type)) FROM nodes as n1, nodes as n2 INNER JOIN relationships on n2.id=parent INNER JOIN claim_classes on claim_classes.claim_id = relationships.claim_id INNER JOIN claim_class_types on class_type=claim_class_types.id WHERE n1.name = '"+row[0].replace("'","\'")+"' AND n2.name='"+row[j+1].replace("'","\'")+"';\n")
					sex=1
			elif data=="$$":
				if row[j+1] != "":
					sql.append("INSERT INTO relationships2 (child, type, parent, claim_id)  SELECT n1.id,'hurts',n2.id,CONCAT('correlation',CONCAT('_',claim_class_types.type)) FROM nodes as n1, nodes as n2 INNER JOIN relationships on n2.id=parent INNER JOIN claim_classes on claim_classes.claim_id = relationships.claim_id INNER JOIN claim_class_types on class_type=claim_class_types.id WHERE n1.name = '"+row[0].replace("'","\'")+"' AND n2.name='"+row[j+1].replace("'","\'")+"';\n")
					sex=1
			j+=1
		if sex==1:
			sql = "".join(sql)[:-1]
			sql_statements.append(sql+"\n")
			sex=0
		i+=1
	
	#sql_values = sql_values[:-2]

	
	sqlfile = open(outfile,'w')
	sqlfile.write("".join(sql_statements))
	sqlfile.close
	print "'"+inputted_csv_file+"' data parsed and added as VALUES of SQL INSERT statement in '"+tbl+".sql'"
	
csv_2_sql("correlations-dd-act.csv", "dd_a", "INSERT INTO `relationships` (child, type, parent, claim_id) VALUES ")
