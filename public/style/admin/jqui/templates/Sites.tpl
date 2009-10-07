{if $data->tplMode == 'list'}
	{include file=Sites/view.tpl view=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Sites/editForm.tpl form=$data->editForm}
{/if}
