<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" class="editForm">
{assign var="formPrefix" value=`$form->prefix`}
{assign var="form" value=`$form->data`}
	<table cols="2" cellspacing="10px" class="editFormTable">
	{if !empty($form->title)}
		<tr>
			<th colspan="2" class="editFormHeader" align="center">{$form->title}</th>
		</tr>
	{/if}
		<tr>
			<td align="right">
				<fieldset class="notCategoryFields">
					<legend>{$form.notCategoryFields->label}</legend>
					{include file="_generic/controls.tpl" field=$form.notCategoryFields}
				</fieldset>
			</td>
			<td align="left">
				<fieldset class="categoryFields">
					<legend>{$form.categoryFields->label}</legend>
					{include file="_generic/controls.tpl" field=$form.categoryFields}
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">{include file="_generic/controls.tpl" field=`$form.$formPrefix`}</td>
		</tr>
		<tr>
			<td colspan="2" align="center">{include file="_generic/controls.tpl" field=$form.submit}</td>
		</tr>
	</table>
</form>
