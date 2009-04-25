{if $data->tplMode == 'list'}
	{include file=menuBulder/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=menuBuilder/editForm.tpl data=$data->editForm}
{/if}
