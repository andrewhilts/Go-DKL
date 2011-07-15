	function showinfo(id) {
	alert(id);
	element = document.getElementById(id);
	element.removeClass("hidden");
	element.addClass("visible");
	}

	function stylify(element, style) {
		$(element).addClass(style);
	}
	function removeStylify(element, style) {
		$(element).removeClass(style);
	}			
	function columnify (column, j, column4) {
		for(var i=0; i<column.length; i++){
			k = j-1;
			thisrow = column[i];
			lastrow = column[j];
			lastlastrow = column[k];
			if(j == "44") {
				var parent = column[i].parentNode;
				var style = column[i].innerHTML;
				if(parent.className == "alternate"){
					stylify(parent, "alt"+style);
				}
				else {
					stylify(parent, style);
				}
			}
			/*else if(i>1){
				if(thisrow.innerHTML == lastrow.innerHTML){
				var thisstyle = column4[i].innerHTML;
				var laststyle = column4[j].innerHTML;
					if(lastrow.innerHTML == lastlastrow.innerHTML){
						stylify(thisrow, "group"+thisstyle);
						stylify(lastrow, "group"+laststyle);
						stylify(thisrow, "bottom");
						removeStylify(lastrow, "bottom");
						stylify(lastrow, "middle");
						}
					else{
						stylify(thisrow, "group"+thisstyle);
						stylify(lastrow, "group"+laststyle);
						stylify(thisrow, "bottom");
						stylify(lastrow, "top");
					}
				}
			j++;
			}*/
		}
	j=1;
	}
function columnclicky (column, j, column4) {
	for(var i=0; i<column.length; i++){
		thisrow = column[i];
		thisrow.onclick=function(){alert(thisrow.innerHTML);}
	}
}
function query_link(class_type, order_by, order_direction, column) {
oForm = document.forms[column];
if(class_type!=""){
oForm.elements["link_class_type"].value= class_type;
oForm.elements["proto_dd"].value=oForm.elements["proto_dd"].value;
}
oForm.elements["order_by"].value= order_by;
oForm.elements["order_dir"].value= order_direction;
oForm.elements["design_name"].value=oForm.elements["design_name"].value;
oForm.submit();
}
function query_linky(id, order_by, order_direction) {
oForm = document.forms[1];
if(id=="active"){
oForm.elements["order_by2"].value= order_by;
oForm.elements["order_dir2"].value= order_direction;
}
else if(id=="complete"){
oForm.elements["order_by"].value= order_by;
oForm.elements["order_dir"].value= order_direction;
}
oForm.submit();
}
