<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gelezka - Описания, цены, драйвера, отзывы...</title>
<meta name="keywords" content="Описания, цены, драйвера, отзывы">
<link href="{$smarty.const.PTA_CSS_URL}/style.css" rel="stylesheet" type="text/css" />
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/corners/jquery.corners.js" type="text/javascript"></script>
</head>
<body>
<!--top start -->
	{include file=$Header->tpl data=$Header}
<!--top end -->
<!--body start -->
	<div id="body">
	<!--left start -->
		{include file=$LeftMenu->tpl data=$LeftMenu}
	<!--left end -->
	<!--right start -->
		<div id="right">
		<!--rightTop start -->
			{*include file=RightTop.tpl*}
		<!--rightTop end -->
		<!--rightLeft start -->
			<div id="rightLeft">
				{include file=$activeModule->tpl data=$activeModule}
			</div>
		<!--rightLeft end -->
		<!--last start -->
			{include file=RightNav.tpl}
		<!--last end -->
		</div>
	<!--right end -->
	<br class="spacer" />
	</div>
<!--body end -->
<!--footer start -->
	{include file=Footer.tpl}
<!--footer end -->
</body>
</html>
