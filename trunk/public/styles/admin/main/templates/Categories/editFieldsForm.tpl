<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
	<table cols="3" cellspacing="10px" {if !empty($formTableCss)}class="{$formTableCss}"{/if}>
	{if !empty($form->title)}
		<tr>
			<th align="center">Field Name</th>
			<th align="center"></th>
			<th align="center">Sort Order</th>
		</tr>
	{/if}
		{foreach from=$form->data item=field }
		<tr>
		{if !empty($field->isSubmit)}
			<td colspan="2" align="center">{include file="_generic/controls.tpl" field=$field}</td>
		{else}
			<td align="right">{$field->label}</td>
			<td align="center">
				<input type="checkbox" name="{$form->prefix}_fields[{$field->properties->fieldId}]" {if !empty($field->value)}checked="checked"{/if} {if !empty($field->cssClass)}class="{$field->cssClass}"{/if}{if !empty($field->disabled)} disabled="disabled"{/if}/>
				{$field->name}
			</td>
			<td align="left">{include file="_generic/controls.tpl" field=$field}</td>
		{/if}
		</tr>
		{/foreach}
	</table>
</form>