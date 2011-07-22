{assign var="filterField" value=$form->data.filterFields}
{assign var="orderField" value=$form->data.sortOrder}
{assign var="fieldTypeField" value=$form->data.fieldtype}
{assign var="autocomField" value=$form->data.autocomplete}
{pta_const name="PTA_Control_Form_Field::TYPE_TEXT" to="typeText"}

<div class="ui-widget ui-helper-clearfix ui-corner-all">
	<div class="portlet-header ui-widget-header ui-corner-all">
		<span class="ui-icon ui-icon-circle-arrow-s"></span>{$form->title}
	</div>
	<div class="form ui-widget-content ui-corner-all">
		<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>

			<table cols="2" cellspacing="10px" class="editFormTable">
				<tr>
					<td align="right" style="width:50%;">
					{defun name="groupWidget" legend='Static Fields' ulId='sortable1' fields=$form->notFilterFields}
						<fieldset>
							<legend> {$legend} </legend>
								<ul id="{$ulId}" class="connectedSortable">
								{foreach from=$fields item=field}
									<li class="ui-state-default ui-corner-all">
										{$field.PRODUCTSFIELDS_TITLE}
										<input type="hidden" rel="asFilter" name="{$filterField->name}[{$field.CATEGORIESFIELDS_ID}]" id="{$filterField->name}_{$field.CATEGORIESFIELDS_ID}" value="0" />
										<input type="hidden" rel="asSort" name="{$orderField->name}[{$field.CATEGORIESFIELDS_ID}]" id="{$orderField->name}_{$field.CATEGORIESFIELDS_ID}" value="0" />
										<input type="hidden" rel="asAutocmp" name="{$autocomField->name}[{$field.CATEGORIESFIELDS_ID}]" id="{$autocomField->name}_{$field.CATEGORIESFIELDS_ID}" value="0" />
										<input type="hidden" rel="asFieldType" name="{$fieldTypeField->name}[{$field.CATEGORIESFIELDS_ID}]" id="{$fieldTypeField->name}_{$field.CATEGORIESFIELDS_ID}" value="{$typeText}" />
									</li>
								{/foreach}
								</ul>
						</fieldset>
					{/defun}
					</td>
					<td>
						{fun name="groupWidget" legend='Filter Fields' ulId='sortable2' fields=$form->filterFields}
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						{assign var="formName" value=$form->name}
						{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$form->data.$formName}
						{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$form->data.submit}
					</td>
				</tr>
			</table>

		</form>
	</div>
</div>
{literal}
<style type="text/css">
	#sortable1, #sortable2 { list-style-type: none; margin: 0; padding: 0; min-height:100px; }
	#sortable1 li, #sortable2 li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em; line-height: 1.2em; }
	.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
</style>
<script type="text/javascript">
	$(function() {
		$("#sortable1, #sortable2").sortable({
			connectWith: '.connectedSortable',
			receive: function(event, ui) {
				$('#sortable1 input[rel="asFilter"]').each(function (i) {
					$(this).val(0);
				});
				$('#sortable2 input[rel="asFilter"]').each(function (i) {
					$(this).val(1);
				});
			},
			stop: function(event, ui) {
				var pos = 100;
				$('#sortable2 input[rel="asSort"]').each(function (i) {
					$(this).val(pos++);
				});
			}
		}).disableSelection();

		$("#fieldOptions").dialog({
			bgiframe: true,
			autoOpen: false,
			modal: true,
			buttons: {
				'Create an account': function() {
						$(this).dialog('close');
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			}
		});

		$("#sortable2 > li").dblclick(function (e) {
			
			$('#fieldOptions').dialog('open');
		});
		
	});
</script>
{/literal}

<div id="fieldOptions" title="Create new user">
	<p id="validateTips">All form fields are required.</p>
	<fieldset>
		<label for="fieldType">Field Type</label>
		<select name="name" id="name" class="text ui-widget-content ui-corner-all" />
		<label for="email">Email</label>
		<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
		<label for="password">Password</label>
		<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
</div>

