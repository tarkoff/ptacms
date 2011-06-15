{if $data->tplMode == 'list'}
	{include file=Posts/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Posts/editForm.tpl form=$data->editForm}
{/if}
