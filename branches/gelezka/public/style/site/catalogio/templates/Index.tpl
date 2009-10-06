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

	<link rel="icon" href="{$smarty.const.PTA_DESIGN_IMAGES_URL}/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="{$smarty.const.PTA_DESIGN_IMAGES_URL}/favicon.ico" type="image/x-icon">

	<link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/main.css" />
	<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/main-msie.css" /><![endif]-->
	<link rel="stylesheet" media="print" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/print.css" />

	<link type="text/css" rel="stylesheet" href="http://mixmarket.biz/uni/partner.css">


	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery.phpdate.js" type="text/javascript"></script>
	<title>{$Gelezka->title|default:'Gelezka'} - Описания, Цены, Обзоры, Драйвера</title>
</head>

<body>
{literal}
<style>.img1px, .img1px img{margin:0;padding:0;font-size:0;line-height:0;}</style>
<div class="img1px">
<script>
	if (uni_tracker_shown===undefined || mix_tracker_shown===undefined) {
		document.write('<img src="http://mixmarket.biz/t.php?uid=1294931752&id=3539779&r=' + escape(document.referrer) + '&t=' + (new Date()).getTime() + '" width="1" height="1"/>');
		var uni_tracker_shown=true;var mix_tracker_shown=true;
	}
</script>
<noscript><img src="http://mixmarket.biz/t.php?uid=1294931752&id=3539779" width="1" height="1"/></noscript>
</div>
{/literal}
<div id="main">

	{include file=$Header->tpl}

	{* include file="Catalog/searchForm.tpl" form=$Catalog->searchForm *}
	{include file="Catalog/searchFormAdSense.tpl"}

	{literal}
		<style>
			#mix_block_12949300631294932072, .mix_horiz_tr{height:120px; overflow:hidden;}
			.mix_inter table{margin:0;}
			.mix_outer .mix_head, .mix_outer .mix_desc, .mix_outer .mix_domain, .mix_outer .mix_img {padding:0.2em;}
			.mix_outer .mix_horiz_td {border:none;}
			#mix_block_12949300631294932072 img{margin:0;padding:0;font-size:0;line-height:0;}
		</style
		<div id="mix_block_12949300631294932072"></div>
	{/literal}

	<!-- Content -->
	<div id="page" class="box">
		{include file=$activeModule->tpl data=$activeModule}
	</div> <!-- /page -->

	<div id="adsense_bottomh" class="big_hor">
		<script type="text/javascript">
			<!--
				google_ad_client = "pub-1610147099732550";
				/* 728x90, gelezka_horizontal_bottom */
				google_ad_slot = "6736390713";
				google_ad_width = 728;
				google_ad_height = 90;
			//-->
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>

	{include file="Footer.tpl"}
</div> <!-- /main -->
{include file="debuger.tpl" app=$Gelezka}
{literal}
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

<!-- MixMarket -->
<script>
document.write('<scr' + 'ipt language="javascript" type="text/javascript" src="http://mixmarket.biz/uni/us/1294930063/1294932072/&div=mix_block_12949300631294932072&r=' + escape(document.referrer) + '&rnd=' + Math.round(Math.random() * 100000) + '" charset="windows-1251"><' + '/scr' + 'ipt>')
</script>
{/literal}
</body>
</html>
