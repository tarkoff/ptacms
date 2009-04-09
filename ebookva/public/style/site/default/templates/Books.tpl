<div id="book">
	<h1>Книга: {$data->category.CATEGORIES_TITLE} / {$data->book.PRODUCTS_TITLE}</h1>
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
			<tr>
				<td>Автор</td>
				<td>{$data->book.PRODUCTS_SHORTDESCR}</td>
			</tr>
			<tr>
				<td>{$data->book.PRODUCTS_SHORTDESCR}</td>
			</tr>
		</table>
</div>