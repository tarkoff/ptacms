$(document).ready(function() { 
	//Apply theme style for form inputes
	$('input[type="text"], input[type="password"], textarea').addClass('text ui-corner-all');
	$('input[type="submit"]').addClass('btn ui-state-default ui-corner-all');

	// SideBar Menu Accordion
//	$("#menuAccordion").accordion();
	$("ul.sf-menu").superfish({ 
		pathClass: 'current',
		autoArrows: false
	});

	$(".ui-widget-header .ui-icon.ui-icon-circle-arrow-s").click(function() {
		$(this).toggleClass("ui-icon-circle-arrow-n");
		$(this).parents(".ui-widget:first").find(".ui-widget-content").slideToggle();
	});

	//Hover states on the static widgets
	$('.ui-state-default').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);

	//Modules Tabs
	//$("#mtabs").tabs();

	//Warning message befor delete
	$("#removeWarning").dialog({
		bgiframe: true,
		resizable: false,
		autoOpen: false,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Yes': function() {
				$(this).dialog('close');
				if (window.delUrl) {
					document.location = window.delUrl;
				} else {
					alert('Delete dialog box error!');
				}
			},
			'No': function() {
				$(this).dialog('close');
				return false
			}
		}
	});

	$("a:has(.ui-icon-trash)").click(function(e) {
		window.delUrl = $(this).attr('href');
		e.preventDefault();
		$('#removeWarning').dialog('open');
	});

});

/*
$(window).load(function () {
	
});
*/

function showSideBar()
{
	var sideBar = $(".sidebar:first");
	sideBar.width(230).find("#showMenu").hide("slow");
	sideBar.find(".ui-widget").toggle('slow');
}