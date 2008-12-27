{if $data->tplMode == 'list'}
	{include file=Categories/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Categories/editForm.tpl data=$data->editForm}
{/if}
{if $data->tplMode == 'categoryFields'}
	{include file=Categories/editFieldsForm.tpl form=$data->editFieldsForm}
{/if}
