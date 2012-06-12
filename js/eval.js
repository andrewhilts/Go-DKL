//Based on a 4-level goal graph (goal class > library goal > action > design feature in the implemented case)
$(document).ready(function(){
//Main evaluation function. See line 160 for the cases which launch this function. Other functions of interest at lines 206,221,240.
function tree_propagate(start, level){
    contribution = start.value;
    if(contribution == "N/A"){
    $(start).parent().children("span").addClass("alternatives_not_applicable");
    }
    else{
    $(start).parent().children("span").removeClass("alternatives_not_applicable");
    }
    graph_parent = $(start).parent().parent().parent();
    siblings = $(graph_parent).children().children().children("select");

    contributions = siblings.length;
    contribution=0;
    
    for(x=0; x<siblings.length; x++){
        y = siblings[x].value;
        checked = $(siblings[x]).parent().children("input[type=checkbox]").attr("checked");
        if(checked){
            node_value = $(siblings[x]).parent().children('span').get(0).className;
            if(node_value){
                //alert(node_value);
                if(node_value=="hurts"){
                 switch(y){
	                case "AND":
	                    contribution = contribution-2;
	                    break;
	                case "OR":
	                    contribution = contribution-2;
	                    break;
	                case "++":
	                    contribution = contribution-2;
	                    break;
	                case "+":
	                    contribution = contribution-1;
	                    break;
	                case "?":
	                    contribution = contribution+0;
	                    break;
	                case "-":
	                    contribution = contribution+1;
	                    break;
	                case "--":
	                    contribution = contribution+2;
	                    break;
                    case "N/A":
	                    contribution = contribution+0;
	                    if(contributions==1){
                        }
                        else{
                        contributions = contributions-1;
                        }
	                    break;
	                   
                }   
                }
                else if(node_value=="conflict"){
                    contribution=contribution;
                }
                else{
                 switch(y){
                    case "AND":
                        contribution = contribution+2;
                        break;
                    case "OR":
                        contribution = contribution+2;
                        break;
                    case "++":
                        contribution = contribution+2;
                        break;
                    case "+":
                        contribution = contribution+1;
                        break;
                    case "?":
                        contribution = contribution+0;
                        break;
                    case "-":
                        contribution = contribution-1;
                        break;
                    case "--":
                        contribution = contribution-2;
                        break;
                    case "N/A":
	                    contribution = contribution+0;
	                    if(contributions==1){
                        }
                        else{
                        contributions = contributions-1;
                        }
	                    break;
                }
                }
            }
            else{
                switch(y){
                    case "AND":
                        contribution = contribution+2;
                        break;
                    case "OR":
                        contribution = contribution+2;
                        break;
                    case "++":
                        contribution = contribution+2;
                        break;
                    case "+":
                        contribution = contribution+1;
                        break;
                    case "?":
                        contribution = contribution+0;
                        break;
                    case "-":
                        contribution = contribution-1;
                        break;
                    case "--":
                        contribution = contribution-2;
                        break;
                    case "N/A":
	                    contribution = contribution+0;
	                    if(contributions==1){
                        }
                        else{
                        contributions = contributions-1;
                        }
                        break;
                }
            }
        }
        else{
        if(contributions==1){
        }
        else{
        contributions = contributions-1;
        }
        }
    }

    parent_class = graph_parent.children("span"); 

    satisfaction_rate = contribution/contributions*100;

    if(satisfaction_rate >= 30){
    parent_class.get(0).className="helps";
    }
    else if(satisfaction_rate >= -30){
    parent_class.get(0).className="conflict";
    }
    else if(satisfaction_rate <= -30){
    parent_class.get(0).className="hurts";
    
    }
    //parent_class.get(0).innerHTML+=satisfaction_rate+"_"+contributions+"__";
    if(level==3){
        //alert(parent_class.children("select").get(0).value);
        tree_propagate(graph_parent.children("select").get(0),2);
    }
}

leaf_nodes=$('#alternatives_analysis_container').find(".alternatives_L3").children('select');
last_trunks = $('#alternatives_analysis_container').find(".alternatives_L3");
l=last_trunks.length;
m=leaf_nodes.length;

var first_leaf = new Array();
var j = 0;
for(i=0;i<l;i++){
    y=last_trunks[i];
    first_leaf[i] = $(y).children('select').get(0);
}

//initial launch of the evaluation function
for(i=0;i<first_leaf.length;i++){
z=first_leaf[i];
tree_propagate(z,3);
}

init_checks = $('#alternatives_analysis_container').find('input[type=checkbox]');
for(i=0;i<init_checks.length;i++){
	if($(init_checks[i]).attr('checked')){
		if($(init_checks[i]).parent().children('select').value=="N/A"){
			$(init_checks[i]).parent().children("span").addClass("alternatives_not_applicable")
		}
	}
	else{
	$(init_checks[i]).parent().children("span").addClass("alternatives_deselected");
	}
}

function get_level(element){
       if($(element).parent().hasClass("alternatives_L2")){level=2;}
  else if($(element).parent().hasClass("alternatives_L3")){level=3;}
  else{level=1;}
return level
}

function update_status(type, change, number){
  $("#alternatives_status").text("");
  $("#alternatives_status").removeClass("popop");
  window.setTimeout(function() {
    $("#alternatives_status").text(number+" instances of this "+type+" "+change);
    $("#alternatives_status").addClass("popop");
  }, 200);
}

//Launch evaluation function whenever the selection value (contribution type) is changed for a relationship
$('#alternatives_analysis_container').find("select").change(function() {
  level = get_level(this);
  switch(level){
    case 2:
        starting_node= $(this).parent().children("ul").children("li").children("select");
        starting_node= starting_node.get(0);
        tree_propagate(starting_node, 3);
        break;
    case 3:
        starting_node = this;
        tree_propagate(starting_node, 3);
        break;
  }
  new_value = $(this).attr('value');
  //change value for each selection value that is an instance of the same relationship (eg various instances of the relationship n1 helps n2)
  same_nodes = $('#alternatives_analysis_container').find('select[name='+this.name+']');
  $(same_nodes).attr('value',new_value);
  update_status("contribution relationship","changed to"+new_value,same_nodes.length);
});
$('#alternatives_analysis_container').find('input[type=checkbox]').change( 
    function() { 
     level = get_level(this);
  switch(level){
    case 2:
        starting_node= $(this).parent().children("ul").children("li").children("select");
        starting_node= starting_node.get(0);
        tree_propagate(starting_node, 3);
        break;
    case 3:
        starting_node = this;
        tree_propagate(starting_node, 3);
        break;
  }
  //Similar find same node for in this individual node selections or deselections. When one instance of n1 is deselected, all instances will be.
  
  //same_node_var = "'input[type=checkbox]"+"."+this.name+"'";
  same_nodes = $('#alternatives_analysis_container').find('input[type=checkbox][id='+this.id+']');
  if($(this).attr('checked')){
  $(same_nodes).attr('checked', true);
  update_status("node","selected",same_nodes.length);
  $(same_nodes).parent().children("span").removeClass("alternatives_deselected");
  }
  else{
  $(same_nodes).attr('checked', false);
  update_status("node","deselected",same_nodes.length);
  $(same_nodes).parent().children("span").addClass("alternatives_deselected");
  }
  
    } 
);

});
