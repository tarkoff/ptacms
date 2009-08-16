{pta_const name="PTA_Control_View::MODE_SIMPLEGRID" to="htmlMode"}
{pta_const name="PTA_Control_View::MODE_JGRID" to="jsMode"}

{if $view->workMode == $htmlMode}
	{include file="_generic/view.tpl" view=$view}
{elseif $view->workMode == $jsMode}
	<link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.PTA_JS_JQUERY_URL}/ui/css/ui-lightness/jquery-ui-1.7.2.custom.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/themes/basic/grid.css" />
	<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/ui/js/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery.layout.min.js"></script>
	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/js/i18n/grid.locale-ru.js" type="text/javascript"></script>
	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>

	<table id="fieldsList" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="fieldsListPager" class="scroll" style="text-align:center;"></div>

	{literal}
	<script type="text/javascript">
$(document).ready(function () {

		$("#fieldsList").jqGrid({
			datatype: "local",
			colNames:[{/literal}
			{foreach from=$view->fields key=fieldAlias item=fieldName name=viewFields}
				'{$fieldAlias}'{if !$smarty.foreach.viewFields.last}, {/if}
			{/foreach}
			{literal}],
			colModel:[{/literal}
			{foreach from=$view->fields key=fieldAlias item=fieldName name=viewFields}
				{ldelim}name:'{$fieldName}', index:'{$fieldName}', width:100{rdelim}{if !$smarty.foreach.viewFields.last}, {/if}
			{/foreach}
			{literal}],
			imgpath: "gridimgpath", 
			viewrecords: true,
			rowNum: {/literal}{$view->fieldsCnt}{literal},
			multiselect: true,
			autowidth: true,
			rowList:[10,20,30],
			pager: jQuery('#fieldsListPager'),
			caption: "Manipulating Array Data" 
		}).navGrid('#fieldsListPager',{edit:false,add:false,del:false}); 

		var mydata = [{/literal}
			{foreach from=$view->data item=record name=fieldRecords}
				{ldelim}
				{foreach from=$record key=fieldName item=fieldValue name=fieldValues}
					{$fieldName}:"{$fieldValue}"{if !$smarty.foreach.fieldValues.last},{/if}
				{/foreach}
				{rdelim}{if !$smarty.foreach.fieldRecords.last},{/if}
			{/foreach}{literal}
		]; 

		var listTable = $("#fieldsList");
		for(var i=0;i<=mydata.length;i++) listTable.addRowData(i+1,mydata[i]); 
});
	</script>
	{/literal}
{/if}