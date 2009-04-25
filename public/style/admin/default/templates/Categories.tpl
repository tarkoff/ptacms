{if $data->tplMode == 'list'}
	{include file=Categories/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Categories/editForm.tpl data=$data->editForm}
{/if}
{if $data->tplMode == 'addFields'}
	{include file=Categories/addFieldsForm.tpl form=$data->addFieldsForm}
{/if}
{if $data->tplMode == 'delFields'}
	{include file=Categories/delFieldsForm.tpl form=$data->delFieldsForm}
{/if}
{if $data->tplMode == 'addProduct'}
	{include file=Categories/addProductForm.tpl form=$data->addProductForm}
{/if}
