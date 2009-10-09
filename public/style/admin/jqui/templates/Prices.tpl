{if $data->tplMode == 'list'}
	{include file=Prices/view.tpl view=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Prices/editForm.tpl form=$data->editForm}
{/if}
