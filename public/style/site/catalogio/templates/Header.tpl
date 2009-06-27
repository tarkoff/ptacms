    <!-- Header -->
    <div id="header">

        <h1 id="logo"><a href="/" title="На главную"><img src="{$smarty.const.PTA_DESIGN_IMAGES_URL}/logo.gif" alt="Logo" /></a></h1>

        <hr class="noscreen" />

        <!-- Date -->
        <div class="date date-24">
            <p class="nom">
            	Сегодня <strong id="todayDate"></strong><br />
            <span class="nonhigh"><a href="">Сделать стартовой</a></span></p>
        </div> <!-- /date -->
		<script type="text/javascript">$("#todayDate").html($.PHPDate('F j, Y', new Date()));</script>

    <hr class="noscreen" />
    </div> <!-- /header -->
