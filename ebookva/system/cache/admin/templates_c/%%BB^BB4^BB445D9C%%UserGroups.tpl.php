<?php /* Smarty version 2.6.19, created on 2009-03-21 15:42:40
         compiled from UserGroups.tpl */ ?>
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