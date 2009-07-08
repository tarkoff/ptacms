{pta_const to="typeSuccess" name=PTA_Object::MESSAGE_SUCCESS}
{pta_const to="typeError" name=PTA_Object::MESSAGE_ERROR}
<hr class="space"/>
<div id="messages" class="container">
	{if !empty($messages)}
		{foreach from=$messages item=message}
			<div class="{if $message.type == $typeSuccess}added{else}removed{/if}">
				{$message.message}
			</div>
		{/foreach}
	{/if}
</div>
<hr class="space"/>