{* include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/form.tpl" form=$form *}

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
			$("a[rel='#newValueForm']").click(function() {
				$('#newValueForm').dialog('open');
				return false;
			});

			$("#newValueForm").dialog({
				bgiframe: true,
				autoOpen: false,
				height: 216,
				modal: true,
				show: 'scale',
				buttons: {
					'Cancel': function() {
						$(this).dialog('close');
					},
					'Add Value': function() {
						$("#fieldsValuesEditForm").submit();
						$(this).dialog('close');
					}
				},
				close: function() {
				}
			});

			$("#fieldsValuesEditForm").submit(function() {
				$.post($(this).attr("action"), $(this).serialize(),
					function(data){
						$("#" + window.fieldName + "_span").load(window.location + " #" + window.fieldName);
					}
				);

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
										<a href="#" rel="#newValueForm" onClick="buildFieldValueForm('{$field->name}', {$field->fieldId})" class="btn_no_text ui-helper-clearfix ui-state-default ui-corner-all" title="New Value">
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
		{assign var="formName" value=$form->name}
		{assign var="submitationField" value=$form->data.$formName}
		{assign var="submitField" value=$form->data.submit}
		<table>
			<tr>
				<td colspan="2" style="text-align:center;">
					{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$submitationField}
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$submitField}
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>

<div id="newValueForm" title="Add new value" class="newValueForm">
	<form name="fieldsValuesEditForm" id="fieldsValuesEditForm" action="#" method="post">
	<div id="submitConfirmation"></div>
	<fieldset>
		<label for="fieldsValuesEditForm_newValue">New Value</label>
		<input type="text" name="fieldsValuesEditForm_newValue" id="fieldsValuesEditForm_newValue" class="text ui-widget-content ui-corner-all" />
		<input type="hidden" name="fieldsValuesEditForm_submit" value="Add New Value" />
	</fieldset>
	</form>
</div>
