<?php /* Smarty version 2.6.19, created on 2009-03-21 15:36:25
         compiled from _generic/form.tpl */ ?>
<form name="<?php echo $this->_tpl_vars['form']->name; ?>
" id="<?php echo $this->_tpl_vars['form']->name; ?>
" action="<?php echo $this->_tpl_vars['form']->action; ?>
" method="<?php echo $this->_tpl_vars['form']->method; ?>
" enctype="<?php echo $this->_tpl_vars['form']->enctype; ?>
" <?php if (! empty ( $this->_tpl_vars['formCss'] )): ?>class="<?php echo $this->_tpl_vars['formCss']; ?>
"<?php endif; ?>>
	<table cols="2" cellspacing="10px" <?php if (! empty ( $this->_tpl_vars['formTableCss'] )): ?>class="<?php echo $this->_tpl_vars['formTableCss']; ?>
"<?php endif; ?>>
	<?php if (! empty ( $this->_tpl_vars['form']->title )): ?>
		<tr>
			<th colspan="2" <?php if (! empty ( $this->_tpl_vars['formHeaderCss'] )): ?>class="<?php echo $this->_tpl_vars['formHeaderCss']; ?>
"<?php endif; ?> align="center"><?php echo $this->_tpl_vars['form']->title; ?>
</th>
		</tr>
	<?php endif; ?>
		<?php $_from = $this->_tpl_vars['form']->data; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
		<tr>
		<?php if (! empty ( $this->_tpl_vars['field']->isSubmit )): ?>
			<td colspan="2" align="center"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_generic/controls.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
		<?php else: ?>
			<td align="right"><?php echo $this->_tpl_vars['field']->label; ?>
 <?php if ($this->_tpl_vars['field']->mandatory): ?>*<?php endif; ?></td>
			<td align="left"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_generic/controls.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
		<?php endif; ?>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
</form>