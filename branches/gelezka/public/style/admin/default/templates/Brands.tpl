{if $data->tplMode == 'list'}
	{include file=Brands/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Brands/editForm.tpl data=$data->editForm data=$data->editForm}
{/if}
