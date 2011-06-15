{* include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/form.tpl" form=$form *}

<link type="text/css" href="{$smarty.const.PTA_JS_JQUERY_URL}/ui/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/ui/js/jquery-ui-1.7.2.custom.min.js"></script>
{literal}
<style type="text/css">
	#fieldsSortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
	#fieldsSortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em; }
	html>body #fieldsSortable li { height: 1.5em; line-height: 1.2em; }
	.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
</style>

<script type="text/javascript">
	$(function() {
		$("#fieldsSortable").sortable({
			placeholder: 'ui-state-highlight',
			scroll: true,
			zIndex: 100,
			stop: function(event, ui) { setSortOrder(); }
		}).disableSelection();
	});

	function setSortOrder()
	{
		var fields = $("#fieldsSortable").sortable('toArray');
		var fieldsCnt = fields.length;
		var orderPsition = 100;
		var formName = "{/literal}{$form->name}{literal}";

		for(i = 0; i < fieldsCnt; i++) {
			fieldObject = $("#" + formName + "_" +fields[i]);
			fieldObject.attr({value: orderPsition++});
			//alert(fields[i] + ' = ' + fieldObject.attr("value"));
		}
	}
</script>
{/literal}

<hr class="space" />
<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}">
	<fieldset>
		<legend>{if !empty($form->title)}{$form->title}{/if}</legend>
		<ul id="fieldsSortable">
		{foreach from=$form->data item=field}
		{if empty($field->isSubmit) && $field->prefix != $form->name}
			<li class="ui-state-default" id="{$field->prefix}">
				{$field->label}
				{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}
			</li>
		{/if}
		{/foreach}
		</ul>
		<hr class="space" />
		<p style="text-align:center;">
			{assign var="formName" value=$form->name}
			{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$form->data.$formName}
			{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$form->data.submit}
		</p>
	</fieldset>
</form>