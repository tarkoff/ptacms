{if !empty($Categories->selected)}
	{include file=Categories/CategoryView.tpl}
{else}
	{include file=Categories/MainPage.tpl}
{/if}
