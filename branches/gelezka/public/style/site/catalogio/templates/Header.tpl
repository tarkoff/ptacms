<!-- Header -->
<div id="header">

	<h1 id="logo"><a href="/" title="На главную"><img src="{$smarty.const.PTA_DESIGN_IMAGES_URL}/logo.gif" alt="На главную" /></a></h1>
	<hr class="noscreen" />

	<!-- Date -->
	<div class="date date-24">
		<p class="nom">
			Сегодня <strong id="todayDate"></strong><br />
		<span class="nonhigh"><a href="">Сделать стартовой</a></span></p>
	</div> <!-- /date -->
	<script type="text/javascript">
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
	</script>

	<hr class="noscreen" />
</div> <!-- /header -->
