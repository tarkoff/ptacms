<!-- Search -->
<div id="search-top" class="box"></div>
<div id="search">
	<div id="search-in">
		<div id="s01">
			{assign var="formName" value=`$form->name`}
			{assign var="validationField" value=$form->data.$formName}
			{assign var="inputField" value=$form->data.searchRequest}
			{assign var="submitField" value=$form->data.submit}
			<form action="{$form->action}" method="get" name="{$form->name}" id="{$form->name}">
				<input type="hidden" id="{$validationField->name}" name="{$validationField->name}" value="{$validationField->value}" />
				<p class="nom t-center">
					<label for="search-catalog">Поиск:</label>
					<input type="text" size="75" id="{$inputField->name}" name="{$inputField->name}" class="nonhigh" value="{$inputField->value|default:'поиск по производителю или модели...'}" {literal}onblur="if (this.value == '') {this.value = 'поиск по производителю или модели...';}" onfocus="if (this.value == 'поиск по производителю или модели...') {this.value = '';}"{/literal} />
					<input type="image" id="{$submitField->name}" name="{$submitField->name}" value="{$submitField->value}" src="{$smarty.const.PTA_DESIGN_IMAGES_URL}/search-button.gif" class="search-submit" />
				</p>
			</form>
		</div>
	<hr class="noscreen" />
	</div> <!-- /search-in -->
</div> <!-- /search -->
<div id="search-bottom"></div>
