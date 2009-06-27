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
		<a href="{$activeModule->url}view/page/1">{html_image file="`$smarty.const.PTA_DESIGN_IMAGES_URL`/view/pager/first.png" alt="First Page"}</a>
	</div>
	<div>
		<a href="{$activeModule->url}view/page/{$view->prevPage}">{html_image file="`$smarty.const.PTA_DESIGN_IMAGES_URL`/view/pager/back.png" alt="Previous Page"}</a>
	</div>
	<div>Page {$view->page} From {$view->lastPage}</div>
	<div>
		<a href="{$activeModule->url}view/page/{$view->nextPage}">{html_image file="`$smarty.const.PTA_DESIGN_IMAGES_URL`/view/pager/forward.png" alt="Next Page"}</a>
	</div>
	<div>
		<a href="{$activeModule->url}view/page/{$view->lastPage}">{html_image file="`$smarty.const.PTA_DESIGN_IMAGES_URL`/view/pager/last.png" alt="Last Page"}</a>
	</div>
</div>