<div id="product">
	<h1 class="contentHeader">{$data->category.CATEGORIES_TITLE} - {$data->brand.BRANDS_TITLE} {$data->product.PRODUCTS_TITLE}</h1>
	<div id="descr" class="descr">
		<div id="descrTop" class="append-bottom">
			<div id="productImg" class="productImgDiv">
				{if !empty($data->product.PRODUCTS_IMAGE)}
					<img src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_CONTENT_URL}/{$data->product.PRODUCTS_IMAGE}&h=180&w=180&zc=0" alt="{$data->product.PRODUCTS_TITLE}"/>
				{else}
					<img src="{$smarty.const.PTA_IMAGES_URL}/bookimg180.gif" alt="{$data->product.PRODUCTS_TITLE}" />
				{/if}
			</div>
		</div>
		<div id="shortDescr" class="append-bottom">
			{$data->product.PRODUCTS_SHORTDESCR}
		</div>
		{if !empty($data->customProductField)}
		<div id="fullDescr" class="fullDescr">
			<table cols="2">
				<th colspan="2">Основные характеристики</th>
			{foreach from=$data->customProductField item=field}
				<tr bgcolor="{cycle values="#E2DEA4,#D5D08E"}">
					<td class="title">{$field.PRODUCTSFIELDS_TITLE}:</td>
					<td>{$field.PRODUCTSFIELDSVALUES_VALUE|default:'неизвестно'}</td>
				</tr>
			{/foreach}
			</table>
		</div>
		{/if}
	</div>
</div>