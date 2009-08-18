<table id="fieldsList" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="fieldsListPager" class="scroll" style="text-align:center;"></div>

{literal}
	<script type="text/javascript">
		jQuery("#fieldsList").jqGrid({
			url:'{/literal}{$Fields->url}?{$app->prefix}_ajaxMode=1&{$app->prefix}_gridMode=2{literal}',
			datatype: "json", 
			colNames:['Actions',{/literal}
			{foreach from=$view->fields key=fieldAlias item=fieldName name=viewFields}
				'{$fieldAlias}'{if !$smarty.foreach.viewFields.last}, {/if}
			{/foreach}
			{literal}],
			colModel:[
				{name:'act',index:'act', width:75,sortable:false}, {/literal}
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
			caption:"Product Fields View",
			loadComplete: function(){
				var ids = jQuery("#fieldsList").getDataIDs();
				for(var i=0; i < ids.length; i++) {
					var cl = ids[i];
					be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=jQuery('#fieldsList').editRow("+cl+"); />";
					se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=jQuery('#fieldsList').saveRow("+cl+"); />";
					ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=jQuery('#fieldsList').restoreRow("+cl+"); />";
					jQuery("#fieldsList").setRowData(ids[i],{act:be+se+ce})
				}
			},
		}).navGrid('#fieldsListPager',{edit:true,add:true,del:true});
	</script>
{/literal}
