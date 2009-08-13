{pta_const name="PTA_Control_View::MODE_SIMPLEGRID" to="htmlMode"}
{pta_const name="PTA_Control_View::MODE_JGRID" to="jsMode"}

{if $view->workMode == $htmlMode}
	{include file="_generic/view.tpl" view=$view}
{elseif $view->workMode == $jsMode}
	<link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/css/ui.jqgrid.css" />
	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/js/i18n/grid.locale-ru.js" type="text/javascript"></script>
	<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>

	<div id="dataGrid">
	</div>

	{literal}
	<script type="text/javascript">
		$(function() {
			$("#dataGrid").jqGrid({
				{/literal}
				page: {$view->page},
				{literal}
				postData: {1,2,3,4,5,6}
			});
		});
	</script>
	{/literal}
{/if}