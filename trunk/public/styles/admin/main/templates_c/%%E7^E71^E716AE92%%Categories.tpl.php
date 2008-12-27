<?php /* Smarty version 2.6.20, created on 2008-12-22 22:42:05
         compiled from Categories.tpl */ ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'list'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Categories/view.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['data']->view)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'edit'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Categories/editForm.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['data']->editForm)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'categoryFields'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Categories/editFieldsForm.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['data']->editFieldsForm)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>