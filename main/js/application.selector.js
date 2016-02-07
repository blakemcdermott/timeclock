$(document).ready(function(){
$.fn.pngFix = function() {
  if (!$.browser.msie || $.browser.version >= 9) { return $(this); }

  return $(this).each(function() {
    var img = $(this),
        src = img.attr('src');

    img.attr('src', 'images/transparent.gif')
        .css('filter', "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled='true',sizingMethod='crop',src='" + src + "')");
  });
};	
	
$.fn.preload = function() {
    this.each(function(){
        $('<img/>')[0].src = this;
    });
}

// Usage:

$(['images/crm_icon_active.png','images/crm_icon_hover.png','images/crm_icon_dead.png',
	'images/sites_icon_active.png','images/sites_icon_hover.png','images/sites_icon_dead.png',
	'images/partners_icon_active.png','images/partners_icon_hover.png','images/partners_icon_dead.png',
	'images/reports_icon_active.png','images/reports_icon_hover.png','images/reports_icon_dead.png']).preload();
var selected = false;
var clicked = false;



function makeSelection(selected, clicked) {
	
		switch(clicked){
		case "crm":
		//alert(selected);
		removeSelection(selected);
		selected=clicked;
		$("#"+selected).css("background","url(images/"+selected+"_icon_active.png) no-repeat");
		return selected;
		break;
		
		case "sites":
		//alert(selected);
		removeSelection(selected);
		selected=clicked;
		$("#"+selected).css("background","url(images/"+selected+"_icon_active.png) no-repeat");
		return selected;
		break;
		
		case "partners":
		//alert(selected);
		removeSelection(selected);
		selected=clicked;
		$("#"+selected).css("background","url(images/"+selected+"_icon_active.png) no-repeat");
		return selected;
		break;
		
		case "reports":
		//alert(selected);
		removeSelection(selected);
		selected=clicked;
		$("#"+selected).css("background","url(images/"+selected+"_icon_active.png) no-repeat");
		return selected;
		break;
		}
	
}
function removeSelection(selected) {
	if(selected){
		switch(selected){
		case "crm":
		$("#"+selected).css("background","url(images/"+selected+"_icon_dead.png) no-repeat");
		break;
		
		case "sites":
		$("#"+selected).css("background","url(images/"+selected+"_icon_dead.png) no-repeat");
		break;
		
		case "partners":
		$("#"+selected).css("background","url(images/"+selected+"_icon_dead.png) no-repeat");
		break;
		
		case "reports":
		$("#"+selected).css("background","url(images/"+selected+"_icon_dead.png) no-repeat");
		break;
		}
	}
}


	$("#crm").click(function(){
	selected = makeSelection(selected, "crm");
	ajaxCall(appLoader(selected));
	$("#portal_box").slideUp('slow');
	});
	$("#sites").click(function(){
	selected = makeSelection(selected, "sites");
	ajaxCall(appLoader(selected));
	$("#portal_box").slideUp('slow');
	});
	$("#partners").click(function(){
	selected = makeSelection(selected, "partners");
	ajaxCall(appLoader(selected));
	$("#portal_box").slideUp('slow');
	});
	$("#reports").click(function(){
	selected = makeSelection(selected, "reports");
	ajaxCall(appLoader(selected));
	$("#portal_box").slideUp('slow');
	});
	
	$("#show-portalbox").click(function(){
	$("#portal_box").slideDown('slow');
	});

	$("#crm").hover(
	function() {
		
	$("img#crm_hover").stop().animate({"opacity": "1"}, "fast");
	},
	function() {
	$("img#crm_hover").stop().animate({"opacity": "0"}, "fast");
	});

	$("#sites").hover(
	function() {
		
	$("img#sites_hover").stop().animate({"opacity": "1"}, "fast");
	},
	function() {
	$("img#sites_hover").stop().animate({"opacity": "0"}, "fast");
	});
	
	$("#partners").hover(
	function() {
		
	$("img#partners_hover").stop().animate({"opacity": "1"}, "fast");
	},
	function() {
	$("img#partners_hover").stop().animate({"opacity": "0"}, "fast");
	});
	
	$("#reports").hover(
	function() {
		
	$("img#reports_hover").stop().animate({"opacity": "1"}, "fast");
	},
	function() {
	$("img#reports_hover").stop().animate({"opacity": "0"}, "fast");
	});
	
	
});