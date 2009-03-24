<div id="mainCategories">
	<div>
	{foreach from=$categories key=catId item=category name=cat}
		<h1><a href="{$activeModule->url}{$catId}">{$category.title}</a></h1>
		{foreach from=$category.childs key=childCatId item=childCategory name=childCats}
			<h2><a href="{$activeModule->url}{$childCatId}">{$childCategory}</a></h2>
		{/foreach}
	{/foreach}
	</div>
</div>