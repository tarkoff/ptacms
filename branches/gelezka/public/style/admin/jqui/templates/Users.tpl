{if $data->tplMode == 'list'}
	{include file=Users/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Users/editForm.tpl data=$data->editForm}
{/if}
