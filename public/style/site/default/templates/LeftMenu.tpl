<div id="left">
	<p class="catagory">Тематика</p>
	<ul class="lftNav">
	{defun name="cattree" list=$data->Categories}
	{foreach from=$list key=alias item=title name=cat}
		{if $smarty.foreach.cat.last}
			<li class="noImg"><a href="{$data->url}{$alias}">{$title}</a></li>
		{else}
			<li><a href="{$data->url}{$alias}">{$title}</a></li>
		{/if}
		{if !empty($category.childs)}
			<ul>{fun name="cattree" list=$childs}</ul>
		{/if}
	{/foreach}
	{/defun}
	</ul>
</div>