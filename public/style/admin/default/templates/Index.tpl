<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>eGOODS</title>
<link rel="stylesheet" href="{$smarty.const.PTA_BASE_URL}/public/css/blueprint/screen.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="{$smarty.const.PTA_BASE_URL}/public/css/blueprint/print.css" type="text/css" media="print" />
<link rel="stylesheet" href="{$smarty.const.PTA_BASE_URL}/public/css/blueprint/plugins/buttons/screen.css" type="text/css" media="screen, projection" />
<!--[if IE]>
  <link rel="stylesheet" href="{$smarty.const.PTA_BASE_URL}/public/css/blueprint/ie.css" type="text/css" media="screen, projection" />
<![endif]-->

<link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_CSS_URL}/style.css" media="screen" />
</head>
<body>
	{if !empty($Header)}
		{include file=$Header->tpl data=$Header}
	{/if}
	<div id="siteContent" class="container">
		{include file=$activeModule->tpl data=$activeModule}
	</div>
	{include file='Footer.tpl'}
</div>
</body>
</html>