<div id="topList">
	<p>Лучшие книги</p>
	<ul>
	{foreach from=$data->topList item=book name=cat}
		<li>
			<a href="{$data->url}{$book.PRODUCTS_ID}">
				<div>
			{if !empty($book.PRODUCTS_IMAGE)}
				<img src="{$smarty.const.THUMBURL}?src={$smarty.const.BASEURL}{$book.PRODUCTS_IMAGE}&h=110&w=110&zc=0" alt="`$book.PRODUCTS_TITLE`"/>
			{else}
				{html_image file="`$smarty.const.BASEURL``$book.PRODUCTS_IMAGE`" alt="`$book.PRODUCTS_TITLE`"}
			{/if}
			</div>
			<div>{$book.PRODUCTS_TITLE}</div>
			</a>
		/li>
	{/foreach}
	</ul>
</div>