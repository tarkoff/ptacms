<div class="title01-top"></div>
<div class="title01">
	<div class="title01-in">
		<h3 class="ico-info">
			Каталог {$data->brand.BRANDS_TITLE}
		</h3>
	</div>
</div>
<div class="title01-bottom"></div>
<p class="bb nomt">
	<a href="/">Главная</a> &raquo;
	<strong>{$data->brand.BRANDS_TITLE}</strong>
</p>

{include file="Catalog/Pager.tpl" view = $data->view data = $data pagerUrl="`$data->brandUrl`/`$data->brand.BRANDS_ALIAS`"}
