<?php /* Smarty version 2.6.21, created on 2009-03-24 21:29:52
         compiled from Header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_image', 'Header.tpl', 19, false),)), $this); ?>
<div id="top">
	<p class="ph">
		<span>+01-4456-6678</span>
	</p>
	<ul>
		<li><a href="#" class="hover">Home</a></li>
		<li><a href="#">About Us</a></li>
		<li><a href="#">Solutions</a></li>
		<li><a href="#">Support</a></li>
		<li><a href="#">Testimonials</a></li>
		<li><a href="#">News and Events</a></li>
		<li><a href="#">Location</a></li>
		<li><a href="#">blog</a></li>
		<li class="noImg"><a href="#">Contact&nbsp;Us</a></li>
	</ul>
</div>
<div class="logoBlock">
	<a href="<?php echo @BASEURL; ?>
">
		<?php echo smarty_function_html_image(array('file' => (@IMAGESURL)."/logo.gif",'alt' => @BASEURL), $this);?>

	</a>
</div>