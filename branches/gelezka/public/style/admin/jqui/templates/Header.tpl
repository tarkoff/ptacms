{assign var="user" value=$app->object->getUser()}
<div id="header">
	<div id="topMenu" class="topMenu top">
		<span class="append-1">Logged in as <span style="color:#fff">{if !empty($user)}{$user->getLogin()}{else}Guest{/if}</span></span>
		<a title="My account" href="#">My account</a>&nbsp;|&nbsp;
		<a title="Edit profile" href="#">Edit profile</a>&nbsp;|&nbsp;
		<a title="Logout" href="/Authorizer/Logout">Logout</a>
	</div>
	<div id="siteLogo" class="siteLogo">
		<a href="/" class="large strong">P. T. A. CMS Admin Panel</a>
	</div>
</div>