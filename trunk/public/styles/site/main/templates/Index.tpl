{include file=$Header->tpl data=$Header}
<div id="site_content">
	{include file=$LeftMenu->tpl data=$LeftMenu}
        <div id="right_side">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
                <div class="contentb">
                        <b class="b1ft"></b><b class="b2ft"></b><b class="b3ft"></b><b class="b4ft"></b>
                            <div id="main_title" class="contentft"><h1>Round FILL!!</h1></div>
                        <b class="b4ft"></b><b class="b3ft"></b><b class="b2ft"></b><b class="b1ft"></b>
                        {include file=$activeModule->tpl data=$activeModule}
                </div>
            <b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
        </div>
    </div>
</div>
{include file='Footer.tpl'}