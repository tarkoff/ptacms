{* include file="_generic/form.tpl" form=$form *}

<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/tools/overlay/tools.overlay-1.0.4.min.js"></script>
<link type="text/css" href="{$smarty.const.PTA_JS_JQUERY_URL}/tools/overlay/overlay-minimal.css" rel="stylesheet" />

{literal}
	<script type="text/javascript">
		function buildFieldValueForm(fieldName, fieldId)
		{
			var editLink = "{/literal}{$smarty.const.PTA_ADMIN_URL}{literal}/Fields/EditFieldValues/Field/" +fieldId + "/";
			window.fieldName = fieldName;

			$("#submitConfirmation").load(editLink + " #fieldsValuesEditForm_fieldsValuesEditForm");
			$("#fieldsValuesEditForm").attr({action: editLink});
		}

		$(document).ready(function(){
			$("button[rel]").overlay(); 
			$("#fieldsValuesEditForm").submit(function() {
				$.post($(this).attr("action"), $(this).serialize(),
					function(data){
						$("#" + window.fieldName + "_span").load(window.location + " #" + window.fieldName);
					}
				);
				$("button[rel]").each(function(i) {
					$(this).overlay().close();
				}); 
				return false;
			});
		});
	</script>
{/literal}

<hr class="space" />
<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
	<fieldset>
		<legend>{if !empty($form->title)}{$form->title}{/if}</legend>
		{foreach from=$form->data item=field}
		{if !empty($field->isSubmit) || !empty($field->hidden)}
			<dl>
				<dd style="text-align:center;">{include file="_generic/controls.tpl" field=$field}</dd>
			</dl>
		{else}
			<dl>
				<dt><label for="color">{$field->label}{if $field->mandatory}*{/if}:</label></dt>
				<dd style="line-height:25px;">
					<span id="{$field->name}_span" style="float:left;margin-right:3px;">{include file="_generic/controls.tpl" field=$field}</span>
					{if !empty($field->fieldId)}
						<button rel="#newValueForm" type="button" onClick="buildFieldValueForm('{$field->name}', {$field->fieldId})">New Value</button>
					{/if}
				</dd>
			</dl>
		{/if}
		{/foreach}
	</fieldset>
</form>

<div class="overlay" id="newValueForm">
	<form name="fieldsValuesEditForm" id="fieldsValuesEditForm" action="#" method="post" style="width:100%;height:100%;">
		<table style="width:100%;height:100%;">
			<tr><td colspan="2"><div id="submitConfirmation"></div></td></tr>
			<tr>
				<td>New Value</td>
				<td><input type="text" name="fieldsValuesEditForm_newValue" value="" /></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<input type="submit" name="fieldsValuesEditForm_submit" value="Add New Value" />
				</td>
			</tr>
		</table>
	</form>
</div> 
