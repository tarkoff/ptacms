{if !empty($action->img)}
	<span>
	{if $action->type == 'edit'}
		{html_image file="`$smarty.const.IMAGESURL`/view/actions/`$action->img`" alt="`$action->title`"}
	{elseif $action->type == 'add'}
		{html_image file="`$smarty.const.IMAGESURL`/view/actions/`$action->img`" alt="`$action->title`"}
	{/if}
	</span>
{/if}
	
{elseif}