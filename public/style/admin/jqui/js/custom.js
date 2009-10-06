$(document).ready(function() { 
	//Hover states on the static widgets
	$('.btn, .btn_no_text').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);

	$(".ui-widget-header .ui-icon").click(function() {
		$(this).toggleClass("ui-icon-circle-arrow-n");
		$(this).parents(".ui-widget:first").find(".ui-widget-content").slideToggle();
	});
});