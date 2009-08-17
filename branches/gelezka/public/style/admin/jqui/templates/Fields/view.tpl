{pta_const name="PTA_Control_View::MODE_SIMPLEGRID" to="htmlMode"}
{pta_const name="PTA_Control_View::MODE_JGRID" to="jsMode"}

{if $view->workMode == $htmlMode}
	{include file="_generic/view.tpl" view=$view}
{elseif $view->workMode == $jsMode}
	<link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/themes/basic/grid.css" />

	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/js/i18n/grid.locale-ru.js" type="text/javascript"></script>
	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>

	<table id="fieldsList" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="fieldsListPager" class="scroll" style="text-align:center;"></div>

	{literal}
	<script type="text/javascript">
$(document).ready(function () {

	jQuery("#fieldsList").jqGrid({ 
		datatype: "local", 
		height: 250, 
		colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'], 
		colModel:[ 
			{name:'id',index:'id', width:60, sorttype:"int"}, 
			{name:'invdate',index:'invdate', width:90, sorttype:"date"}, 
			{name:'name',index:'name', width:100}, 
			{name:'amount',index:'amount', width:80, align:"right",sorttype:"float"}, 
			{name:'tax',index:'tax', width:80, align:"right",sorttype:"float"}, 
			{name:'total',index:'total', width:80,align:"right",sorttype:"float"}, 
			{name:'note',index:'note', width:150, sortable:false} 
		], 
		imgpath: "gridimgpath", 
		multiselect: true, 
		caption: "Manipulating Array Data" 
	}); 
	var mydata = [ {id:"1",invdate:"2007-10-01",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"}, {id:"2",invdate:"2007-10-02",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"}, {id:"3",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"}, {id:"4",invdate:"2007-10-04",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"}, {id:"5",invdate:"2007-10-05",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"}, {id:"6",invdate:"2007-09-06",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"}, {id:"7",invdate:"2007-10-04",name:"test",note:"note",amount:"200.00",tax:"10.00",total:"210.00"}, {id:"8",invdate:"2007-10-03",name:"test2",note:"note2",amount:"300.00",tax:"20.00",total:"320.00"}, {id:"9",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"} ]; for(var i=0;i<=mydata.length;i++) jQuery("#fieldsList").addRowData(i+1,mydata[i]); 
});
	</script>
	{/literal}
{/if}