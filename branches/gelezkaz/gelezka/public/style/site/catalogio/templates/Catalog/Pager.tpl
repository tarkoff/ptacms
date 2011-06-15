<!-- Pagination -->
{defun name="pagination" nav=$view}
	<div class="title01-top"></div>
	<div class="title01">
		<div class="title01-in">
			<p class="pagination">
				<a href="{$pagerUrl}/page/{$nav->prevPage}{if !empty($smarty.server.QUERY_STRING)}?{$smarty.server.QUERY_STRING}{/if}">&laquo; Назад</a> &nbsp;
				{math equation="x + y" x=$nav->lastPage y=1 assign="lastPage"}
				{assign var="groupStep" value="10"}
				{math equation="x - y" x=$view->page y=$groupStep assign="firstGroupPage"}
				{math equation="x + y + 1" x=$view->page y=$groupStep assign="lastGroupPage"}
				{if $firstGroupPage <= $groupStep}
					{assign var="firstGroupPage" value="1"}
					{math equation="x + y * 2" x=1 y=$groupStep assign="lastGroupPage"}
				{/if}
				{if $lastGroupPage > $lastPage}
					{if ($lastPage - $groupStep * 2) > 0}
						{ math equation="x - y * 2" x=$lastPage y=$groupStep assign="firstGroupPage" }
					{/if}
					{assign var="lastGroupPage" value="$lastPage"}
				{/if}
				{if $firstGroupPage > 1}
					<a href="{$pagerUrl}/page/{$firstGroupPage}{if !empty($smarty.server.QUERY_STRING)}?{$smarty.server.QUERY_STRING}{/if}">...</a>
				{/if}
				{section name=page start=$firstGroupPage loop=$lastGroupPage step=1}
					{if $smarty.section.page.index == $nav->page}
						<strong>{$nav->page}</strong>
					{else}
						<a href="{$pagerUrl}/page/{$smarty.section.page.index}{if !empty($smarty.server.QUERY_STRING)}?{$smarty.server.QUERY_STRING}{/if}">{$smarty.section.page.index}</a>
					{/if}
					{if $smarty.section.page.index != $smarty.section.page.last}
						<span class="noscreen">,</span>
					{/if}
				{sectionelse}
					<strong>1</strong>
				{/section}
				{if $lastGroupPage < $view->lastPage}
					<a href="{$pagerUrl}/page/{$lastGroupPage}{if !empty($smarty.server.QUERY_STRING)}?{$smarty.server.QUERY_STRING}{/if}">...</a>
				{/if}
				&nbsp; <a href="{$pagerUrl}/page/{$nav->nextPage}{if !empty($smarty.server.QUERY_STRING)}?{$smarty.server.QUERY_STRING}{/if}">Вперед &raquo;</a>
			</p>
		</div>
	</div>
	<div class="title01-bottom"></div>
{/defun}

<!-- <p>Показано <strong>1 - 10</strong> из <strong>520</strong>.</p> -->
{if !empty($catalogObject)}
	{assign var="sponsoredLinks" value=$catalogObject->getSponsoredLinks()}
{/if}
<!-- Sponsored links -->
	<div class="title03-top"></div>
	<div class="title03">
		<h3 class="nom">Лучшие предложения партнеров:</h3>
		<div id="mixkt_4294945452"></div>
		<div id="mix_block_12949300631294932579"></div>
	</div>
	<div class="title03-bottom"></div>

<ol class="cat-results">
	{if !empty($view->prodsDefaultCats)}
		{assign var="cats" value=$view->prodsDefaultCats}
	{/if}
	{foreach from=$view->data item=catalogItem name=catalog}
		{if $smarty.foreach.catalog.iteration == 6}
		<li>{include file=ads/adsense_728x90.tpl}</li>
		{/if}
		<li>
			<h4>
				<a href="{$data->brandUrl}/{$catalogItem.BRANDS_ALIAS}">{$catalogItem.BRANDS_TITLE}</a>&nbsp;
				<a href="{$data->url}/{$catalogItem.PRODUCTS_ALIAS}">{$catalogItem.PRODUCTS_TITLE}</a> &ndash; 
				<span>
					<a href="{$data->url}/{$catalogItem.PRODUCTS_ALIAS}" class="high ico-card">Подробнее...</a>&nbsp;
					{if empty($cats[$catalogItem.PRODUCTS_ID])}
						(<a href="{$Categories->url}/{$catalogItem.CATEGORIES_ALIAS}" class="folder">{$catalogItem.CATEGORIES_TITLE}</a>)
					{else}
						(<a href="{$Categories->url}/{$cats[$catalogItem.PRODUCTS_ID].CATEGORIES_ALIAS}" class="folder">{$cats[$catalogItem.PRODUCTS_ID].CATEGORIES_TITLE}</a>)
					{/if}
				</span>
				
			</h4>
			<p>{$catalogItem.PRODUCTS_SHORTDESCR|truncate:400}</p>
		</li>
	{foreachelse}
		<li>
			{if $data->tplAction == 'list'}
				Этот раздел пока пуст
			{elseif $data->tplAction == 'search'}
				Ничего не найдено
			{/if}
		</li>
	{/foreach}
</ol>

<!-- Pagination -->
{fun name="pagination" nav=$view}