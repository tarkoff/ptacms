<div class="viewDiv">
<div class="actions">
	{if !empty($view->singleActions)}
		{foreach from=$view->singleActions item=action}
			<a class="button positive" href="{$action->url}">
				<img 
					src="{$smarty.const.PTA_BASE_URL}/public/css/blueprint/plugins/buttons/icons/{$action->img}" 
				/> 
				{$action->title}
			</a>
		{/foreach}
	{/if}
</div>
<table class="viewTable" cellspacing="0" cellpadding="0" cols="{$view->fieldsCount}">
	<tr>
	{foreach from=$view->fields item=fieldName key=fieldAlias}
		<th class="columnNames" valign="middle">
		{$fieldAlias}
		</th>
	{/foreach}
	{if ! empty($view->commonActions)}
		<th class="columnNames" valign="middle">Actions</th>
	{/if}
	</tr>
	{foreach from=$view->data item=record}
	<tr bgcolor="{cycle values="#FFFFFF,#EEEEEE"}">
		{foreach from=$record item=field}
			<td>{$field|truncate:80}</td>
		{/foreach}
		{if ! empty($view->commonActions)}
			<td>
			{assign var="editField" value=`$view->actionField`}
			{foreach from=$view->commonActions item=action}
				<a href="{$action->url}/{$record.$editField}/">
					{html_image file="`$smarty.const.PTA_DESIGN_IMAGES_URL`/view/actions/`$action->img`" alt="`$action->title`" title="`$action->title`"}
				</a>
			{/foreach}
			</td>
		{/if}
	</tr>
	{/foreach}
</table>
<div>{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/nav.tpl" form=$data}</div>
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