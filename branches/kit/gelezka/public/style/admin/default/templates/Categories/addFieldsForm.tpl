<hr class="space" />
<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" class="editForm">
{assign var="formPrefix" value=`$form->prefix`}
{assign var="catFields" value=`$form->categoryFields`}
{assign var="formData" value=`$form->data`}
	<table cols="2" cellspacing="10px" class="editFormTable">
		<tr>
			<td align="right">
				<fieldset class="notCategoryFields" style="width:300px;">
					<legend>{$formData.notCategoryFields->label}</legend>
					{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$formData.notCategoryFields}
				</fieldset>
			</td>
			<td align="left">
				<fieldset class="categoryFields" style="width:500px;">
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
