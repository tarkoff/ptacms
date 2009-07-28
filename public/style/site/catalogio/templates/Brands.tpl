		<div class="title01-top"></div>
		<div class="title01">
			<div class="title01-in">
				<h3 class="ico-info">
					Каталог {$data->brand.BRANDS_TITLE}
				</h3>
			</div>
		</div>
		<div class="title01-bottom"></div>
		<p class="bb nomt">
			<a href="/">Главная</a> &raquo;
			<strong>{$data->brand.BRANDS_TITLE}</strong>
		</p>

		<!-- Pagination -->
		{defun name="pagination" nav=$data->view}
		<div class="title01-top"></div>
		<div class="title01">	
			<div class="title01-in">
				<p class="pagination">
					<a href="{$data->url}/{$data->brand.BRANDS_ALIAS}/page/{$nav->prevPage}">&laquo; Назад</a> &nbsp;
					{section name=page start=1 loop=$nav->lastPage step=1}
						{if $smarty.section.page.index == $nav->page}
							<strong>{$nav->page}</strong>
						{else}
							<a href="{$data->url}/{$data->brand.BRANDS_ALIAS}/page/{$smarty.section.page.index}">{$smarty.section.page.index}</a>
						{/if}
						{if !$smarty.section.page.index == $smarty.section.page.last}
							<span class="noscreen">,</span>
						{/if}
					{sectionelse}
						<strong>1</strong>
					{/section}
					&nbsp; <a href="{$data->url}/{$data->brand.BRANDS_ALIAS}/page/1">Вперед &raquo;</a>
				</p>
			</div>
		</div>
		<div class="title01-bottom"></div>
		{/defun}

<!--		<p>Показано <strong>1 - 10</strong> из <strong>520</strong>.</p> -->
		{*assign var="sponsoredLinks" value=$catalogObject->getSponsoredLinks()*}
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
		{foreach from=$data->view->data item=catalogItem}
			<li>
				<h4>
					<a href="{$data->url}/{$catalogItem.BRANDS_ALIAS}">{$catalogItem.BRANDS_TITLE}</a>&nbsp;
					<a href="{$data->prodUrl}/{$catalogItem.PRODUCTS_ID}">{$catalogItem.PRODUCTS_TITLE}</a> &ndash; 
					<span><a href="{$data->url}/{$catalogItem.PRODUCTS_ID}" class="high ico-card">Подробнее...</a></span>
				</h4>
				<p>{$catalogItem.PRODUCTS_SHORTDESCR|truncate:400}</p>
			</li>
		{foreachelse}
			<li>
				Этот раздел пока пуст
			</li>
		{/foreach}
		</ol>

		<!-- Pagination -->
		{fun name="pagination" nav=$data->view}