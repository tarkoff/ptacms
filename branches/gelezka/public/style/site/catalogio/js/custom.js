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
		months['11'] = 'Нояьря';
		months['12'] = 'Декабря';

		var todayDay = $.PHPDate('j', new Date());

		$("#todayDate").html(
			todayDay + ' ' + months[$.PHPDate('m', new Date())] 
			+ ' ' + $.PHPDate('Y', new Date())
		);
		var dateDiv = $(".date");
		dateDiv.removeClass();
		dateDiv.addClass('date date-' + todayDay);
	});

	// Image resizer
	$("a[rel='photos']").colorbox({
		transition:"elastic",
		current : "{current} из {total}"
	});

	// Add price form
	$("#newPriceFormScroller").click(function () {
		if ($("#newPriceForm").is(":hidden")) {
			$("#newPriceForm").slideDown("slow");
		} else {
			$("#newPriceForm").slideUp("slow");
		}
	});

	var dateTo = $("#PriceForm_dateTo");
	dateTo.datepicker(
		$.datepicker.regional['ru']
	);
	dateTo.datepicker(
		'option', {dateFormat:'yy-mm-dd'}
	);

});