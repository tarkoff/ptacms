{* include file=TopList.tpl data=$TopList *}

<div id="catalog">
	<h1>{$data->category.CATEGORIES_TITLE|default:'Последние поступления'}</h1>
	<ul>
	{foreach from=$data->products item=product name=cat}
		<li>
			<a href="{$data->url}/{$product.PRODUCTS_ID}">
				<div>
					{if !empty($product.PRODUCTS_IMAGE)}
						<img 
							src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_BASE_URL}{$product.PRODUCTS_IMAGE}&h=120&w=120&zc=0" 
							alt="{$product.PRODUCTS_TITLE}"
						/>
					{else}
						{html_image file="`$smarty.const.PTA_IMAGES_URL`/bookimg120.gif" alt="`$product.PRODUCTS_TITLE`"}
					{/if}
				</div>
				<div>{$product.PRODUCTS_TITLE}</div>
			</a>
		/li>
	{/foreach}
	</ul>
</div>