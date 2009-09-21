<div class="title01-top"></div>
<div class="title01">
	<div class="title01-in">
		<h3 class="ico-info">Описание&nbsp;{$data->brand.BRANDS_TITLE}&nbsp;{$data->product.PRODUCTS_TITLE}</h3>
	</div>
</div>
<div class="title01-bottom"></div>

{assign var=productObject value=$data->object}
{assign var="categoriesObject" value=$Categories->object}

<p class="bb nomt">
	<a href="/">Главная</a> &raquo;

	{assign var=categories value=$categoriesObject->getParentCategories($data->category.CATEGORIES_ID)}
	{defun name="cattree" categories=$categories}
		{foreach from=$categories item=category}
			{if !empty($category.childs)}
				<a href="{$Categories->url}/{$category.CATEGORIES_ALIAS}">{$category.CATEGORIES_TITLE}</a> &raquo;
				{fun name="cattree" categories=$category.childs}
			{else}
				<strong>{$category.CATEGORIES_TITLE}</strong>
			{/if}
		{/foreach}
	{/defun}
</p>

{if !empty($data->categories)}
	<!-- Subcategories -->
	<h4>Разделы в которые входит {$data->brand.BRANDS_TITLE}&nbsp;{$data->product.PRODUCTS_TITLE}</h4>
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
						<img src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}&w=180&zc=0" alt="{$data->product.PRODUCTS_TITLE}"/>
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
						<td colspan="2">
							<noindex><a href="{$data->brand.BRANDS_URL}" rel="nofollow" target="_blank" class="bigger strong nonhigh">Сайт производителя</a></noindex>
						</td>
					</tr>
				{/if}
				{if !empty($data->product.PRODUCTS_URL)}
					<tr class="bb">
						<td colspan="2">
							<noindex><a href="{$data->product.PRODUCTS_URL}" rel="nofollow" target="_blank" class="bigger strong nonhigh">Описание производителя</a></noindex>
						</td>
					</tr>
				{/if}
				{if !empty($data->product.PRODUCTS_DRIVERSURL)}
					<tr class="bb">
						<td colspan="2">
							<noindex><a href="{$data->product.PRODUCTS_DRIVERSURL}" rel="nofollow" target="_blank" class="bigger strong high">Скачать драйвера</a></noindex>
						</td>
					</tr>
				{/if}
				</table>
			</td>
			<td>
				<script type="text/javascript">
					<!--
						google_ad_client = "pub-1610147099732550";
						/* 336x280, gelezka_products_square */
						google_ad_slot = "9277947482";
						google_ad_width = 336;
						google_ad_height = 280;
					//-->
				</script>
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
			</td>
		</tr>
	</table>
	<div id="descrBody" class="box">
		<div id="shortDescr">{$data->product.PRODUCTS_SHORTDESCR}</div>
	</div>
	<br />
	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/tools/tabs/tools.tabs-1.0.1.min.js" type="text/javascript"></script>
	<link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.PTA_JS_JQUERY_URL}/tools/tabs/tabs-no-images.css" />
	{literal}
		<script type="text/javascript">
			// perform JavaScript after the document is scriptable. 
			$(function() { 
				// setup ul.tabs to work as tabs for each div directly under div.panes 
				$("ul.descr-tabs").tabs("div.descr-panes > div", { 
					event: 'click',
					effect: 'fade',
					initialIndex: {/literal}{if $data->commentForm->submited}1{else}0{/if}{literal}
				});
			});
		</script>
	{/literal}

	<ul class="descr-tabs"> 
		<li><a href="#">Технические характеристики</a></li> 
		<li><a href="#">Коментарии</a></li> 
	</ul> 

	<div class="descr-panes"> 
		<div id="prodCustomDescr" class="box">
		{if !empty($data->customFields)}
			<table cols="2" class="width100">
			{foreach from=$data->customFields item=group}
				{if !empty($group.fields)}
					<tr class="bb">
						<th colspan="2" style="text-align:left;">{$group.FIELDSGROUPS_TITLE|default:'Разное'}</th>
					</tr>
					<tr>
						<td>
							<table class="width100">
							{foreach from=$group.fields item=field}
								{if !empty($field.PRODUCTSFIELDSVALUES_VALUE) && $field.PRODUCTSFIELDSVALUES_VALUE != 'empty'}
								<tr>
									<td width="50%"><em>{$field.PRODUCTSFIELDS_TITLE}</em></td>
									<td>{$field.PRODUCTSFIELDSVALUES_VALUE}</dd>
								</tr>
								{/if}
							{/foreach}
							</table>
						</td>
					</tr>
				{/if}
			{/foreach}
			</table>
		{/if}
		</div>
		<div id="prodComments">
			{if $data->commentForm->submited}
				{pta_const name="Products_CommentsForm::COMMENT_ERROR_FIELDS" to="fieldsError"}
				{pta_const name="Products_CommentsForm::COMMENT_ERROR_CAPTCHA" to="captchaError"}
				{if $data->commentForm->error == $fieldsError}
					<p class="error">Не все поля заполнены!</p>
				{elseif $data->commentForm->error == $captchaError}
					<p class="error">Ответ на контрольный вопрос неправильный!</p>
				{else}
					<p class="success">Ваш коментарий учпешно добавлен!</p>
				{/if}
			{/if}

			<table class="width100">
			{foreach from=$data->comments item=comment}
				<tr class="bb">
					<td width="200px">
						<p class="strong">{$comment.POSTS_AUTHOR}</p>
						<p class="smaller">{$comment.POSTS_DATE}</p>
					</td>
					<td>{$comment.POSTS_POST}</td>
				</tr>
			{foreachelse}
				Коментариев пока нет
			{/foreach}
			</table>

			<p id="commentForm">
				{assign var="commentForm" value=$data->commentForm}
				{assign var="formFields" value=$commentForm->data}
				{assign var="formName" value=$commentForm->name}
				<form name="{$commentForm->name}" action="{$commentForm->action}" method="post">
					<input type="hidden" name="{$formFields.$formName->name}" value="{$formFields.$formName->value}" />
					<table width="400px;">
						<tr>
							<td align="right">Имя</td>
							<td><input type="text" name="{$formFields.author->name}" value="" class="formInput" /></td>
						</tr>
						<tr>
							<td align="right">Коментарий</td>
							<td><textarea name="{$formFields.post->name}" class="formInput commentText"></textarea></td>
						</tr>
						<tr>
							<td class="" colspan="2">
								<p class="ico-info "><em>Для добваления коментария ответьте на вопрос</em></p>
								<p class="bigger strong">Два сапога <input type="text" name="{$formFields.captcha->name}" value="" class="formInput" /></p>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="width:100%;" align="center"><input type="submit" name="{$formFields.submit->name}" value="Отправить" /></td>
						</tr>
					</table>
				</form>
			</p>

		</div>
	</div>
</div>