<?php /* Smarty version 2.6.21, created on 2009-03-28 09:54:52
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
<?php if ($this->_tpl_vars['data']->tplMode == 'addFields'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Categories/addFieldsForm.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['data']->addFieldsForm)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'delFields'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Categories/delFieldsForm.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['data']->delFieldsForm)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']->tplMode == 'addProduct'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Categories/addProductForm.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['data']->addProductForm)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>