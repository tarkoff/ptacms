<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="en" />
	<meta name="description" content="{$Gelezka->descr|default:'Gelezka'} - Описания, Цены, Обзоры, Драйвера" />
	<meta name="keywords" content="Описание,Цены,Обзор,Драйвера,{$Gelezka->keywords|default:''}" />
	<meta content="index,follow,noodp,noydir" name="robots"/>
	<meta name="verify-v1" content="9fJg4XE0nrThqvm74P7/ATkDKlFvRledoo8jQ9aTfMs=" />

	<link rel="icon" href="{$smarty.const.PTA_DESIGN_IMAGES_URL}/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="{$smarty.const.PTA_DESIGN_IMAGES_URL}/favicon.ico" type="image/x-icon" />

	<link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/main.css" />
	<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/main-msie.css" /><![endif]-->
	<link rel="stylesheet" media="print" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/print.css" />

	<link type="text/css" rel="stylesheet" href="http://mixmarket.biz/uni/partner.css" />

	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery-1.3.2.min.js" type="text/javascript"></script>
	<link type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/jquery-ui.css" rel="stylesheet" />
	<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/ui/js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/ui/js/i18n/jquery-ui-i18n.min.js"></script>
	<title>{$Gelezka->title|default:'Gelezka'} - Описания, Цены, Обзоры, Драйвера</title>
</head>

<body>
{literal}
<div class="img1px">
<script type="text/javascript">
	document.write('<img src="http://mixmarket.biz/t.php?id=3539779&uid=1294931752&r=' + escape(document.referrer) + '&t=' + (new Date()).getTime() + '" width="1" height="1"/>');
	var mix_tracker_shown=true;
	var uni_tracker_shown=true;
</script>
<noscript><img src="http://mixmarket.biz/t.php?id=3539779&uid=1294931752" width="1" height="1"/></noscript>
</div>
{/literal}
<div id="main">

	{include file=$Header->tpl}

	<!-- Tabs -->
	<div id="search-tabs">
		<ul id="search-type">
			<li><a href="#s01"><span>Каталог</span></a></li>
			<li><a href="#s02"><span>Интернет</span></a></li>
		</ul>
		<div id="search-top"></div>
			<div id="search">
				<div id="search-in">
					{include file="Catalog/searchForm.tpl" form=$Catalog->searchForm}
					{include file="Catalog/searchFormAdSense.tpl"}
					<hr class="noscreen" />
				</div> <!-- /search-in -->
			</div> <!-- /search -->
		<div id="search-bottom"></div>
		</div> <!-- /search-tabs -->

	{* include file="Catalog/searchForm.tpl" *}
	{* include file="Catalog/searchForm.tpl" form=$Catalog->searchForm *}
	{* include file="Catalog/searchFormAdSense.tpl" *}

{literal}
<script type="text/javascript">
	$("#search-tabs").tabs();
</script>
{/literal}

	<div style="margin:15px 0 0 0;">
		<script type="text/javascript"><!--
			google_ad_client = "pub-1610147099732550";
			/* 728x90, gelezka_horizontal_bottom */
			google_ad_slot = "6736390713";
			google_ad_width = 728;
			google_ad_height = 90;
			//-->
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
	<!-- Content -->
	<div id="page" class="box">
		{include file=$activeModule->tpl data=$activeModule}
	</div> <!-- /page -->
{literal}
	<div id="adsense_bottomh" class="big_hor">
		<script type="text/javascript" language="Javascript" src="http://a.ava.com.ua/a/showA.js?partner=335&block=701&encoding=&limit="></script>
	</div>
{/literal}
	{include file="Footer.tpl"}
</div> <!-- /main -->
{include file="debuger.tpl" app=$Gelezka}
{literal}
	<script type="text/javascript">
		//MiXmarket Catalog context goods
		if (document.getElementById('mixkt_4294945452')) document.write('<scr' + 'ipt language="javascript" type="text/javascript" src="http://mixmarket.biz/show/4294945452/&div=mixkt_4294945452{/literal}{if !empty($Gelezka->mixCategory)}&cat_id={$Gelezka->mixCategory}{/if}{literal}&r=' + escape(document.referrer) + '&rnd=' + Math.round(Math.random() * 100000) + '" charset="UTF-8"><' + '/scr' + 'ipt>');
		//MixMarket Product context goods
		if (document.getElementById('mixkt_4294944591')) document.write('<scr' + 'ipt language="javascript" type="text/javascript" src="http://mixmarket.biz/show/4294944591/&div=mixkt_4294944591{/literal}{if !empty($Gelezka->mixCategory)}&cat_id={$Gelezka->mixCategory}{/if}{literal}&r=' + escape(document.referrer) + '&rnd=' + Math.round(Math.random() * 100000) + '" charset="UTF-8"><' + '/scr' + 'ipt>');
		//MixMarket partner
		if (document.getElementById('mix_block_12949300631294932579')) document.write('<scr' + 'ipt language="javascript" type="text/javascript" src="http://mixmarket.biz/uni/us/1294930063/1294932579/&div=mix_block_12949300631294932579&r=' + escape(document.referrer) + '&rnd=' + Math.round(Math.random() * 100000) + '" charset="windows-1251"><' + '/scr' + 'ipt>');
		//ForOffice Banner
		if (document.getElementById('mix_block_12949300631294933091')) document.write('<scr' + 'ipt language="javascript" type="text/javascript" src="http://mixmarket.biz/uni/us/1294930063/1294933091/&div=mix_block_12949300631294933091&r=' + escape(document.referrer) + '&rnd=' + Math.round(Math.random() * 100000) + '" charset="windows-1251"><' + '/scr' + 'ipt>');
	</script>

	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		try {
			var pageTracker = _gat._getTracker("UA-9266190-2");
			pageTracker._trackPageview();
		} catch(err) {}
	</script>
	<script type="text/javascript">
		window.google_analytics_uacct = "UA-9266190-2";
	</script>
{/literal}
</body>
</html>
