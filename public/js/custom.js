$(document).ready(function() {
	var data = ['sup', '.com', 'port', '@', 'evice', 'satd'];
	var cnct = data[0] + data[2] + data[3] + data[5] + data[4] + data[1];
	$('#support a, #contacts a').attr('href', 'mailto:' + cnct).text(cnct);
});


/*
$(window).load(function () {
	
});
*/

