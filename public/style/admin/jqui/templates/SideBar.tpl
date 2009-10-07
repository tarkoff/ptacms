<div id="sidebar" class="first span-6 sidebar">
{literal}
	<a class="ui-state-default ui-corner-all btn_vertical" href="javascript:function a(){return false;}" onClick="showSideBar()" style="display:none;" id="showMenu">
		<span class="ui-icon ui-icon-circle-arrow-e"></span>S<br/>h<br/>o<br/>w<br/> <br/>M<br/>e<br/>n<br/>u<br/>
	</a>
{/literal}
	<div class="ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<div class="portlet-header ui-widget-header ui-corner-all">
			<span class="ui-icon ui-icon-circle-arrow-w"></span>Main Menu
		</div>
		<div class="ui-widget-content ui-corner-all">
			<div id="menuAccordion">
				<h3><a href="#">Catalog</a></h3>
				<div>
					<ul class="menuList">
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Catalog/">Catalog</a></li>
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Categories/">Categories</a></li>
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Fields/">Fields</a></li>
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/FieldsGroups/">Field Groups</a></li>
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Brands/">Brands</a></li>
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Currencies/">Currencies</a></li>
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Posts/">Posts</a></li>
					</ul>
				</div>
				<h3><a href="#">Users</a></h3>
				<div>
					<ul class="menuList">
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Users/">Users</a></li>
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/UserGroups/">User Groups</a></li>
					</ul>
				</div>
				<h3><a href="#">Settings</a></h3>
				<div>
					<ul class="menuList">
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Sites/">Sites</a></li>
						<li><a href="{$smarty.const.PTA_ADMIN_URL}/Themes/">Themes</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>