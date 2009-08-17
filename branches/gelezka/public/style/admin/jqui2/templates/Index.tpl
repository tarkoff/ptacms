<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>jqGrid Demos</title>

<link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.PTA_DESIGN_CSS_URL}/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/css/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{$smarty.const.PTA_DESIGN_CSS_URL}/style.css" />

<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{$smarty.const.PTA_JS_JQUERY_URL}/ui/js/jquery-ui-1.7.2.custom.min.js"></script>
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery.layout.min.js" type="text/javascript"></script>
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/js/i18n/grid.locale-ru.js" type="text/javascript"></script>
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery.tablednd.js" type="text/javascript"></script>
<script src="{$smarty.const.PTA_JS_JQUERY_URL}/jquery.contextmenu.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
var gridimgpath = 'themes/basic/images';
jQuery(document).ready(function(){
    //$('#switcher').themeswitcher();

	$('body').layout({
		resizerClass: 'ui-state-default',
        west__onresize: function (pane, $Pane) {
            jQuery("#west-grid").setGridWidth($Pane.innerWidth()-2);
		}
	});
	$.jgrid.defaults = $.extend($.jgrid.defaults,{loadui:"enable"});
	var maintab =jQuery('#tabs','#RightPane').tabs({
        add: function(e, ui) {
            // append close thingy
            $(ui.tab).parents('li:first')
                .append('<span class="ui-tabs-close ui-icon ui-icon-close" title="Close Tab"></span>')
                .find('span.ui-tabs-close')
                .click(function() {
                    maintab.tabs('remove', $('li', maintab).index($(this).parents('li:first')[0]));
                });
            // select just added tab
            maintab.tabs('select', '#' + ui.panel.id);
        }
    });
    jQuery("#west-grid").jqGrid({
        url: "{/literal}{$smarty.const.PTA_DESIGN_URL}{literal}/templates/tree.xml",
        datatype: "xml",
        height: "auto",
        pager: false,
        loadui: "disable",
        colNames: ["id","Items","url"],
        colModel: [
            {name: "id",width:1,hidden:true, key:true},
            {name: "menu", width:150, resizable: false, sortable:false},
            {name: "url",width:1,hidden:true}
        ],
        treeGrid: true,
		caption: "jqGrid Demos",
        ExpandColumn: "menu",
        autowidth: true,
        //width: 180,
        rowNum: 200,
        ExpandColClick: true,
        treeIcons: {leaf:'ui-icon-document-b'},
        onSelectRow: function(rowid) {
            var treedata = $("#west-grid").getRowData(rowid);
            if(treedata.isLeaf=="true") {
                //treedata.url
                var st = "#t"+treedata.id;
				if($(st).html() != null ) {
					maintab.tabs('select',st);
				} else {
					maintab.tabs('add',st, treedata.menu);
					$(st,"#tabs").load(treedata.url);
				}
            }
        }
    });
	
// end splitter

});
</script>
{/literal}
</head>
<body>
<script type="text/javascript"
  //src="http://ui.jquery.com/themeroller/themeswitchertool/">
</script>
  	<div id="LeftPane" class="ui-layout-west ui-widget ui-widget-content">
		<table id="west-grid"></table>
	</div> <!-- #LeftPane -->
	<div id="RightPane" class="ui-layout-center ui-helper-reset ui-widget-content" ><!-- Tabs pane -->
    <div id="switcher"></div>
		<div id="tabs" class="jqgtabs">
			<ul>
				<li><a href="#tabs-1">jqGrid 35</a></li>
			</ul>
			<div id="tabs-1" style="font-size:12px;"> After lot of work, I'm happy to introduce a new jqGrid realease. <br/>
			The work is just in alfa stage, but I hope that the final will be released soon.<br/>
			There are a lot of issueas that I should resolve. The most of them are (as usual) in IE browsers. <br/>
			The theme integration should be polished too.There are some missing things like zebra striping, <br/>
			but I do not find a class in UI theme roller that corresponds to this.<br/>
			Instead of this, I think that this is a good start.
			<br/>
			Enjoy
			</div>
		</div>
	</div> <!-- #RightPane -->
</body>
</html>