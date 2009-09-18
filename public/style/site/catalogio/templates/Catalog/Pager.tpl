<!-- Pagination -->
{defun name="pagination" nav=$view}
	<div class="title01-top"></div>
	<div class="title01">
		<div class="title01-in">
			<p class="pagination">
				<a href="{$Categories->url}/{$Categories->category.CATEGORIES_ALIAS}/page/{$nav->prevPage}">&laquo; Назад</a> &nbsp;
				{math equation="x + y" x=$nav->lastPage y=1 assign="lastPage"}
				{section name=page start=1 loop=$lastPage step=1}
					{if $smarty.section.page.index == $nav->page}
						<strong>{$nav->page}</strong>
					{else}
						<a href="{$Categories->url}/{$Categories->category.CATEGORIES_ALIAS}/page/{$smarty.section.page.index}">{$smarty.section.page.index}</a>
					{/if}
					{if $smarty.section.page.index != $smarty.section.page.last}
						<span class="noscreen">,</span>
					{/if}
				{sectionelse}
					<strong>1</strong>
				{/section}
				&nbsp; <a href="{$Categories->url}/{$Categories->category.CATEGORIES_ALIAS}/page/{$nav->nextPage}">Вперед &raquo;</a>
			</p>
		</div>
	</div>
	<div class="title01-bottom"></div>
{/defun}

<!-- <p>Показано <strong>1 - 10</strong> из <strong>520</strong>.</p> -->
{if !empty($catalogObject)}
	{assign var="sponsoredLinks" value=$catalogObject->getSponsoredLinks()}
{/if}
{if !empty($sponsoredLinks)}
	<!-- Sponsored links -->
	<div class="title03-top"></div>
	<div class="title03">

		<h3 class="nom">Лучшие предложения:</h3>

		<ol class="cat-results">
			<li>
				<h4><a href="">Lorem ipsum dolor sit amete</a> &ndash; <span><a href="" class="ico-card">Details</a></span></h4>
				<p><span class="high">http://www.server.com/index.php</span><br />
				Lorem ipsum dolor sit amete, consectetuer adipiscing elit. Integer eget risus a ante gravida suscipit. Maecenas luctus metus. Pellentesque habitant esti. Lorem ipsum dolor sit amete, consectetuer adipiscing elit. Integer eget risus a ante gravida suscipit. Maecenas luctus metus. Pellentesque esti.</p>
			</li>
			<li>
				<h4><a href="">Lorem ipsum dolor sit amete</a> &ndash; <span><a href="" class="ico-card">Details</a></span></h4>
				<p><span class="high">http://www.server.com/index.php</span><br />
				Lorem ipsum dolor sit amete, consectetuer adipiscing elit. Integer eget risus a ante gravida suscipit. Maecenas luctus metus. Pellentesque habitant esti. Lorem ipsum dolor sit amete, consectetuer adipiscing elit. Integer eget risus a ante gravida suscipit. Maecenas luctus metus. Pellentesque esti.</p>
			</li>
			<li>
				<h4><a href="">Lorem ipsum dolor sit amete</a> &ndash; <span><a href="" class="ico-card">Details</a></span></h4>
				<p><span class="high">http://www.server.com/index.php</span><br />
				Lorem ipsum dolor sit amete, consectetuer adipiscing elit. Integer eget risus a ante gravida suscipit. Maecenas luctus metus. Pellentesque habitant esti. Lorem ipsum dolor sit amete, consectetuer adipiscing elit. Integer eget risus a ante gravida suscipit. Maecenas luctus metus. Pellentesque esti.</p>
			</li>
		</ol>

	</div>
	<div class="title03-bottom"></div>
{/if}

<ol class="cat-results">
	{if !empty($view->prodsDefaultCats)}
		{assign var="cats" value=$view->prodsDefaultCats}
	{/if}
	{foreach from=$view->data item=catalogItem}
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
