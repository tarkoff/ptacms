<?php /* Smarty version 2.6.19, created on 2009-03-21 15:57:27
         compiled from Header.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>eGOODS</title>
<link rel="stylesheet" type="text/css" href="<?php echo @CSSURL; ?>
/style.css" media="screen" />

<script src="<?php echo @JQUERYURL; ?>
/jquery-1.3.2.min.js" type="text/javascript" />
<script src="<?php echo @JQUERYURL; ?>
/corners/jquery.corners.js" type="text/javascript" />

</head>
<body>
<div id="Header" class="header">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['MainMenu']->tpl, 'smarty_include_vars' => array('data' => $this->_tpl_vars['MainMenu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>