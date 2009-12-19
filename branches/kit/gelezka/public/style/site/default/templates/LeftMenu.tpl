<div id="leftMenu" class="span-5">
	<h6 class="menuTitle">Раздеды</h6>
	<ul class="menuList">
	{defun name="cattree" list=$data->Themes.childs}
	{foreach from=$list item=theme name=cat}
			<li {if $smarty.foreach.cat.last}class="noImg"{/if}>
				<a href="{$data->url}{$theme.CATEGORIES_ALIAS}">{$theme.CATEGORIES_TITLE}</a>
				{if !empty($theme.childs)}
					<ul>{fun name="cattree" list=$theme.childs}</ul>
				{/if}
			</li>
	{/foreach}
	{/defun}
	</ul>
</div>