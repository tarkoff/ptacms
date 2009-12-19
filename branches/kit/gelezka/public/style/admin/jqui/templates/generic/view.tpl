{* include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/view.tpl" view=$data *}
<div id="actions" class="actions ui-widget ui-widget-content ui-helper-clearfix" style="display:block;">
	<div id="commonActions" class="comActions">
	{if !empty($view->singleActions)}
		{foreach from=$view->singleActions item=action}
			<a class="btn ui-state-default ui-corner-all" href="{$action->url}">
				<span class="ui-icon {$action->img}"></span>{$action->title|default:'New Item'}
			</a>&nbsp;
		{/foreach}
	{/if}
	{if !empty($filterForm)}
		<a class="btn ui-state-default ui-corner-all" href="#" id="filterOpen">
			<span class="ui-icon ui-icon-search"></span>Filter
		</a>
	{/if}
	</div>
	<div><span id="filterForm"></span></div>
</div>
<hr class="space" />

<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery.quicksearch.js"></script>
{literal}
<script type="text/javascript">
	$(document).ready(function() {
		$("#viewTable").tablesorter();

		$('table#viewTable tbody tr').quicksearch({
			position: 'before',
			attached: 'span#filterForm',
			formId: 'view_searchForm',
			labelText: 'Search:',
			loaderText: ''
		});
	});
</script>
{/literal}

<div id="{$view->prefix}" class="view">
	<table cellspacing="0" cellpadding="0" cols="{$view->fieldsCount}" id="viewTable">
		<thead>
			<tr>
			{foreach from=$view->fields item=fieldName key=fieldAlias}
				<th valign="middle">{$fieldAlias}</th>
			{/foreach}
			{if ! empty($view->commonActions)}
				<th class="columnNames" valign="middle">Actions</th>
			{/if}
			</tr>
		</thead>
		<tbody>
		{foreach from=$view->data item=record}
			<tr class="{cycle values="even,odd"}">
			{foreach from=$record item=field}
				<td>{$field|truncate:80}</td>
			{/foreach}
			{if ! empty($view->commonActions)}
				<td>
				{assign var="editField" value=`$view->actionField`}
				{foreach from=$view->commonActions item=action}
					<a href="{$action->url}/{$record.$editField}/" class="btn_no_text ui-state-default ui-corner-all" title="{$action->title}">
						<span class="ui-icon {$action->img}"></span>
					</a>
				{/foreach}
				</td>
			{/if}
			</tr>
		{/foreach}
		</tbody>
	</table>
	<div id="pagination" class="pagination ui-widget ui-widget-content ui-helper-clearfix">
		<select name="rpp" id="rpp" class="rpp">
		{foreach from=$view->rpps key=key item=rpp}
			{if $view->rpp == $rpp}
				<option value="{$key}" selected="selected">{$rpp}</option>
			{else}
				<option value="{$key}">{$rpp}</option>
			{/if}
		{/foreach}
		</select>
		<ul>
			{if $view->page <= $view->prevPage}
				<li class="unactive ui-state-default ui-corner-all ui-state-disabled">« Prev.</li>
			{else}
				<li class="ui-state-default ui-corner-all"><a href="{$activeModule->url}view/page/{$view->prevPage}?{$smarty.server.QUERY_STRING}">« Prev.</a></li>
			{/if}
			{math equation="x + y" x=$view->lastPage y=1 assign="lastPage"}
			{section name=page start=1 loop=$lastPage step=1}
				{if $smarty.section.page.index == $view->page}
					<li class="unactive ui-state-default ui-corner-all ui-state-disabled">{$view->page}</li>
				{else}
					<li class="ui-state-default ui-corner-all"><a href="{$activeModule->url}view/page/{$smarty.section.page.index}?{$smarty.server.QUERY_STRING}">{$smarty.section.page.index}</a></li>
				{/if}
			{sectionelse}
				<li class="unactive ui-state-default ui-corner-all ui-state-disabled">1</li>
			{/section}
			{if $view->page >= $view->lastPage}
				<li class="unactive ui-state-default ui-corner-all ui-state-disabled">Next »</li>
			{else}
				<li class="ui-state-default ui-corner-all"><a href="{$activeModule->url}view/page/{$view->nextPage}?{$smarty.server.QUERY_STRING}">Next »</a></li>
			{/if}
		</ul>
	</div>
</div>
{if !empty($filterForm)}
{assign var="formName" value=$filterForm->name}
{assign var="filterData" value=$filterForm->filterData}
<div id="filterDiv" title="Filter">
	<p>
		<form id="{$formName}" name="{$formName}" method="GET" action="?">
			{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=`$filterForm->data.$formName`}
			<table>
			{foreach from=$view->fields item=fieldName key=fieldAlias}
				{if !empty($filterData.$fieldAlias.value)}
					{assign var="filter" value=$filterData.$fieldAlias}
				{else}
					{assign var="filter" value=""}
				{/if}
				<tr>
					<th>{$fieldAlias}</th>
					<td>
						<select name="{$formName}_{$fieldAlias}_cond">
						{foreach from=$filterForm->conditions item=condTitle key=condValue}
							<option value="{$condValue}" {if !empty($filter.cond) && $filter.cond == $condValue}selected="selected"{/if}>{$condTitle}</option>
						{/foreach}
						</select>
					</td>
					<td><input type="text" name="{$formName}_{$fieldAlias}_value" {if !empty($filter.value)}value="{$filter.value}"{/if} /></td>
				</tr>
			{/foreach}
			</table>
		</form>
	</p>
</div>
{/if}