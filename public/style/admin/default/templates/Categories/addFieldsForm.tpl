<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
{assign var="formPrefix" value=`$form->prefix`}
{assign var="form" value=`$form->data`}
	<table cols="2" cellspacing="10px" {if !empty($formTableCss)}class="{$formTableCss}"{/if}>
	{if !empty($form->title)}
		<tr>
			<th colspan="2" {if !empty($formHeaderCss)}class="{$formHeaderCss}"{/if} align="center">{$form->title}</th>
		</tr>
	{/if}
		<tr>
			<td align="right">
				<fieldset>
					<legend>{$form.notCategoryFields->label}</legend>
					{include file="_generic/controls.tpl" field=$form.notCategoryFields}
				</fieldset>
			</td>
			<td align="left">
				<fieldset>
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
