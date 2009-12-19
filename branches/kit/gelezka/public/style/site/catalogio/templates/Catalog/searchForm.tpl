<div id="s01">
	{assign var="form" value=`$Catalog->searchForm`}
	{assign var="formName" value=`$form->name`}
	{assign var="validationField" value=$form->data.$formName}
	{assign var="inputField" value=$form->data.searchRequest}
	{assign var="submitField" value=$form->data.submit}
	<form action="{$form->action}" method="get" name="{$form->name}" id="{$form->name}">
		<input type="hidden" id="{$validationField->name}" name="{$validationField->name}" value="{$validationField->value}" />
		<p class="nom t-center">
			<label for="{$inputField->name}">По Каталогу:</label>
			<input type="text" size="75" id="{$inputField->name}" name="{$inputField->name}" class="nonhigh search-input" value="{$inputField->value|default:'поиск по производителю или модели...'}" {literal}onblur="if (this.value == '') {this.value = 'поиск по производителю или модели...';}" onfocus="if (this.value == 'поиск по производителю или модели...') {this.value = '';}"{/literal} />
			<input type="submit" id="{$submitField->name}" name="{$submitField->name}" value="&#x041f;&#x043e;&#x0438;&#x0441;&#x043a;" class="search-submit" />
		</p>
	</form>
</div>
