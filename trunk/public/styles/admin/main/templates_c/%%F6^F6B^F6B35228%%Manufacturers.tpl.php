<?php /* Smarty version 2.6.20, created on 2008-12-07 17:02:29
         compiled from Manufacturers.tpl */ ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'list'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Manufacturers/view.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['data']->view)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'edit'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Manufacturers/editForm.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['data']->editForm)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>