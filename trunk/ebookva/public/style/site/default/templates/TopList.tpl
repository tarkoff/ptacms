<div id="topList">
	<p>Лучшие книги</p>
	<ul>
	{foreach from=$data->topList item=book name=cat}
		<li>
			<a href="{$data->url}{$book.PRODUCTS_ID}">
				{$book.PRODUCTS_TITLE}
			</a>
		/li>
	{/foreach}
	</ul>
</div>