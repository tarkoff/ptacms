{* include file=TopList.tpl data=$TopList *}

<div id="catalog">
	<h1>Бесплатные книги по </h1>
	<ul>
	{foreach from=$data->books item=book name=cat}
		<li>
			<a href="{$data->url}/{$book.PRODUCTS_ID}">
				<div>
			{if !empty($book.PRODUCTS_IMAGE)}
				<img src="{$smarty.const.THUMBURL}?src={$smarty.const.BASEURL}{$book.PRODUCTS_IMAGE}&h=120&w=120&zc=0" alt="`$book.PRODUCTS_TITLE`"/>
			{else}
				{html_image file="`$smarty.const.IMAGESURL`/bookimg.gif" alt="`$book.PRODUCTS_TITLE`"}
			{/if}
			</div>
			<div>{$book.PRODUCTS_TITLE}</div>
			</a>
		/li>
	{/foreach}
	</ul>
</div>