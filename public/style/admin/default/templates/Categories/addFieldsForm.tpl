<hr class="space" />
<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" class="editForm">
{assign var="formPrefix" value=`$form->prefix`}
{assign var="catFields" value=`$form->categoryFields`}
{assign var="formData" value=`$form->data`}
	<table cols="2" cellspacing="10px" class="editFormTable">
	{if !empty($form->title)}
		<tr>
			<th colspan="2" class="editFormHeader" align="center">{$form->title}</th>
		</tr>
	{/if}
		<tr>
			<td align="right">
				<fieldset class="notCategoryFields" style="width:300px;">
					<legend>{$formData.notCategoryFields->label}</legend>
					{include file="_generic/controls.tpl" field=$formData.notCategoryFields}
				</fieldset>
			</td>
			<td align="left">
				<fieldset class="categoryFields" style="width:500px;">
					<legend>{$formData.categoryFields->label}</legend>
					{include file="_generic/controls.tpl" field=$formData.categoryFields}
					<br />
					{*include file="_generic/controls.tpl" field=$formData.fieldSortOrder*}
					{*pta_dump var=$catFields*}
					<table>
					{foreach from=$catFields item=category}
					<tr>
					<td><label for="{$form->name}_sortOrder[{$category.PRODUCTSFIELDS_ID}]">"{$category.PRODUCTSFIELDS_TITLE}" Sort Order:</label></td>
					<td>
						<input 
							type="text" 
							name="{$form->name}_sortOrder[{$category.PRODUCTSFIELDS_ID}]" 
							id="{$form->name}_sortOrder[{$category.PRODUCTSFIELDS_ID}]" 
							value={$category.CATEGORIESFIELDS_SORTORDER} 
						/>
					</td>
					</tr>
					{/foreach}
					</table>
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
{literal}
<script type="text/javascript">
	$(document).ready(function(){
		var sortOrderField = $("#{/literal}{$formData.fieldSortOrder->name}{literal}");
		
		$("#{/literal}{$formData.categoryFields->name}{literal}").change(function () {
			$("#{/literal}{$formData.categoryFields->name}{literal} option:selected").each(function () {
				sortOrderField.val(
					$("#{/literal}{$form->name}{literal}_sortOrder\\[" + $(this).val() + "\\]").val()
				);
			});
		}).change();
	});
</script>
{/literal}