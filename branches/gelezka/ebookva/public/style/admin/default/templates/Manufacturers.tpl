{if $data->tplMode == 'list'}
	{include file="Manufacturers/view.tpl" data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file="Manufacturers/editForm.tpl" data=$data->editForm data=$data->editForm}
{/if}
