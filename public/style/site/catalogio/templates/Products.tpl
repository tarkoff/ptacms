<div class="title01-top"></div>
	<div class="title01">
		<div class="title01-in">
			<h3 class="ico-info">Описание&nbsp;{$data->brand.BRANDS_TITLE}&nbsp;{$data->product.PRODUCTS_TITLE}</h3>
		</div>
	</div>
	<div class="title01-bottom"></div>

	{assign var=productObject value=$data->object}
	{assign var="categoriesObject" value=$Categories->object}

	{if !empty($data->categories)}
		<!-- Subcategories -->
		<h4>Разделы {$data->brand.BRANDS_TITLE}&nbsp;{$data->product.PRODUCTS_TITLE}</h4>
		<ul class="ul-categories box bb">
		{foreach from=$data->categories item=category}
			<li><a href="{$Categories->url}/{$category.CATEGORIES_ALIAS}">{$category.CATEGORIES_TITLE}</a> ({$category.PRODS_CNT|default:'0'})</li>
		{/foreach}
		</ul>
	{/if}
	<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/colorbox/jquery.colorbox-min.js"></script>
	<link type="text/css" media="screen" rel="stylesheet" href="{$smarty.const.PTA_JS_JQUERY_URL}/colorbox/css/colorbox.css" />
	<!--[if IE]>
		<link rel="stylesheet" href="{$smarty.const.PTA_JS_JQUERY_URL}/colorbox/css/colorbox-ie.css" type="text/css" media="screen, projection" />
	<![endif]-->

	{literal}
	<script type="text/javascript">
		$(document).ready(function(){
			//Examples of how to assign the ColorBox event to elements.
			$("a[rel='photos']").colorbox({
				transition:"elastic",
				current : "{current} из {total}"
			});
		});
	</script>
	{/literal}

		<div id="productDescr box">
			<table class="width100">
				<tr>
					<td class="imgBox">
					{foreach from=$data->photos item=photo}
						{if $photo.PHOTOS_DEFAULT}
							<a href="{$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}" rel="photos" title="{$data->brand.BRANDS_TITLE} {$data->product.PRODUCTS_TITLE}">
								<img src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}&h=180&w=180&zc=0" alt="{$data->product.PRODUCTS_TITLE}"/>
							</a>
						{else}
							<a href="{$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}" rel="photos" title="{$data->brand.BRANDS_TITLE} {$data->product.PRODUCTS_TITLE}"></a>
						{/if}
					{foreachelse}
						<img src="{$smarty.const.PTA_DESIGN_IMAGES_URL}/noImg180.gif" alt="{$data->product.PRODUCTS_TITLE}" />
					{/foreach}
					</td>
					<td>
						<table>
						{if !empty($data->brand.BRANDS_URL)}
							<tr class="bb">
								<td>Сайт производителя:</td>
								<td><a href="{$data->brand.BRANDS_URL}" rel="nofollow">{$data->brand.BRANDS_URL}</a></td>
							</tr>
						{/if}
						{if !empty($data->product.PRODUCTS_URL)}
							<tr class="bb">
								<td>Описание производителя:</td>
								<td><a href="{$data->product.PRODUCTS_URL}" rel="nofollow">{$data->product.PRODUCTS_URL}</a></td>
							</tr>
						{/if}
						{if !empty($data->product.PRODUCTS_DRIVERSURL)}
							<tr class="bb">
								<td><b>Скачать драйвера:</b></td>
								<td><a href="{$data->product.PRODUCTS_DRIVERSURL}" rel="nofollow">{$data->product.PRODUCTS_DRIVERSURL}</a></td>
							</tr>
						{/if}
						</table>
					</td>
				</tr>
			</table>
			<div id="descrBody" class="box">
				<div id="shortDescr">{$data->product.PRODUCTS_SHORTDESCR}</div>
			</div>
			{if !empty($data->customProductField)}
				<div id="prodCustomDescr" class="box">
					<table cols="2" class="width100">
						<th colspan="2">Основные характеристики</th>
						{foreach from=$data->customProductField item=field}
							<tr bgcolor="{cycle values="#DCEBFF,#FFF5C7"}">
								<td><em>{$field.PRODUCTSFIELDS_TITLE}</em>:</td>
								<td>{$field.PRODUCTSFIELDSVALUES_VALUE|default:'неизвестно'}</td>
							</tr>
						{/foreach}
					</table>
				</div>
			{/if}
		</div>