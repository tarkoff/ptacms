<div id="top">
	<ul>
		<li><a href="/" {if empty($TopMenu->selected)}class="hover"{/if}>Главная</a></li>
		{foreach from=$TopMenu->Categories key=alias item=title name=cat}
		{if $smarty.foreach.cat.last}
			<li class="noImg"><a {if $TopMenu->selected == $alias}class="hover"{/if}" href="{$TopMenu->url}{$alias}">{$title}</a></li>
		{else}
			<li><a {if $TopMenu->selected == $alias}class="hover"{/if} href="{$TopMenu->url}{$alias}">{$title}</a></li>
		{/if}
	{/foreach}
	</ul>
</div>
<div class="logoBlock">
	<a href="/">
		{html_image file="`$smarty.const.PTA_IMAGES_URL`/logo.gif" alt=$smarty.const.PTA_BASE_URL}
	</a>
</div>