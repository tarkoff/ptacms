{if $data->tplMode == 'list'}
	{include file=Categories/view.tpl view=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Categories/editForm.tpl data=$data->editForm}
{/if}
{if $data->tplMode == 'addFields'}
	{include file=Categories/addFieldsForm.tpl form=$data->addFieldsForm}
{/if}
{if $data->tplMode == 'addProduct'}
	{include file=Catalog/editForm.tpl form=$data->addProductForm}
{/if}
{if $data->tplMode == 'EditFieldsSortOrder'}
	{if !empty($data->fieldsSortOrderForm)}
		{include file=Categories/FieldsSortOrderForm.tpl form=$data->fieldsSortOrderForm}
	{/if}
{/if}
{if $data->tplMode == 'EditGroupsSortOrder'}
	{if !empty($data->groupsSortOrderForm)}
		{include file=Categories/GroupsSortOrderForm.tpl form=$data->groupsSortOrderForm}
	{/if}
{/if}
