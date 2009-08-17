<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>P.T.A. CMS Admin Panel</title>
<link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/redmond/jquery-ui-1.7.2.custom.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.PTA_DESIGN_CSS_URL}/style.css" media="screen" />
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/ui/js/jquery-ui-1.7.2.custom.min.js"></script>
</head>
<body>
	{if !empty($Header)}
		{include file=$Header->tpl data=$Header}
	{/if}
	<table class="layout-grid" cellspacing="0" cellpadding="0">
		<tr>
			<td class="left-nav">
				{include file=$MainMenu->tpl data=$MainMenu}
			</td>
			<td class="normal">
				{if !empty($app->messages)}
					<div class="normal">
						{include file="Messages.tpl" messages=$app->messages}
					</div
				{/if}
				<div class="normal">
						<h4 class="demo-subheader">{$activeModule->prefix}:</h4>
						<h3 class="demo-header">tabs</h3>
							{include file=$activeModule->tpl data=$activeModule}
				</div>

			</td>
		</tr>
	</table>

	{include file='Footer.tpl'}
</body>
</html>