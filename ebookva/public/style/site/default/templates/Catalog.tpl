{if $data->tplMode == 'mainPage'}
	{include file=TopList.tpl data=$TopList}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Fields/editForm.tpl data=$data->editForm data=$data->editForm}
{/if}
