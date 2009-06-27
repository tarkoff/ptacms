		<div class="box">
		<div class="title01-top"></div>
		<div class="title01">	
			<div class="title01-in">
				<h3 class="ico-info">Последние добавления</h3>
			</div>
		</div>
		<div class="title01-bottom"></div>
		{pta_array_chunk to="prodsList" preserve_keys="true" input=$MostRecent->products}
		{*pta_dump var=$prodsList*}
			<div class="col50">
				{foreach from=$prodsList[0] item=product}
					<div class="new-link">
						<p>
							<strong>
								<a href="{$MostRecent->brandUrl}/{$product.BRANDS_ALIAS}">{$product.BRANDS_TITLE}</a>&nbsp;
								<a href="{$MostRecent->url}/{$product.PRODUCTS_ID}">{$product.PRODUCTS_TITLE}</a>
							</strong>
						</p>
						<p>
							<span><a class="high ico-card" href="{$MostRecent->url}/{$product.PRODUCTS_ID}">Подробнее...</a></span>&nbsp;
							(<a href="{$Categories->url}{$product.CATEGORIES_ALIAS}" class="folder">{$product.CATEGORIES_TITLE}</a>)<br />
						</p>
						<p>{$product.PRODUCTS_SHORTDESCR|truncate:160}</p>
						<hr class="noscreen" />
					</div> <!-- /new-link -->
				{/foreach}
			</div> <!-- /col50 -->
			
			<div class="col50 f-right">
				{foreach from=$prodsList[1] item=product}
					<div class="new-link">
						<p>
							<strong>
								<a href="{$MostRecent->brandUrl}/{$product.BRANDS_ALIAS}">{$product.BRANDS_TITLE}</a>&nbsp;
								<a href="{$MostRecent->url}/{$product.PRODUCTS_ID}">{$product.PRODUCTS_TITLE}</a>
							</strong>
						</p>
						<p>
							<span><a class="high" href="{$MostRecent->url}/{$product.PRODUCTS_ID}" class="ico-card">Подробнее...</a></span>&nbsp;
							(<a href="{$Categories->url}{$product.CATEGORIES_ALIAS}" class="folder">{$product.CATEGORIES_TITLE}</a>)<br />
						</p>
						<p>{$product.PRODUCTS_SHORTDESCR|truncate:160}</p>
						<hr class="noscreen" />
					</div> <!-- /new-link -->
				{/foreach}
			</div> <!-- /col50 -->
		
		</div> <!-- /box -->
<!--
		<p class="t-center"><a href="">Show more &raquo;</a></p>
-->