<div id="product">
	<h1>Книга: {$data->category.CATEGORIES_TITLE} / {$data->product.PRODUCTS_TITLE}</h1>
	<div id="productDesc">
		<div id="productImg">
			{if !empty($data->product.PRODUCTS_IMAGE)}
				<img src="{$smarty.const.THUMBURL}?src={$smarty.const.BASEURL}{$data->product.PRODUCTS_IMAGE}&h=180&w=180&zc=0" alt="{$data->product.PRODUCTS_TITLE}"/>
			{else}
				{html_image file="`$smarty.const.IMAGESURL`/bookimg180.gif" alt="`$data->product.PRODUCTS_TITLE`"}
			{/if}
		</div>
		<div id="bDescr">
			<table>
			{foreach from=$data->customProductField item=field}
				<tr>
					<td class="ftitle">{$field.PRODUCTSFIELDS_TITLE}:</td>
					<td class="fvalue">{$field.PRODUCTSVALUES_VALUE}</td>
				</tr>
			{/foreach}
			<tr>
				<td class="ftitle">Описание:</td>
				<td class="fvalue">{$data->product.PRODUCTS_SHORTDESCR}</td>
			</div>
			</table>
		</div>
	</div>
<!--
		<table>
			<tr>
				<td rowspan="5">
				{if !empty($data->product.PRODUCTS_IMAGE)}
					<img src="{$smarty.const.THUMBURL}?src={$smarty.const.BASEURL}{$data->product.PRODUCTS_IMAGE}&h=180&w=180&zc=0" alt="{$data->product.PRODUCTS_TITLE}"/>
				{else}
					{html_image file="`$smarty.const.IMAGESURL`/bookimg180.gif" alt="`$data->product.PRODUCTS_TITLE`"}
				{/if}
				</td>
				</tr>
			{foreach from=$data->customProductField item=field}
			<tr>
				<td class="ftitle">{$field.PRODUCTSFIELDS_TITLE}:</td>
				<td class="fvalue">{$field.PRODUCTSVALUES_VALUE}</td>
			</tr>
			{/foreach}
			<tr>
				<td class="fvalue">{$data->product.PRODUCTS_SHORTDESCR}</td>
			</tr>
		</table>
-->
</div>