<table id="fieldsList" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="fieldsListPager" class="scroll" style="text-align:center;"></div>

{literal}
	<script type="text/javascript">
		jQuery("#fieldsList").jqGrid({ 
			url:'{/literal}{$Fields->url}?{$app->prefix}_ajaxMode=1&{$app->prefix}_asXml=1{literal}', 
			datatype: "xml", 
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
			rowNum:{/literal}{$view->rpp}{literal}, 
			autowidth: true, 
			rowList:[10,20,30,40,50,60,70,80,90,100,120,140,160,180,200], 
			pager: jQuery('#fieldsListPager'), 
			viewrecords: true, 
			sortorder: "asc", 
			height:440,
			caption:"Product Fields View" 
		}).navGrid('#fieldsListPager',{edit:true,add:true,del:true});
	</script>
{/literal}
