{if $data->tplMode == 'list'}
	{include file=Themes/view.tpl view=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Themes/editForm.tpl form=$data->editForm}
{/if}
