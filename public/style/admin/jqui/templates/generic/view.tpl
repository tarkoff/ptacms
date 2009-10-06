{* include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/view.tpl" view=$data *}
<div id="actions" class="actions ui-widget ui-widget-content ui-helper-clearfix" style="display:block;">
	{if !empty($view->singleActions)}
	<div id="commonActions" class="comActions">
		{foreach from=$view->singleActions item=action}
			<a class="btn ui-state-default ui-corner-all" href="{$action->url}">
				<span class="ui-icon {$action->img}"></span>{$action->title|default:'New Item'}
			</a>
		{/foreach}
	</div>
	{/if}
	<div>
	<span id="filterForm"></span>
	</div>
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
		<select name="rpp" id="rpp" onchange="setRpp(this.value)" class="rpp">
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
				<li class="unactive btn ui-state-default ui-corner-all ui-state-disabled">« Prev.</li>
			{else}
				<li class="btn ui-state-default ui-corner-all"><a href="{$activeModule->url}view/page/{$view->prevPage}">« Prev.</a></li>
			{/if}
			{math equation="x + y" x=$view->lastPage y=1 assign="lastPage"}
			{section name=page start=1 loop=$lastPage step=1}
				{if $smarty.section.page.index == $view->page}
					<li class="unactive btn ui-state-default ui-corner-all ui-state-disabled">{$view->page}</li>
				{else}
					<li class="btn ui-state-default ui-corner-all"><a href="{$activeModule->url}view/page/{$smarty.section.page.index}">{$smarty.section.page.index}</a></li>
				{/if}
			{sectionelse}
				<li class="unactive btn ui-state-default ui-corner-all ui-state-disabled">1</li>
			{/section}
			{if $view->page >= $view->lastPage}
				<li class="unactive btn ui-state-default ui-corner-all ui-state-disabled">Next »</li>
			{else}
				<li class="btn ui-state-default ui-corner-all"><a href="{$activeModule->url}view/page/{$view->nextPage}">Next »</a></li>
			{/if}
		</ul>
	</div>
</div>
{literal}
<script type="text/javascript" language="JavaScript">
function setRpp(rpp)
{
	var ww;
	ww = '?rpp=' + rpp;
 
	location.href = ww;
}
</script>
{/literal}