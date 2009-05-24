<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
	<table cols="2" cellspacing="10px" {if !empty($formTableCss)}class="{$formTableCss}"{/if}>
	{if !empty($form->title)}
		<tr>
			<th colspan="2" {if !empty($formHeaderCss)}class="{$formHeaderCss}"{/if} align="center">{$form->title}</th>
		</tr>
	{/if}
		{foreach from=$form->data item=field}
		<tr>
		{if !empty($field->isSubmit) || !empty($field->hidden)}
			<td colspan="2" align="center">{include file="_generic/controls.tpl" field=$field}</td>
		{else}
			<td align="right">{$field->label} {if $field->mandatory}*{/if}</td>
			<td align="left">{include file="_generic/controls.tpl" field=$field}</td>
		{/if}
		</tr>
		{/foreach}
	</table>
</form>
