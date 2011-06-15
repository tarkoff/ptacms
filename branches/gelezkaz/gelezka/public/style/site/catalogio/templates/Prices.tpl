{if $data->tplMode == 'list'}
	{include file=Prices/View.tpl data=$data->view}
{/if}
{if $data->tplMode == 'edit'}
	{include file=Prices/EditForm.tpl form=$data->EditForm}
{/if}
