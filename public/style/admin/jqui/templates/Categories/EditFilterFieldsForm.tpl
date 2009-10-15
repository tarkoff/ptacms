{assign var="filterField" value=$form->data.filterFields}
<div class="ui-widget ui-helper-clearfix ui-corner-all">
	<div class="portlet-header ui-widget-header ui-corner-all">
		<span class="ui-icon ui-icon-circle-arrow-s"></span>{$form->title}
	</div>
	<div class="form ui-widget-content ui-corner-all">
		<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>

			<table cols="2" cellspacing="10px" class="editFormTable">
				<tr>
					<td align="right" style="width:50%;">
						<fieldset>
							<legend> Category Fields </legend>
								<ul id="sortable1" class="connectedSortable">
								{foreach from=$form->notFilterFields item=field}
									<li class="ui-state-default">
										{$field.PRODUCTSFIELDS_TITLE}
										<input type="hidden" name="{$filterField->name}[{$field.CATEGORIESFIELDS_ID}]" id="{$filterField->name}_{$field.CATEGORIESFIELDS_ID}" value="" />
									</li>
								{/foreach}
								</ul>
						</fieldset>
					</td>
					<td>
						<fieldset>
							<legend> Filter Fields </legend>
							<ul id="sortable2" class="connectedSortable">
							{foreach from=$form->filterFields item=field}
								<li class="ui-state-default">
									{$field.PRODUCTSFIELDS_TITLE}
									<input type="hidden" name="{$filterField->name}[{$field.CATEGORIESFIELDS_ID}]" id="{$filterField->name}_{$field.CATEGORIESFIELDS_ID}" value="1" />
								</li>
							{/foreach}
							</ul>
						</fieldset>
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
				$('#sortable1 input[type="hidden"]').each(function (i) {
					$(this).val(0);
				});
				$('#sortable2 input[type="hidden"]').each(function (i) {
					$(this).val(1);
				});
			}
			
		}).disableSelection();
	});
</script>
{/literal}
