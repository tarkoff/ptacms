<!-- Tabs -->
<div class="tabs-sidebar box">
	<ul id="switch">
		<li><a href="#tab-01"><span>Most Recent</span></a></li>
		<li><a href="#tab-02"><span>Most Viewed</span></a></li>
	</ul>
</div> <!-- /tabs-sidebar -->

<!-- Most Recent -->
<div id="tab-01">
	<p>
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>
	</p>
</div>

<!-- Most Viewed -->
<div id="tab-02">
	<p>
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>,
		<a href="" class="l-02">Ipsum</a>,
		<a href="">Dolor</a>,
		<a href="" class="h-01">Sit</a>,
		<a href="" class="h-02">Amet</a>,
		<a href="" class="h-02">Lorem</a>
	</p>
</div>

<script src="{$smarty.const.PTA_JS_JQUERY_URL}/tools/tabs/tools.tabs-1.0.1.min.js" type="text/javascript"></script>
<link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.PTA_JS_JQUERY_URL}/tools/tabs/tabs-no-images.css" />
{literal}
	<script type="text/javascript">
		$(function() { 
			// setup ul.tabs to work as tabs for each div directly under div.panes 
			$("ul.descr-tabs").tabs("div.descr-panes > div", { 
				event: 'click',
				effect: 'fade',
				initialIndex: {/literal}{if $data->commentForm->submited}1{else}0{/if}{literal}
			});
		});
	</script>
{/literal}

<hr class="noscreen" />