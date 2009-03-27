<?php /* Smarty version 2.6.21, created on 2009-03-28 01:14:21
         compiled from Index.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>eBookva</title>
<link href="<?php echo @CSSURL; ?>
/style.css" rel="stylesheet" type="text/css" />
<script src="<?php echo @JQUERYURL; ?>
/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="<?php echo @JQUERYURL; ?>
/corners/jquery.corners.js" type="text/javascript"></script>
<script src="<?php echo @JQUERYURL; ?>
/accordion/jquery.accordion.js" type="text/javascript"></script>
<?php echo '
<script type="text/javascript">
	jQuery().ready(function(){
		// second simple accordion with special markup
		jQuery(\'#navigation\').accordion({
			active: false,
			header: \'.head\',
			navigation: true,
			event: \'mouseover\',
			fillSpace: true,
			animated: \'easeslide\'
		});
		
		
	});
</script>
<style>

div.selected .title { font-weight: bold; }
div.selected {
	border-bottom: none;
}

#navigation {
	border:1px solid #5263AB;
	margin:0px;
	padding:0px;
	text-indent:0px;
	background-color:#E2E2E2;
	width:200px;
}
#navigation a.head {
	cursor:pointer;
	border:1px solid #CCCCCC;
	background:#5263AB url(collapsed.gif) no-repeat scroll 3px 4px;
	color:#FFFFFF;
	display:block;
	font-weight:bold;
	margin:0px;
	padding:0px;
	text-indent:14px;
	text-decoration: none;
}
#navigation a.head:hover {
	color:#FFFF99;
}
#navigation a.selected {
	background-image: url(expanded.gif);
}
#navigation a.current {
	background-color:#FFFF99;
}
#navigation ul {
	border-width:0px;
	margin:0px;
	padding:0px;
	text-indent:0px;
}
#navigation li {
	list-style:none outside none; display:inline;
}
#navigation li li a {
	color:#000000;
	display:block;
	text-indent:10px;
	text-decoration: none;
}
#navigation li li a:hover {
	background-color:#FFFF99;
	color:#FF0000;
}
</style>
'; ?>

</head>
<body>
<!--top start -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['Header']->tpl, 'smarty_include_vars' => array('data' => $this->_tpl_vars['Header'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--top end -->
<!--body start -->
	<div id="body">
	<!--left start -->
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['LeftMenu']->tpl, 'smarty_include_vars' => array('data' => $this->_tpl_vars['LeftMenu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!--left end -->
	<!--right start -->
		<div id="right">
		<!--rightTop start -->
					<!--rightTop end -->
		<!--rightLeft start -->
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['activeModule']->tpl, 'smarty_include_vars' => array('data' => $this->_tpl_vars['activeModule'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<!--rightLeft end -->
		<!--last start -->
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "RightNav.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<!--last end -->
		<br class="spacer" />
		</div>
	<!--right end -->
	<br class="spacer" />
	</div>
<!--body end -->
<!--footer start -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--footer end -->
</body>
</html>