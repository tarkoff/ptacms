{assign var="formData" value=`$form->data`}
{assign var="formName" value=`$form->name`}

<div class="ui-widget ui-helper-clearfix ui-corner-all">
	<div class="portlet-header ui-widget-header ui-corner-all">
		<span class="ui-icon ui-icon-circle-arrow-s"></span>{$form->title}
	</div>
	<div class="form ui-widget-content ui-corner-all">
		<form name="{$form->name}" id="{$form->name}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" {if !empty($formCss)}class="{$formCss}"{/if}>
	<table cols="3" cellspacing="10px" class="editFormTable">
		<tr>
			<td><label for="color">{$formData.photo->label}{if $formData.photo->mandatory}*{/if}:</label></td>
			<td colspan="2">{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$formData.photo}</td>
		</tr>
		<tr>
			<th>Is Default</th>
			<th>Photo</th>
			<th>Actions</th>
		</tr>
		{foreach from=`$form->photos` item=photo}
		<tr>
			<td>
				<input type="radio" name="{$formData.default->name}" value="{$photo.PHOTOS_ID}" {if !empty($photo.PHOTOS_DEFAULT)}checked="checked"{/if}>
			</td>
			<td>
				{if !empty($photo.PHOTOS_PHOTO)}
					<img 
						src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}&h=120&w=120&zc=0" 
						alt="{$photo.PHOTOS_ID}"
						width="120" 
						height="120" 
					/>
				{/if}
			</td>
			<td>
				<a href="{$form->actionUrl}{$photo.PHOTOS_ID}/" class="btn_no_text ui-state-default ui-corner-all" title="Remove Photo">
					<span class="ui-icon ui-icon-trash"></span>
				</a>
			</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="2" align="center" style="text-align:center;">
				{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=`$formData.$formName`}
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="text-align:center;">
				{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$formData.submit}
			</td>
		</tr>
	</table>
		</form>
	</div>
</div>

<!--
<hr class="space" />
<fieldset>
	<legend>{$form->title|default:'Photo Edit Form'}</legend>
<form name="{$formName}" id="{$formName}" action="{$form->action}" method="{$form->method}" enctype="{$form->enctype}" class="editForm">
	<table cols="2" cellspacing="10px" class="editFormTable">
		<tr>
			<td><label for="color">{$formData.photo->label}{if $formData.photo->mandatory}*{/if}:</label></td>
			<td>{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$formData.photo}</td>
		</tr>
		<tr>
			<th>Is Default</th>
			<th>Photo</th>
		</tr>
		{foreach from=`$form->photos` item=photo}
		<tr>
			<td>
				{if empty($photo.PHOTOS_DEFAULT)}
					<input type="radio" name="{$formData.default->name}" value="{$photo.PHOTOS_ID}">
				{else}
					<input type="radio" name="{$formData.default->name}" value="{$photo.PHOTOS_ID}" checked="checked">
				{/if}
			</td>
			<td>
				{if !empty($photo.PHOTOS_PHOTO)}
					<img 
						src="{$smarty.const.PTA_THUMB_URL}?src={$smarty.const.PTA_CONTENT_URL}/{$photo.PHOTOS_PHOTO}&h=120&w=120&zc=0" 
						alt="{$photo.PHOTOS_ID}"
						width="120" 
						height="120" 
					/>
				{/if}
				&nbsp;
				<a href="{$form->actionUrl}{$photo.PHOTOS_ID}/">
					{html_image file="`$smarty.const.PTA_DESIGN_IMAGES_URL`/view/actions/remove.png" alt="Delete Photo"}
				</a>
			</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="2" align="center" style="text-align:center;">
				{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=`$formData.$formName`}
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="text-align:center;">
				{include file="`$smarty.const.PTA_GENERIC_TEMPLATES_PATH`/controls.tpl" field=$formData.submit}
			</td>
		</tr>
	</table>
</form>
</fieldset>
-->