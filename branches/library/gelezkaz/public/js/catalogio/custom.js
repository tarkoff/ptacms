$(document).ready(function() {
	//Search bar tabs
	$("#search-tabs").tabs();

	//Product description tabs
	$( "#prodTabs" ).tabs({
		ajaxOptions: {
			error: function( xhr, status, index, anchor ) {
				$( anchor.hash ).html("В данный момент страница не загружается. Мы испрваим это в ближайшее время.");
			}
		},
		select: function(event, ui) { $("#prodTabs div.ui-tabs-panel[id!=prodCustomDescr][id!=prices]").html("Загрузка..."); }
	});


});

function getUserIP() {
	$.getJSON("http://jsonip.appspot.com?callback=?",function(ipadd){
		return ipadd.ip;
	});
}