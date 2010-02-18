<!-- Header -->
<div id="header">
<div class="f-left" style="padding-top:34px;">
	<h1 id="logo"><a href="/" title="На главную"><img width="210" height="56" src="{$smarty.const.PTA_DESIGN_IMAGES_URL}/logo.gif" alt="На главную" /></a></h1>
</div>
	<!-- Date -->
	<div class="f-right">
	{if !empty($Gelezka->keywords)}
		{literal}<script type="text/javascript" language="Javascript" src="http://a.ava.com.ua/a/showA.js?partner=335&block=944&search={/literal}{$Gelezka->keywords|replace:',':'+'}{literal}&encoding=utf8&limit=2"></script>{/literal}
	{/if}
	</div>
	<!-- /date -->
</div>
<hr class="noscreen" />
<!-- /header -->
