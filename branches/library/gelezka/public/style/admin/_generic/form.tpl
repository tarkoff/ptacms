<hr class="space" />
<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
	<fieldset>
		<legend>{if !empty($form->title)}{$form->title}{/if}</legend>
		{foreach from=$form->data item=field}
		{if !empty($field->isSubmit) || !empty($field->hidden)}
			<dl>
				<dd style="text-align:center;">{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}</dd>
			</dl>
		{else}
			<dl>
				<dt><label for="color">{$field->label}{if $field->mandatory}*{/if}:</label></dt>
				<dd>{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}</dd>
			</dl>
		{/if}
		{/foreach}
	</fieldset>
</form>