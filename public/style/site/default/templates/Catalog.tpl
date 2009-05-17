{* include file=TopList.tpl data=$TopList *}

<div id="catalog">
	<h1>Бесплатные книги по </h1>
	<ul>
	{foreach from=$data->products item=product name=cat}
		<li>
			<a href="{$data->url}/{$product.PRODUCTS_ID}">
				<div>
			{if !empty($product.PRODUCTS_IMAGE)}
				<img src="{$smarty.const.THUMBURL}?src={$smarty.const.BASEURL}{$product.PRODUCTS_IMAGE}&h=120&w=120&zc=0" alt="`$product.PRODUCTS_TITLE`"/>
			{else}
				{html_image file="`$smarty.const.IMAGESURL`/bookimg120.gif" alt="`$product.PRODUCTS_TITLE`"}
			{/if}
			</div>
			<div>{$product.PRODUCTS_TITLE}</div>
			</a>
		/li>
	{/foreach}
	</ul>
</div>