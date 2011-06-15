{if $data->tplMode == 'list'}
	{include file=Currencies/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Currencies/editForm.tpl form=$data->editForm}
{/if}
