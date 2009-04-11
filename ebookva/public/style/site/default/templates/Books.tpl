<div id="book">
	<h1>Книга: {$data->category.CATEGORIES_TITLE} / {$data->book.PRODUCTS_TITLE}</h1>
	<div id="bookDesc">
		<div id="bookImg">
			{if !empty($data->book.PRODUCTS_IMAGE)}
				<img src="{$smarty.const.THUMBURL}?src={$smarty.const.BASEURL}{$data->book.PRODUCTS_IMAGE}&h=180&w=180&zc=0" alt="{$data->book.PRODUCTS_TITLE}"/>
			{else}
				{html_image file="`$smarty.const.IMAGESURL`/bookimg180.gif" alt="`$data->book.PRODUCTS_TITLE`"}
			{/if}
		</div>
		<div id="bDescr">
			<table>
			{foreach from=$data->customBookField item=field}
				<tr>
					<td class="ftitle">{$field.PRODUCTSFIELDS_TITLE}:</td>
					<td class="fvalue">{$field.PRODUCTSVALUES_VALUE}</td>
				</tr>
			{/foreach}
			<tr>
				<td class="ftitle">Описание:</td>
				<td class="fvalue">{$data->book.PRODUCTS_SHORTDESCR}</td>
			</div>
			</table>
		</div>
	</div>
<!--
		<table>
			<tr>
				<td rowspan="5">
				{if !empty($data->book.PRODUCTS_IMAGE)}
					<img src="{$smarty.const.THUMBURL}?src={$smarty.const.BASEURL}{$data->book.PRODUCTS_IMAGE}&h=180&w=180&zc=0" alt="{$data->book.PRODUCTS_TITLE}"/>
				{else}
					{html_image file="`$smarty.const.IMAGESURL`/bookimg180.gif" alt="`$data->book.PRODUCTS_TITLE`"}
				{/if}
				</td>
				</tr>
			{foreach from=$data->customBookField item=field}
			<tr>
				<td class="ftitle">{$field.PRODUCTSFIELDS_TITLE}:</td>
				<td class="fvalue">{$field.PRODUCTSVALUES_VALUE}</td>
			</tr>
			{/foreach}
			<tr>
				<td class="fvalue">{$data->book.PRODUCTS_SHORTDESCR}</td>
			</tr>
		</table>
-->
</div>