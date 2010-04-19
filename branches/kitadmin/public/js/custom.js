$(document).ready(function() { 
	//Apply theme style for form inputes
	$('.kit-form input:text, .kit-form textarea').addClass('text kit-text ui-corner-all');
	$(".kit-form input:submit").parent().css('text-align', 'center');
	$(".kit-form input:submit").button();

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

