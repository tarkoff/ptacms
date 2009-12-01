{assign var=catalogObject value=$data->object}
{assign var="categoriesObject" value=$Categories->object}
{assign var=subCategories value=$categoriesObject->getSubCategories($Categories->category.CATEGORIES_ID, 1) }
		<div class="title01-top"></div>
		<div class="title01">
			<div class="title01-in">
				<h3 class="ico-info">
				{if $data->tplAction == 'list'}
					{$Categories->category.CATEGORIES_TITLE}
				{elseif $data->tplAction == 'filter'}
					{$data->searchRequest.fieldTitle} &raquo; {$data->searchRequest.fieldValue}
				{elseif $data->tplAction == 'search'}
					Резултаты поиска: '{$data->searchRequest}'
				{/if}
				</h3>
			</div>
		</div>
		<div class="title01-bottom"></div>
		<p class="bb">
			<a href="/">Главная</a> &raquo;
			{if $data->tplAction == 'list'}
				{assign var=categories value=$categoriesObject->getParentCategories($Categories->category.CATEGORIES_ID)}
				{defun name="cattree" categories=$categories}
					{foreach from=$categories item=category}
						{if !empty($category.childs)}
							<a href="{$Categories->url}/{$category.CATEGORIES_ALIAS}">{$category.CATEGORIES_TITLE}</a> &raquo;
							{fun name="cattree" categories=$category.childs}
						{else}<strong>{$category.CATEGORIES_TITLE}</strong>{/if}
					{/foreach}
				{/defun}
				{assign var="pagerUrl" value="`$Categories->url`/`$Categories->category.CATEGORIES_ALIAS`"}
			{elseif $data->tplAction == 'filter'}
				{$data->searchRequest.fieldTitle} &raquo; {$data->searchRequest.fieldValue}
				{assign var="pagerUrl" value="`$smarty.const.PTA_BASE_URL`/Catalog/Filter/Value/`$data->searchRequest.valueId`"}
			{elseif $data->tplAction == 'search'}
				{assign var="pagerUrl" value="`$Categories->url`/`$Categories->category.CATEGORIES_ALIAS`"}
				<strong>{$data->searchRequest}</strong>
			{/if}
		</p>

{if !empty($subCategories)}
	<!-- Subcategories -->
	<h4>Подразделы:</h4>
	<ul class="ul-categories box">
	{foreach from=$subCategories item=category}
		<li><a href="{$Categories->url}/{$category.CATEGORIES_ALIAS}">{$category.CATEGORIES_TITLE}</a> ({$category.PRODS_CNT|default:'0'})</li>
	{/foreach}
	</ul>
{/if}

{include file="Catalog/Pager.tpl" view = $data->view data = $data pagerUrl = $pagerUrl}
{* include file="ads/MixMarket_ForOffice_Banner.tpl" mode = 'js' *}
