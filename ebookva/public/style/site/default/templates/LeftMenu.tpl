<div id="left">
	<p class="catagory">Categories</p>
	<ul class="lftNav" id="navigation">
	{defun name="cattree" list=$data->Categories}
	{foreach from=$list item=category name=cat}
		{if $smarty.foreach.cat.last}
			<li class="noImg"><a href="{$data->url}{$category.alias}">{$category.title}</a></li>
		{else}
			<li><a href="{$data->url}{$category.alias}">{$category.title}</a></li>
		{/if}
		{if !empty($category.childs)}
			<ul>{fun name="cattree" list=$category.childs}</ul>
		{/if}
	{/foreach}
	{/defun}
	</ul>
</div>
