{if $data->tplMode == 'list'}
	{include file=Fields/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Fields/editForm.tpl data=$data->editForm data=$data->editForm}
{/if}
{if $data->tplMode == 'EditFieldValues'}
	{include file=Fields/editFieldsValuesForm.tpl data=$data->editForm data=$data->editForm}
{/if}
