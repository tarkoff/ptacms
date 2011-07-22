{pta_const to="typeSuccess" name=PTA_Object::MESSAGE_SUCCESS}
{pta_const to="typeError" name=PTA_Object::MESSAGE_ERROR}
{pta_const to="typeNotice" name=PTA_Object::MESSAGE_NOTICE}
<br />
<div id="messages" class="container">
	{if !empty($messages)}
		{foreach from=$messages item=message}
			<div class="{if $message.type == $typeSuccess}success{elseif $message.type == $typeNotice}notice{else}error{/if}">
				{$message.message}
			</div>
		{/foreach}
	{/if}
</div>
<br />