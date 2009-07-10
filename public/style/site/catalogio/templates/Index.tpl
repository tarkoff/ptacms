<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="en" />
	<meta name="description" content="{$Gelezka->descr|default:'Gelezka'} - Описания, Цены, Обзоры, Драйвера" />
	<meta name="keywords" content="Описание,Цены,Обзор,Драйвера,{$Gelezka->keywords|default:''}" />
	<meta content="index,follow,noodp,noydir" name="robots"/>

	<link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/main.css" />
	<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/main-msie.css" /><![endif]-->
	<link rel="stylesheet" media="print" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/print.css" />

	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery.phpdate.js" type="text/javascript"></script>
	<title>Gelezka - Описания, Цены, Обзоры, Драйвера</title>
</head>

<body>

<div id="main">

	{include file=$Header->tpl}

	<!-- Search -->
	<div id="search-top" class="box"></div>
	<div id="search">
		<div id="search-in">
			<div id="s01">
				<form action="" method="get">
					<p class="nom t-center">
						<label for="search-input01">Поиск:</label>
						<input type="text" size="75" name="" id="search-input01" />
						<input type="image" value="Search" src="{$smarty.const.PTA_DESIGN_IMAGES_URL}/search-button.gif" class="search-submit" />
					</p>
				</form>
			</div>
		<hr class="noscreen" />
		</div> <!-- /search-in -->
	</div> <!-- /search -->
	<div id="search-bottom"></div>

	<!-- Content -->
	<div id="page" class="box">
		{include file=$activeModule->tpl data=$activeModule}
	</div> <!-- /page -->

	{include file="Footer.tpl"}
</div> <!-- /main -->

</body>
</html>
