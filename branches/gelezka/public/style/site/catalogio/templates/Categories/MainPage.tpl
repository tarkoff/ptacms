		<!-- Catalog -->
		<div class="box">

			<div id="col-l">

				<div class="title01-top"></div>
				<div class="title01">
					<div class="title01-in">
						<!-- <p class="f-right noprint"><strong><a href="" class="add">Submit a Site</a></strong></p> -->
						<h2 class="ico-list">Разделы</h2>
					</div>
				</div>
				<div class="title01-bottom"></div>
					{assign var="catsList" value=$Categories->object->getCategoriesTree()}
					{pta_array_chunk to="cats" preserve_keys="true" input=$catsList.childs}
				<div class="box">
				<dl class="cat">
				{foreach from=$cats[0] item=category}
					<dt><a href="{$Categories->url}/{$category.CATEGORIES_ALIAS}">{$category.CATEGORIES_TITLE}</a></dt>
					<dd>
					{foreach from=$category.childs item=subCategory name=subCats}
						<a href="{$Categories->url}/{$subCategory.CATEGORIES_ALIAS}">{$subCategory.CATEGORIES_TITLE}</a>{if !$smarty.foreach.subCats.last}, {/if}
					{/foreach}
					</dd>
				{/foreach}
				</dl>

				<dl class="cat f-right">
				{foreach from=$cats[1] item=category}
					<dt><a href="{$Categories->url}/{$category.CATEGORIES_ALIAS}">{$category.CATEGORIES_TITLE}</a></dt>
					<dd>
					{foreach from=$category.childs item=subCategory name=subCats}
						<a href="{$Categories->url}/{$subCategory.CATEGORIES_ALIAS}">{$subCategory.CATEGORIES_TITLE}</a>{if !$smarty.foreach.subCats.last}, {/if}
					{/foreach}
					</dd>
				{/foreach}
				</dl>
				</div> 
				<!-- /box -->

				<hr class="noscreen" />
			</div> <!-- /col-l -->

			<!-- Sidebar -->
			<div id="col-r" class="noprint">
				{include file="Users/LoginForm.tpl"}
				{*include file="Catalog/Tabs.tpl"*}
			</div> <!-- /col-r -->
		</div> <!-- /box -->
		{include file="Catalog/MostRecent.tpl"}