$(document).ready(function() { 
	//Hover states on the static widgets
	$('.ui-state-default').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);

	// SideBar Menu Accordion
	$("#menuAccordion").accordion();

	$(".ui-widget-header .ui-icon.ui-icon-circle-arrow-s").click(function() {
		$(this).toggleClass("ui-icon-circle-arrow-n");
		$(this).parents(".ui-widget:first").find(".ui-widget-content").slideToggle();
	});

	$(".ui-widget-header .ui-icon.ui-icon-circle-arrow-w").click(function() {
		$(this).toggleClass("ui-icon-circle-arrow-e");
//		$(this).parents(".ui-widget:first").find(".ui-widget-content").slideToggle();
		$(this).parents(".ui-widget").toggle('slow');
		$(this).parents(".sidebar:first").width('auto').find("#showMenu").show("slow");
	});
	
	// Calculate document width
	$(function() {
		var sidBarWidth = $('#sidebar').width();
		var contentWidth = $('#content').width();
		$('body').css('min-width', sidBarWidth + contentWidth + 20 + 'px');
	});

});

function showSideBar()
{
	var sideBar = $(".sidebar:first");
	sideBar.width(230).find("#showMenu").hide("slow");
	sideBar.find(".ui-widget").toggle('slow');
}