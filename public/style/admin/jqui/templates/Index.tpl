<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>P.T.A. CMS Admin Panel</title>
<link rel="stylesheet" href="/public/css/blueprint/screen.css" type="text/css" media="screen, projection" />
<!--[if IE]>
  <link rel="stylesheet" href="/public/css/blueprint/ie.css" type="text/css" media="screen, projection" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/{$smarty.const.PTA_DESIGN_THEME}/jquery-ui-1.7.2.custom.css" title="style" media="screen" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/style.css" media="screen" />
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/ui/js/jquery-ui-1.7.2.custom.min.js"></script>
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery.cookie.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{$smarty.const.PTA_DESIGN_URL}/js/custom.js"></script>
</head>
<body>
	{if !empty($Header)}
		{include file=$Header->tpl data=$Header}
		<hr class="space" />
		<div id="layout">
		{include file="SideBar.tpl"}
		<div id="content" class="last span-24">
	{else}
		<div id="content" style="margin:0 auto !Important;width:300px;padding-top:100px;">
	{/if}
		<div class="ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" style="padding:5px;">
		{if !empty($app->messages)}
			{include file="Messages.tpl" messages=$app->messages}
		{/if}
		{include file=$activeModule->tpl data=$activeModule}
		</div>
	</div>
	</div>
	{include file='Footer.tpl'}

<div id="removeWarning" title="Remove this item?">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		This item will be permanently deleted and cannot be recovered. Are you sure?
	</p>
</div>

</body>
</html>