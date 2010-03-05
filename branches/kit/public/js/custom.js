$(document).ready(function() { 
	//Apply theme style for form inputes
	$('input[type="text"], input[type="password"], textarea').addClass('text ui-corner-all');
	$('input[type="submit"]').addClass('btn ui-state-default ui-corner-all');

	$(".ui-widget-header .ui-icon.ui-icon-circle-arrow-s").click(function() {
		$(this).toggleClass("ui-icon-circle-arrow-n");
		$(this).parents(".ui-widget:first").find(".ui-widget-content").slideToggle();
	});

	//Hover states on the static widgets
	$('.ui-state-default:not(div[class!=ui-jqgrid-pager])').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);

	// Align jqGrid view by center
	$(function() {
		$('#gbox_list').css('margin', '0 auto');
	});

});

/*
$(window).load(function () {
	
});
*/

