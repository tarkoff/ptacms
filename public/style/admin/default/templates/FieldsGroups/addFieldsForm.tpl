<hr class="space" />
<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}">
{assign var="formPrefix" value=`$form->prefix`}
{assign var="formData" value=`$form->data`}
	<table cols="2" cellspacing="10px" class="editFormTable">
		<tr>
			<td align="right">
				<fieldset style="width:300px;">
					<legend>{$formData.notfieldGroupFields->label}</legend>
					{include file="_generic/controls.tpl" field=$formData.notfieldGroupFields}
				</fieldset>
			</td>
			<td align="left">
				<fieldset style="width:500px;">
					<legend>{$formData.fieldGroupFields->label}</legend>
					{include file="_generic/controls.tpl" field=$formData.fieldGroupFields}
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">{include file="_generic/controls.tpl" field=`$formData.$formPrefix`}</td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="text-align:center;">
				{include file="_generic/controls.tpl" field=$formData.submit}
			</td>
		</tr>
	</table>
</form>
