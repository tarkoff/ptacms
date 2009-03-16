<div class="viewDiv">
<table class="viewTable" cellspacing="0" cellpadding="0" cols="{$view->fieldsCount}">
	{if !empty($view->singleActions)}
		<tr>
			<th colspan="{$view->fieldsCount}" style="text-align:left;">
				<div class="mtb">
				{foreach from=$view->singleActions item=action}
					<span class="mtbItem">
						<a href="{$action->url}">
						{if !empty($action->img)}
							<span>{html_image file="`$smarty.const.IMAGESURL`/view/actions/`$action->img`" alt="`$action->title`"}</span>
						{/if}
							<span>{$action->title}</span>
						</a>
					</span>
					<em class="btnseparator"></em>
				{/foreach}
				</div>
			</th>
		</tr>
	{/if}
	<tr>
	{foreach from=$view->fields item=fieldTitle}
		<th class="columnNames" valign="middle">
		{$fieldTitle}
		</th>
	{/foreach}
	</tr>
	{foreach from=$view->data item=record}
	<tr bgcolor="{cycle values="#FFFFFF,#EEEEEE"}">
		{foreach from=$record item=field}
			<td>{$field}</td>
		{/foreach}
		{if ! empty($view->commonActions)}
			<td>
			{assign var="editField" value=`$view->actionField`}
			{foreach from=$view->commonActions item=action}
				<a href="{$action->url}/{$record.$editField}/">
					{html_image file="`$smarty.const.IMAGESURL`/view/actions/`$action->img`" alt="`$action->title`" title="`$action->title`"}
				</a>
			{/foreach}
			</td>
		{/if}
	</tr>
	{/foreach}
	<tr>
		<th colspan="{$view->fieldsCount}" style="text-align:left;" valign="middle" height="30px">
			{include file="_generic/nav.tpl" form=$data}
		</th>
	</tr>
</table>
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