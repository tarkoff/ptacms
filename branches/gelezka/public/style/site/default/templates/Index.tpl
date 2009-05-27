<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gelezka - Описания, цены, драйвера, отзывы...</title>
<meta name="keywords" content="Описания, цены, драйвера, отзывы">
<link rel="stylesheet" href="{$smarty.const.PTA_BASE_URL}/public/css/blueprint/screen.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="{$smarty.const.PTA_BASE_URL}/public/css/blueprint/print.css" type="text/css" media="print" />
<!--[if IE]>
  <link rel="stylesheet" href="{$smarty.const.PTA_BASE_URL}/public/css/blueprint/ie.css" type="text/css" media="screen, projection" />
<![endif]-->
<link href="{$smarty.const.PTA_CSS_URL}/style2.css" rel="stylesheet" type="text/css" />

<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/corners/jquery.corners.js" type="text/javascript"></script>
</head>
<body>
	{include file=$Header->tpl data=$Header}
	<hr class="space" />
	<div id="siteBody" class="container">
		{include file=$LeftMenu->tpl data=$LeftMenu}
		<div id="rightSide" class="span-19 last">
			<div id="content" class="span-14 content">
				{include file=$activeModule->tpl data=$activeModule}
			</div>
			<div id="adv" class="span-5 last"><h2>Sponsors links</h2></div>
		</div>
	</div>
	<hr class="space" />
	{include file=Footer.tpl}
</body>
</html>