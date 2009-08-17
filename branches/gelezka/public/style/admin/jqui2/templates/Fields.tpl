{if $Fields->tplMode == 'list'}
	{include file=Fields/view.tpl view=$Fields->view}
{/if}
{if $Fields->tplMode == 'edit'}
	{include file=Fields/editForm.tpl form=$Fields->editForm}
{/if}
{if $Fields->tplMode == 'EditFieldValues'}
	{include file=Fields/editFieldsValuesForm.tpl form=$Fields->fieldsValuesEditForm}
{/if}
