<div class="nav">
	<div>
		<select name="rpp" id="rpp" onchange="setRpp(this.value)">
		{foreach from=$view->rpps key=key item=rpp}
			{if $view->rpp == $rpp}
				<option value="{$key}" selected="selected">{$rpp}</option>
			{else}
				<option value="{$key}">{$rpp}</option>
			{/if}
		{/foreach}
		</select>
	</div>
	<div><em class="btnseparator"></em></div>
	<div>
		<a href="{$activeModule->url}page/1">{html_image file="`$smarty.const.PTA_IMAGES_URL`/view/pager/first.png" alt="First Page"}</a>
	</div>
	<div>
		<a href="{$activeModule->url}page/1">{html_image file="`$smarty.const.PTA_IMAGES_URL`/view/pager/back.png" alt="First Page"}</a>
	</div>
	<div>
		<a href="{$activeModule->url}page/1">{html_image file="`$smarty.const.PTA_IMAGES_URL`/view/pager/forward.png" alt="First Page"}</a>
	</div>
	<div>
		<a href="{$activeModule->url}page/1">{html_image file="`$smarty.const.PTA_IMAGES_URL`/view/pager/last.png" alt="First Page"}</a>
	</div>
</div>