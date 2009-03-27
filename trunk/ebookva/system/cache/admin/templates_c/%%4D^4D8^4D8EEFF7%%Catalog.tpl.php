<?php /* Smarty version 2.6.21, created on 2009-03-27 00:23:39
         compiled from Catalog.tpl */ ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'list'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Fields/view.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['data']->view)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'edit'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Fields/editForm.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['data']->editForm)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>