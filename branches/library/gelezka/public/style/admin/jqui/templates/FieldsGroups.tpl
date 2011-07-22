{if $data->tplMode == 'list'}
	{include file=FieldsGroups/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=FieldsGroups/editForm.tpl form=$data->editForm}
{/if}
{if $data->tplMode == 'editFields'}
	{include file=FieldsGroups/addFieldsForm.tpl form=$data->addFieldsForm}
{/if}
