<?php /* Smarty version 2.6.21, created on 2009-03-30 21:25:58
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
						<div id="rightLeft">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['activeModule']->tpl, 'smarty_include_vars' => array('data' => $this->_tpl_vars['activeModule'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div>
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