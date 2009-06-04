{if $data->tplMode == 'list'}
	{include file=Catalog/view.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Catalog/editForm.tpl data=$data->editForm}
{/if}
{if $data->tplMode == 'editPhotos'}
	{include file=Catalog/EditPhotosForm.tpl form=$data->EditPhotosForm}
{/if}
