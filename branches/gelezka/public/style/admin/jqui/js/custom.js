$(document).ready(function() { 
	//Hover states on the static widgets
	$('.btn, .btn_no_text').hover(
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

});

/*
$(window).load(function () {
	themeSwitcher();
});

//Theme switcher
function themeSwitcher(themeAlias)
{
	var date = new Date();
	var themePath = 'http://admin.gelezka/public/style/admin/jqui/css/';
	var themeFile = 'jquery-ui-1.7.2.custom.css';
	//var theme = 'ui-lightness';
	if (!themeAlias) {
		themeAlias = $.cookie('ui_theme');
	}
	switch(themeAlias) {
		case 'ui_lght':
			theme = 'ui-lightness';
			themeAlias = 'ui_lght';
		break;
		case 'ui_rdmnd':
			theme = 'redmond';
			themeAlias = 'ui_rdmnd';
		break;
		default:
			theme = 'redmond';
			themeAlias = 'ui_rdmnd';
	}
	
	$("link[title='style']").attr("href", themePath + theme + '/' + themeFile);
	
	date.setTime(date.getTime() + (3 * 24 * 60 * 60 * 1000)); 
	$.cookie('ui_theme', themeAlias, { path: '/', expires: date });
	$(".themeswitcher select").val(themeAlias);
}
*/
function showSideBar()
{
	var sideBar = $(".sidebar:first");
	sideBar.width(230).find("#showMenu").hide("slow");
	sideBar.find(".ui-widget").toggle('slow');
}