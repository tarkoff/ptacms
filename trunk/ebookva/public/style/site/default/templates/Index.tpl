<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TemplateWorld.com Template - Web 2.0</title>
<link href="{$smarty.const.CSSURL}/style.css" rel="stylesheet" type="text/css" />
<!--
<script src="{$smarty.const.JQUERYURL}/jquery-1.3.2.min.js" type="text/javascript" />
-->
<!--
	<script src="http://www.google.com/jsapi"></script>
	<script>google.load("jquery", "1.2.6")</script>
-->
<!--	<script src="{$smarty.const.JQUERYURL}/../corners/jquery.corners.js" type="text/javascript" /> -->


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
			{include file=RightLeft.tpl}
		<!--rightLeft end -->
		<!--last start -->
			{include file=RightNav.tpl}
		<!--last end -->
		<br class="spacer" />
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