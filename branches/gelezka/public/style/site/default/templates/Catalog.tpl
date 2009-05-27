<div id="catalog">
	<h1 class="contentHeader">{$data->category.CATEGORIES_TITLE|default:'Последние поступления'}</h1>
	<div class="catalogList">
	{foreach from=$data->products item=product name=cat}
		<a href="{$data->url}/{$product.PRODUCTS_ID}">
			<div class="catalogItem">
				<div>
					{if !empty($product.PRODUCTS_IMAGE)}
						<img 
							src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_CONTENT_URL}/{$product.PRODUCTS_IMAGE}&h=100&w=100&zc=0" 
							alt="{$product.PRODUCTS_TITLE}"
							width="100" 
							height="100" 
						/>
					{else}
						<img 
							src="{$smarty.const.PTA_IMAGES_URL}/bookimg120.gif" 
							alt="{$product.PRODUCTS_TITLE}"
							width="120"
							height="120"
						/>
					{/if}
				</div>
				<div>{$product.PRODUCTS_TITLE}</div>
			</div>
		</a>
	{/foreach}
	</div>
</div>