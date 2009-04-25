<div id="top">
	<ul>
		<li><a href="/" {if empty($TopMenu->selected)}class="hover"{/if}>Главная</a></li>
		{foreach from=$TopMenu->Categories key=alias item=title name=cat}
		{if $smarty.foreach.cat.last}
			<li class="noImg {if $TopMenu->selected == $alias}hover{/if}"><a href="{$TopMenu->url}{$alias}">{$title}</a></li>
		{else}
			<li><a {if $TopMenu->selected == $alias}class="hover"{/if} href="{$TopMenu->url}{$alias}">{$title}</a></li>
		{/if}
	{/foreach}
	</ul>
</div>
<div class="logoBlock">
	<a href="/">
		{html_image file="`$smarty.const.IMAGESURL`/logo.gif" alt=$smarty.const.BASEURL}
	</a>
</div>