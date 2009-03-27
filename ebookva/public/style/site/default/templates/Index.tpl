<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>eBookva</title>
<link href="{$smarty.const.CSSURL}/style.css" rel="stylesheet" type="text/css" />
<script src="{$smarty.const.JQUERYURL}/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="{$smarty.const.JQUERYURL}/corners/jquery.corners.js" type="text/javascript"></script>
<script src="{$smarty.const.JQUERYURL}/accordion/jquery.accordion.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
	jQuery().ready(function(){
		// second simple accordion with special markup
		jQuery('#navigation').accordion({
			active: false,
			header: '.head',
			navigation: true,
			event: 'mouseover',
			fillSpace: true,
			animated: 'easeslide'
		});
		
		
	});
</script>
<style>

div.selected .title { font-weight: bold; }
div.selected {
	border-bottom: none;
}

#navigation {
	border:1px solid #5263AB;
	margin:0px;
	padding:0px;
	text-indent:0px;
	background-color:#E2E2E2;
	width:200px;
}
#navigation a.head {
	cursor:pointer;
	border:1px solid #CCCCCC;
	background:#5263AB url(collapsed.gif) no-repeat scroll 3px 4px;
	color:#FFFFFF;
	display:block;
	font-weight:bold;
	margin:0px;
	padding:0px;
	text-indent:14px;
	text-decoration: none;
}
#navigation a.head:hover {
	color:#FFFF99;
}
#navigation a.selected {
	background-image: url(expanded.gif);
}
#navigation a.current {
	background-color:#FFFF99;
}
#navigation ul {
	border-width:0px;
	margin:0px;
	padding:0px;
	text-indent:0px;
}
#navigation li {
	list-style:none outside none; display:inline;
}
#navigation li li a {
	color:#000000;
	display:block;
	text-indent:10px;
	text-decoration: none;
}
#navigation li li a:hover {
	background-color:#FFFF99;
	color:#FF0000;
}
</style>
{/literal}
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
			{*include file=RightLeft.tpl*}
			{include file=$activeModule->tpl data=$activeModule}
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
