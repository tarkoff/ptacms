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
<div id="product">
	<h1 class="contentHeader">{$data->category.CATEGORIES_TITLE} - {$data->brand.BRANDS_TITLE} {$data->product.PRODUCTS_TITLE}</h1>
	<div id="descr" class="descr">
		<div id="descrTop" class="append-bottom">
			<div id="productImg" class="productImgDiv">
				{foreach from=$data->photos item=photo}
					{if $photo.PHOTOS_DEFAULT}
						<a href="{$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}" rel="photos" title="{$data->brand.BRANDS_TITLE} {$data->product.PRODUCTS_TITLE}">
							<img src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}&h=180&w=180&zc=0" alt="{$data->product.PRODUCTS_TITLE}"/>
						</a>
					{else}
						<a href="{$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}" rel="photos" title="{$data->brand.BRANDS_TITLE} {$data->product.PRODUCTS_TITLE}">
						</a>
					{/if}
				{foreachelse}
					<img src="{$smarty.const.PTA_IMAGES_URL}/noImg180.gif" alt="{$data->product.PRODUCTS_TITLE}" />
				{/foreach}
			</div>
		</div>
		<div id="shortDescr" class="append-bottom">
			{$data->product.PRODUCTS_SHORTDESCR}
		</div>
		{if !empty($data->customProductField)}
		<div id="fullDescr" class="fullDescr">
			<table cols="2">
				<th colspan="2">Основные характеристики</th>
			{foreach from=$data->customProductField item=field}
				<tr bgcolor="{cycle values="#E2DEA4,#D5D08E"}">
					<td class="title">{$field.PRODUCTSFIELDS_TITLE}:</td>
					<td>{$field.PRODUCTSFIELDSVALUES_VALUE|default:'неизвестно'}</td>
				</tr>
			{/foreach}
			</table>
		</div>
		{/if}
	</div>
</div>