{assign var="formPrefix" value=`$form->prefix`}
{assign var="catFields" value=`$form->categoryFields`}
{assign var="formData" value=`$form->data`}

<div class="ui-widget ui-helper-clearfix ui-corner-all">
	<div class="portlet-header ui-widget-header ui-corner-all">
		<span class="ui-icon ui-icon-circle-arrow-s"></span>{$form->title}
	</div>
	<div class="form ui-widget-content ui-corner-all">
		<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
			<table cols="2" cellspacing="10px" class="editFormTable">
				<tr>
					<td align="right" style="width:50%;">
						<fieldset class="notCategoryFields">
							<legend>{$formData.notCategoryFields->label}</legend>
							{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$formData.notCategoryFields}
						</fieldset>
					</td>
					<td align="left">
						<fieldset class="categoryFields">
							<legend>{$formData.categoryFields->label}</legend>
							{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$formData.categoryFields}
						</fieldset>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=`$formData.$formPrefix`}</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="text-align:center;">
						{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$formData.submit}
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
