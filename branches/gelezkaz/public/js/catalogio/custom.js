$(document).ready(function() {
	var data = ['sup', '.net', 'port', '@', 'ezka', 'gel'];
	var cnct = data[0] + data[2] + data[3] + data[5] + data[4] + data[1];
	$('#support a, #contacts a').attr('href', 'mailto:' + cnct).text(cnct);

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