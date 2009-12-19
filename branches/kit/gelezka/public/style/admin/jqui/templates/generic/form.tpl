<div class="ui-widget ui-helper-clearfix ui-corner-all">
	<div class="portlet-header ui-widget-header ui-corner-all">
		<span class="ui-icon ui-icon-circle-arrow-s"></span>{$form->title}
	</div>
	<div class="form ui-widget-content ui-corner-all">
		<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
		<table>
		{foreach from=$form->data item=field}
			<tr>
			{if !empty($field->isSubmit) || !empty($field->hidden)}
				<td colspan="2" style="text-align:center;">
					{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}
				</td>
			{else}
				<td class="fieldTitle"><label for="color">{$field->label}{if $field->mandatory}*{/if}</label></td>
				<td class="fieldValue">{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}</td>
			{/if}
			</tr>
		{/foreach}
		</table>
		</form>
	</div>
</div>