<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>eGOODS</title>
<link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_CSS_URL}/style.css" media="screen" />
</head>
<body>
	{if !empty($Header)}
		{include file=$Header->tpl data=$Header}
	{/if}
	<div id="siteContent" class="content">
		{include file=$activeModule->tpl data=$activeModule}
	</div>
	{include file='Footer.tpl'}
</div>
</body>
</html>