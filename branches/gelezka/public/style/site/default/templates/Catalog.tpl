<div id="catalog">
	<h1 class="contentHeader">{$data->category.CATEGORIES_TITLE|default:'Последние добавления'}</h1>
	<div class="catalogList">
	{foreach from=$data->products item=product name=cat}
		<div class="span-4 catalogItem">
			{assign var="pTitle" value="`$product.BRANDS_TITLE`&nbsp;`$product.PRODUCTS_TITLE`"}
			<a href="{$data->url}/{$product.PRODUCTS_ID}">
				<h6>{$pTitle|truncate:30}</h6>
				{if !empty($product.PHOTOS_PHOTO)}
					<img 
						src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_CONTENT_URL}/{$product.PHOTOS_PHOTO}&h=120&w=120&zc=0" 
						alt="{$product.PRODUCTS_TITLE}"
						width="120" 
						height="120" 
					/>
				{else}
					<img 
						src="{$smarty.const.PTA_IMAGES_URL}/noImg120.gif" 
						alt="{$product.PRODUCTS_TITLE}"
						width="120"
						height="120" 
					/>
				{/if}
			</a>
		</div>
	{/foreach}
	</div>
</div>