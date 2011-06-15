<div id="header" class="container">
	<div class="topMenu">
		<ul>
			<li><a href="/" {if empty($TopMenu->selected)}class="hover"{/if}>Главная</a></li>
			{foreach from=$TopMenu->Categories item=category name=cat}
				{if $smarty.foreach.cat.last}
					<li class="noImg"><a {if $TopMenu->selected == $category.CATEGORIES_ALIAS}class="hover"{/if}" href="{$TopMenu->url}{$category.CATEGORIES_ALIAS}">{$category.CATEGORIES_TITLE}</a></li>
				{else}
					<li><a {if $TopMenu->selected == $category.CATEGORIES_ALIAS}class="hover"{/if} href="{$TopMenu->url}{$category.CATEGORIES_ALIAS}">{$category.CATEGORIES_TITLE}</a></li>
				{/if}
			{/foreach}
		</ul>
	</div>
	<hr class="space" />
	<div>
		<a href="/">
			{html_image file="`$smarty.const.PTA_DESIGN_IMAGES_URL`/logo.gif" alt=$smarty.const.PTA_BASE_URL}
		</a>
</div>
</div>
