$(document).ready(function() {
	$(function() {
		var months = new Array();
		months['01'] = 'Января';
		months['02'] = 'Февраля';
		months['03'] = 'Марта';
		months['04'] = 'Апреля';
		months['05'] = 'Мая';
		months['06'] = 'Июня';
		months['07'] = 'Июля';
		months['08'] = 'Августа';
		months['09'] = 'Сентября';
		months['10'] = 'Октября';
		months['11'] = 'Ноября';
		months['12'] = 'Декабря';

		var todayDay = $.PHPDate('d', new Date());

		$("#todayDate").html(
			todayDay + ' ' + months[$.PHPDate('m', new Date())] 
			+ ' ' + $.PHPDate('Y', new Date())
		);
		var dateDiv = $(".date");
		dateDiv.removeClass();
		dateDiv.addClass('date date-' + todayDay);
	});

});