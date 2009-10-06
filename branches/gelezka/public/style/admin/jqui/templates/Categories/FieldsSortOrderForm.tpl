{* include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/form.tpl" form=$form *}

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

<div class="ui-widget ui-helper-clearfix ui-corner-all">
	<div class="portlet-header ui-widget-header ui-corner-all">
		<span class="ui-icon ui-icon-circle-arrow-s"></span>{$form->title}
	</div>
	<div class="form ui-widget-content ui-corner-all">
		<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
		<table>
			<tr>
				<td align="center" style="text-align:center;">
					<ul id="fieldsSortable">
					{foreach from=$form->data item=field}
						{if empty($field->isSubmit) && $field->prefix != $form->name}
							<li class="ui-state-default ui-corner-all" id="{$field->prefix}">
								{$field->label}
								{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}
							</li>
						{/if}
					{/foreach}
					</ul>
					<hr class="space" />
					{assign var="formName" value=$form->name}
					{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$form->data.$formName}
					{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$form->data.submit}
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
