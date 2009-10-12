{* include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/form.tpl" form=$form *}

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
			$("a[rel='#newValueForm']").overlay(); 
			$("#fieldsValuesEditForm").submit(function() {
				$.post($(this).attr("action"), $(this).serialize(),
					function(data){
						$("#" + window.fieldName + "_span").load(window.location + " #" + window.fieldName);
					}
				);
				$("a[rel='#newValueForm']").each(function(i) {
					$(this).overlay().close();
				}); 
				return false;
			});
		});
	</script>
{/literal}



<div class="ui-widget ui-helper-clearfix ui-corner-all">
	<div class="portlet-header ui-widget-header ui-corner-all">
		<span class="ui-icon ui-icon-circle-arrow-s"></span>{$form->title}
	</div>
	<div class="form ui-widget-content ui-corner-all">
		<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
		{defun name="groupWidget" groupTitle='Static Fields' groupId=0}
			<div class="ui-widget ui-helper-clearfix ui-corner-all">
				<div class="portlet-header ui-widget-header ui-corner-all">
					<span class="ui-icon ui-icon-circle-arrow-s"></span>{$groupTitle}
				</div>
				<div class="form ui-widget-content ui-corner-all">
					<table>
					{foreach from=$form->data item=field}
						{if isset($field->groupId) && $field->groupId == $groupId}
							<tr>
							{if !empty($field->isSubmit) || !empty($field->hidden)}
								<td colspan="2" style="text-align:center;">{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}</td>
							{else}
								<td class="fieldTitle"><label for="color">{$field->label}{if $field->mandatory}*{/if}</label></td>
								<td>
									<span id="{$field->name}_span" style="float:left;margin-right:3px;">{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}</span>
									{if !empty($field->fieldId)}
										<a href="javascript:return false;" rel="#newValueForm" onClick="buildFieldValueForm('{$field->name}', {$field->fieldId})" class="btn_no_text ui-helper-clearfix ui-state-default ui-corner-all" title="New Value">
											<span class="ui-icon ui-icon-plus"></span>
										</a>
									{/if}
								</td>
							{/if}
							</tr>
						{/if}
					{/foreach}
					</table>
				</div>
			</div>
			<br />
		{/defun}
		{foreach from = $form->fieldGroups item = group key = groupId}
			{fun name="groupWidget" groupTitle=$group groupId=$groupId}
		{/foreach}
		<table>
			{foreach from=$form->data item=field}
				{if !empty($field->isSubmit) || !empty($field->hidden)}
					<tr><td colspan="2" style="text-align:center;">{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$field}</td></tr>
				{/if}
			{/foreach}
		</table>
		</form>
	</div>
</div>

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
