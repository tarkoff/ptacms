{if $data->tplMode == 'list'}
	{include file=Categories/view.tpl view=$data->view}
{elseif $data->tplMode == 'edit'}
	{include file=Categories/editForm.tpl data=$data->editForm}
{elseif $data->tplMode == 'addFields'}
	{include file=Categories/addFieldsForm.tpl form=$data->addFieldsForm}
{elseif $data->tplMode == 'addProduct'}
	{include file=Catalog/editForm.tpl form=$data->addProductForm}
{elseif $data->tplMode == 'EditFieldsSortOrder' && !empty($data->fieldsSortOrderForm)}
	{include file=Categories/FieldsSortOrderForm.tpl form=$data->fieldsSortOrderForm}
{elseif $data->tplMode == 'EditGroupsSortOrder' && !empty($data->groupsSortOrderForm)}
	{include file=Categories/GroupsSortOrderForm.tpl form=$data->groupsSortOrderForm}
{elseif $data->tplMode == 'EditFilterFields' && !empty($data->filterFieldsForm)}
	{include file=Categories/EditFilterFieldsForm.tpl form=$data->filterFieldsForm}
{/if}
