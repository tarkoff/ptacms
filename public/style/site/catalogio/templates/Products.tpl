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
	<h4>Разделы, в которые входит {$data->brand.BRANDS_TITLE}&nbsp;{$data->product.PRODUCTS_TITLE}</h4>
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

{assign var="priceForm" value=$data->PriceForm}
{assign var="fields" value=$priceForm->data}
{assign var="currencyField" value=$fields.currency}
{assign var="priceField" value=$fields.price}
{assign var="descrField" value=$fields.descr}
{assign var="dateToField" value=$fields.dateTo}
{assign var="submitField" value=$fields.submit}

{literal}
	<script type="text/javascript">
		$(document).ready(function(){
			$("a[rel='photos']").colorbox({
				transition:"elastic",
				current : "{current} из {total}"
			});

			$("#newPriceFormScroller").click(function () {
				if ($("#newPriceForm").is(":hidden")) {
					$("#newPriceForm").slideDown("slow");
				} else {
					$("#newPriceForm").slideUp("slow");
				}
			});

			$("#{/literal}{$dateToField->name}{literal}").datepicker(
				$.datepicker.regional['ru']
			);
			$("#{/literal}{$dateToField->name}{literal}").datepicker(
				'option', {dateFormat:'yy-mm-dd'}
			);
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
				<img src="{$smarty.const.PTA_DESIGN_IMAGES_URL}/noImg180.jpg" alt="{$data->product.PRODUCTS_TITLE}" width="180" height="135"/>
			{/foreach}
			</td>
			<td>
				<table>
					<tr class="bb">
						<td colspan="2">
							<a id="newPriceFormScroller" class="bigger strong add" style="cursor:pointer;">Добавить обьявление о продаже</a>
							{if $priceForm->submited}
								{pta_const name="PTA_Control_Form::FORM_ERROR_SAVE" to="saveError"}
								{pta_const name="PTA_Control_Form::FORM_ERROR_VALIDATE" to="validateError"}
								{if $priceForm->error == $validateError}
									<p class="error">Не все поля заполнены!</p>
								{elseif $priceForm->error == $saveError}
									<p class="error">Ответ на контрольный вопрос неправильный!</p>
								{else}
									<p class="success">Ваше обьявление успешно добавлено!</p>
								{/if}
							{/if}
							<div id="newPriceForm" style="display:none;">
								{assign var="formName" value=$priceForm->name}
								<form enctype="{$priceForm->enctype}" method="{$priceForm->method}" action="{$priceForm->action}" id="{$formName}" name="{$formName}">
									<fieldset>
										<legend>Добавление новой цены</legend>
										<dl>
											<dd style="text-align: center;">{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$fields.$formName}</dd>
										</dl>
										<dl>
											<dt><label for="{$priceField->name}">Валюта*:</label></dt>
											<dd>{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$currencyField cssClass="prpiceInput"}</dd>
										</dl>
										<dl>
											<dt><label for="{$priceField->name}">Цена*:</label></dt>
											<dd><input type="text" value="" maxlength="10" id="{$priceField->name}" name="{$priceField->name}" class="prpiceInput"/></dd>
										</dl>
										<dl>
											<dt><label for="{$descrField->name}">Описание и контакты*:</label></dt>
											<dd><textarea id="{$descrField->name}" name="{$descrField->name}" class="prpiceInput priceDescr"></textarea></dd>
										</dl>
										<dl>
											<dt><label for="{$dateToField->name}">Актуально до:</label></dt>
											<dd>
												<input type="text" value="" readonly="readonly" id="{$dateToField->name}" name="{$dateToField->name}" class="prpiceInput" />
												<div id="datepicker"></div>
											</dd>
										</dl>
										<dl>
											<dt style="text-align: center;"><input type="submit" value="Добавить" id="{$submitField->name}" name="{$submitField->name}" class="prpiceInput" /></dt>
										</dl>
									</fieldset>
								</form>
							</div>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
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

	<div class="descr-panes" id="tabs"> 
		<ul class="descr-tabs"> 
			{if !empty($data->mixPrices)}<li><a href="#prices">Цены</a></li>{/if}
			<li><a href="#prodCustomDescr">Технические характеристики</a></li>
			<li><a href="#prodComments">Комментарии</a></li>
			<li><a href="#secondHandPrices">Продам Б/У</a></li>
		</ul> 
		{if !empty($data->mixPrices)}
			<div id="prices">
				{assign var="noImg" value="`$smarty.const.PTA_DESIGN_IMAGES_URL`/noImg90.jpg"}
				{foreach from=$data->mixPrices item=offer}
					<div class="bb" style="margin:5px 0;font-size:0.9em;">
						<table>
							<tr>
								<td rowspan="5" width="100px">
									<a href="{$offer.OFFERS_URL}" target="_blank"><img src="{$offer.OFFERS_IMG|default:$noImg}" width="{if empty($offer.OFFERS_IMGW)}90{else}{$offer.OFFERS_IMGW}{/if}" height="{if empty($offer.OFFERS_IMGH)}67{else}{$offer.OFFERS_IMGH}{/if}" /></a>
								</td>
								<th style="text-align:left;">
									<a href="{$offer.OFFERS_URL}" target="_blank" class="bigger strong high">{$offer.OFFERS_NAME}</a>
								</th>
							</tr>
							<tr>
								<td>
									<em>Цена</em>: <a href="{$offer.OFFERS_URL}" target="_blank" class="bigger strong">{$offer.OFFERS_PRICE} {if $offer.OFFERS_CURRENCYID == 'RUB'}руб.{else}${/if}</a>
								</td>
							</tr>
							<tr>
								<td>
									<em>Магазин</em>: <a href="{$offer.OFFERS_URL}" target="_blank">{$offer.ADVERTIZERS_TITLE}</a>
								</td>
							</tr>
							<tr>
								<td>
									<em>Описание</em>: {if empty($offer.OFFERS_DESC)}{$offer.OFFERS_NAME}{else}{$offer.OFFERS_DESC|truncate:400}{/if}
								</td>
							</tr>
							<tr>
								<td><a href="{$offer.OFFERS_URL}" target="_blank">Купить в магазине <b>{$offer.ADVERTIZERS_TITLE}</b> >></a></td>
							</tr>
						</table>
					</div>
				{/foreach}
			</div>
		{/if}
		<div id="prodCustomDescr" class="box">
		{if !empty($data->customFields)}
			<table cols="2" class="width100">
			{foreach from=$data->customFields item=group}
				{if !empty($group.fields)}
					<tr class="bb">
						<th colspan="2" style="text-align:left;">{$group.FIELDSGROUPS_TITLE|default:'Общие характеристики'}</th>
					</tr>
					<tr>
						<td>
							<table class="width100">
							{foreach from=$group.fields item=field}
								{if !empty($field.PRODUCTSFIELDSVALUES_VALUE) && $field.PRODUCTSFIELDSVALUES_VALUE != 'empty'}
								<tr>
									<td width="50%"><em>{$field.PRODUCTSFIELDS_TITLE}</em></td>
									<td>
									{if is_array($field.PRODUCTSFIELDSVALUES_VALUE)}
										{foreach from=$field.PRODUCTSFIELDSVALUES_VALUE item=fieldValue name=fv}
											{if $smarty.foreach.fv.first}
												{$fieldValue}
											{else}
												; {$fieldValue}
											{/if}
										{/foreach}
									{else}
										{$field.PRODUCTSFIELDSVALUES_VALUE}
									{/if}
									</td>
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
				{pta_const name="PTA_Control_Form::FORM_ERROR_VALIDATE" to="fieldsError"}
				{pta_const name="Products_CommentsForm::COMMENT_ERROR_CAPTCHA" to="captchaError"}
				{if $data->commentForm->error == $fieldsError}
					<p class="error">Не все поля заполнены!</p>
				{elseif $data->commentForm->error == $captchaError}
					<p class="error">Ответ на контрольный вопрос неправильный!</p>
				{else}
					<p class="success">Ваш комментарий успешно добавлен!</p>
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
				Комментариев пока нет
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
							<td align="right">Комментарий</td>
							<td><textarea name="{$formFields.post->name}" class="formInput commentText"></textarea></td>
						</tr>
						<tr>
							<td class="" colspan="2">
								<p class="ico-info "><em>Для добавления комментария ответьте на вопрос</em></p>
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
		<div id="secondHandPrices">
		{if !empty($data->secondHandPrice)}
			<table class="width100">
			<tr>
				<th>Описание</th>
				<th width="100px">Цена</th>
				<th width="100px">Актуально до</th>
			</tr>
			{foreach from=$data->secondHandPrices item=price}
				<tr bgcolor="{cycle values="#eeeeee,#ffffff"}">
					<td>{$price.PRICES_DESCR}</td>
					<td align="center">{$price.PRICES_PRICE} {$price.CURRENCY}</td>
					<td align="center">{$price.PRICES_DATETO|date_format:"%d.%m.%Y"}</td>
				</tr>
			{/foreach}
			</table>
			{else}
				Обьявлений пока нет.
			{/if}
		</div>
	</div>
</div>