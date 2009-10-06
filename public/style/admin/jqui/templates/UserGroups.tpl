{if $data->tplMode == 'list'}
	{include file=UserGroups/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=UserGroups/editForm.tpl data=$data->editForm}
{/if}
