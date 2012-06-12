$(document).ready(function(){
  var results = $("#results");
  var orderUP = $(".orderUP").parent();
  orderUP.addClass("orderUP");
  var orderDOWN = $(".orderDOWN").parent();
  orderDOWN.addClass("orderDOWN");
  $('#results tr:nth-child(odd)').addClass('alternate');
  $('#report tr:nth-child(odd)').addClass('alternate');
  $('#correlation_list div.correlation_helps:nth-child(odd)').addClass('althelps');
  $('#correlation_list div.correlation_helps:nth-child(even)').addClass('helps');
  $('#correlation_list div.correlation_hurts:nth-child(odd)').addClass('althurts');
  $('#correlation_list div.correlation_hurts:nth-child(even)').addClass('hurts');
  $('#correlation_list div.correlation_conflict:nth-child(odd)').addClass('altconflict');
  $('#correlation_list div.correlation_conflict:nth-child(even)').addClass('conflict');
  $('#correlation_list div.correlation_conflict:nth-child(odd)').addClass('altconflict');
  $('#alternatives_analysis_container p.helps:nth-child(odd)').addClass('althelps');
  $('#alternatives_analysis_container p.hurts:nth-child(odd)').addClass('althurts');
  var rows = results.children("tbody").children("tr");
  var cells = rows.children("td");
  
}); 



window.onload=function(){
selectmenu=document.getElementById("project_goals");
claimmenu=document.getElementById("claim_classes");
libgoaldiv=document.getElementById("library_goals");
goalclassdiv=document.getElementById("goal_classes");
//selectmenu.onchange=function(){goalclassdiv.className="visible";claimmenu.selectedIndex=-1};
};
var last = "";
var last_class = "";

function toggle(id, this_class, clicker) {
button = document.getElementById(clicker);
button_position = getElementAbsolutePos(button);
button_position_y = button_position.y;
button_box = document.getElementById(clicker).parentNode.parentNode;
button_box_position = getElementAbsolutePos(button_box);
button_box_position_y = button_box_position.y
button_box_height = button_box.offsetHeight;
button_box_bottom = button_box_height + button_box_position_y;
description = document.getElementById(id).parentNode;
description_position = getElementAbsolutePos(description);
description_position_y = description_position.y;
description_height = description.offsetHeight;
Yposition = button_position_y-description_position_y;
desc= document.getElementById(id);
desc_height = $(desc).height();
description_bottom = desc_height + Yposition + button_box_position_y;

if(button_box_height>desc_height){
if(button_box_bottom<description_bottom){
diff = description_bottom - button_box_bottom;
Yposition = Yposition - diff;
}
}
toggle_element = document.getElementById(id).className;
if(last !== ""){
if(last !== id){
document.getElementById(last).className='hidden';
}
}

if (toggle_element=="hidden"){
document.getElementById(id).className='visible';
document.getElementById(id).style.top=Yposition;
}
else{
document.getElementById(id).className='hidden';
}

last = id;
lasr_class = this_class;
}

var laster = "";
var laster_class = "";

function toggler(id, this_class) {
toggler_element = document.getElementById(id).className;
if(laster !== ""){
if(laster !== id){
document.getElementById(laster).className='hidden';
}
}

if (toggler_element=="hidden"){
document.getElementById(id).className='visible';
}
else{
document.getElementById(id).className='hidden';
}

laster = id;
laster_class = this_class;
}


var last2 = "";
var last_class2 = "";

function toggle2(id, this_class) {
toggle_element2 = document.getElementById(id).className;
if(last2 !== ""){
if(last_class2 == this_class){
if(last2 !== id){
document.getElementById(last2).className='passive';
document.getElementById(last2).innerHTML='&#9654;';
}
}
}

if (toggle_element2=="passive"){
document.getElementById(id).className='act';
document.getElementById(id).innerHTML='&#9664;';
}
else{
document.getElementById(id).className='passive';
document.getElementById(id).innerHTML='&#9654;';
}

last2 = id;
last_class2 = this_class;
}

var last_c = '1_checklist_div';
var last_ch = '1_checklist_h4';
var last_class_c = "";

function toggle3(id, this_class) {
toggle_element = document.getElementById(id).className;
if(last_c !== ""){
if(last_c !== id){
document.getElementById(last_c).className='hidden';
document.getElementById(last_ch).className='passive';
}
}

if (toggle_element=="hidden"){
document.getElementById(id).className='visible';
}
else{
document.getElementById(id).className='hidden';
}

last_c = id;
last_class_c = this_class;
}

var last4 = "";
var last_class4 = "";

function toggle4(id, this_class) {
toggle_element4 = document.getElementById(id).className;
if(last4 !== ""){
if(last_class4 == this_class){
if(last4 !== id){
document.getElementById(last4).className='passive';
}
}
}

if (toggle_element4=="passive"){
document.getElementById(id).className='act';
}
else{
document.getElementById(id).className='passive';
}

last4 = id;
last_class4 = this_class;
}
var __isIE =  navigator.appVersion.match(/MSIE/);
var __userAgent = navigator.userAgent;
var __isFireFox = __userAgent.match(/firefox/i);
var __isFireFoxOld = __isFireFox && 
   (__userAgent.match(/firefox\/2./i) || __userAgent.match(/firefox\/1./i));
var __isFireFoxNew = __isFireFox && !__isFireFoxOld;

function __parseBorderWidth(width) {
    var res = 0;
    if (typeof(width) == "string" && width != null 
                && width != "" ) {
        var p = width.indexOf("px");
        if (p >= 0) {
            res = parseInt(width.substring(0, p));
        }
        else {
             //do not know how to calculate other 
             //values (such as 0.5em or 0.1cm) correctly now
             //so just set the width to 1 pixel
            res = 1; 
        }
    }
    return res;
}

//returns border width for some element
function __getBorderWidth(element) {
    var res = new Object();
    res.left = 0; res.top = 0; res.right = 0; res.bottom = 0;
    if (window.getComputedStyle) {
        //for Firefox
        var elStyle = window.getComputedStyle(element, null);
        res.left = parseInt(elStyle.borderLeftWidth.slice(0, -2));  
        res.top = parseInt(elStyle.borderTopWidth.slice(0, -2));  
        res.right = parseInt(elStyle.borderRightWidth.slice(0, -2));  
        res.bottom = parseInt(elStyle.borderBottomWidth.slice(0, -2));  
    }
    else {
        //for other browsers
        res.left = __parseBorderWidth(element.style.borderLeftWidth);
        res.top = __parseBorderWidth(element.style.borderTopWidth);
        res.right = __parseBorderWidth(element.style.borderRightWidth);
        res.bottom = __parseBorderWidth(element.style.borderBottomWidth);
    }
   
    return res;
}

//returns absolute position of some element within document
function getElementAbsolutePos(element) {
    var res = new Object();
    res.x = 0; res.y = 0;
    if (element !== null) {
        res.x = element.offsetLeft;
        res.y = element.offsetTop;
        
        var offsetParent = element.offsetParent;
        var parentNode = element.parentNode;
        var borderWidth = null;

        while (offsetParent != null) {
            res.x += offsetParent.offsetLeft;
            res.y += offsetParent.offsetTop;
            
            var parentTagName = offsetParent.tagName.toLowerCase();    

            if ((__isIE && parentTagName != "table") || 
                (__isFireFoxNew && parentTagName == "td")) {            
                borderWidth = __getBorderWidth(offsetParent);
                res.x += borderWidth.left;
                res.y += borderWidth.top;
            }
            
            if (offsetParent != document.body && 
                offsetParent != document.documentElement) {
                res.x -= offsetParent.scrollLeft;
                res.y -= offsetParent.scrollTop;
            }

            //next lines are necessary to support FireFox problem with offsetParent
               if (!__isIE) {
                while (offsetParent != parentNode && parentNode !== null) {
                    res.x -= parentNode.scrollLeft;
                    res.y -= parentNode.scrollTop;
                    
                    if (__isFireFoxOld) {
                        borderWidth = __getBorderWidth(parentNode);
                        res.x += borderWidth.left;
                        res.y += borderWidth.top;
                    }
                    parentNode = parentNode.parentNode;
                }    
            }

            parentNode = offsetParent.parentNode;
            offsetParent = offsetParent.offsetParent;
        }
    }
    return res;
}

var last_alt = "";
var last_alt_class = "";

function toggle_alts(id,this_class) {
switch(this_class){
    case "alternatives_L1":
        active = $(id).parent().parent().children('.alternatives_L1');
        last = $(last_alt).parent().parent().children('.alternatives_L1');
    break;
    case "alternatives_L2":
        active = $(id).parent().parent().children('.alternatives_L2');
        last = $(last_alt).parent().children('.alternatives_L2');
    break;
    case "alternatives_L3":
        active = $(id).parent().parent().children('.alternatives_L3');
        last = $(last_alt).parent().parent().children('.alternatives_L3');
    break;
}
if(last_alt !== ""){
    if(last_alt !== id){
        if($(last).hasClass('hidden')){
        }
        else{
            $(last).addClass('hidden');
            $(last_alt).removeClass('act');
        }
    }
}

if($(active).hasClass('hidden')){
    $(active).removeClass('hidden');
    $(id).addClass('act');
}
else{
    $(active).addClass('hidden');
    $(id).removeClass('act');
}

last_alt = id;
last_alt_class = this_class;

}
