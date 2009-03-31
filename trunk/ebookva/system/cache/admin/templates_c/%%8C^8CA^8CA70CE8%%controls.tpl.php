<?php /* Smarty version 2.6.21, created on 2009-03-28 16:41:06
         compiled from _generic/controls.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pta_const', '_generic/controls.tpl', 1, false),)), $this); ?>
<?php echo $this->_plugins['function']['pta_const'][0][0]->ptaConst(array('name' => "PTA_Control_Form_Field::TYPE_TEXT",'to' => 'fieldType'), $this);?>

<?php if ($this->_tpl_vars['field']->type == $this->_tpl_vars['fieldType']): ?>
	<input type="text" name="<?php echo $this->_tpl_vars['field']->name; ?>
" id="<?php echo $this->_tpl_vars['field']->name; ?>
" value="<?php echo $this->_tpl_vars['field']->value; ?>
" <?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?>class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?>/>
<?php endif; ?>

<?php echo $this->_plugins['function']['pta_const'][0][0]->ptaConst(array('name' => "PTA_Control_Form_Field::TYPE_PASSWORD",'to' => 'fieldType'), $this);?>

<?php if ($this->_tpl_vars['field']->type == $this->_tpl_vars['fieldType']): ?>
	<input type="password" name="<?php echo $this->_tpl_vars['field']->name; ?>
" id="<?php echo $this->_tpl_vars['field']->name; ?>
" value="<?php echo $this->_tpl_vars['field']->value; ?>
" <?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?>class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?>/>
<?php endif; ?>

<?php if ($this->_tpl_vars['field']->type == 'TextArea'): ?>
	<textarea name="<?php echo $this->_tpl_vars['field']->name; ?>
" id="<?php echo $this->_tpl_vars['field']->name; ?>
"<?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?> class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?>><?php if (! empty ( $this->_tpl_vars['field']->value )): ?><?php echo $this->_tpl_vars['field']->value; ?>
<?php endif; ?></textarea>
<?php endif; ?>

<?php echo $this->_plugins['function']['pta_const'][0][0]->ptaConst(array('name' => "PTA_Control_Form_Field::TYPE_SELECT",'to' => 'fieldType'), $this);?>

<?php if ($this->_tpl_vars['field']->type == $this->_tpl_vars['fieldType']): ?>
	<select name="<?php echo $this->_tpl_vars['field']->name; ?>
" id="<?php echo $this->_tpl_vars['field']->name; ?>
"<?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?> class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?>>
	<?php if (empty ( $this->_tpl_vars['field']->value )): ?>
		<?php $this->assign('selected', '0'); ?>
	<?php else: ?>
		<?php $this->assign('selected', $this->_tpl_vars['field']->value); ?>
	<?php endif; ?>
	<?php $_from = $this->_tpl_vars['field']->options; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		<?php if ($this->_tpl_vars['selected'] == $this->_tpl_vars['item'][0]): ?>
			<option value="<?php echo $this->_tpl_vars['item'][0]; ?>
" selected="selected"><?php echo $this->_tpl_vars['item'][1]; ?>
</option>
		<?php else: ?>
			<option value="<?php echo $this->_tpl_vars['item'][0]; ?>
"><?php echo $this->_tpl_vars['item'][1]; ?>
</option>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	</select>
<?php endif; ?>

<?php echo $this->_plugins['function']['pta_const'][0][0]->ptaConst(array('name' => "PTA_Control_Form_Field::TYPE_CHECKBOX",'to' => 'fieldType'), $this);?>

<?php if ($this->_tpl_vars['field']->type == $this->_tpl_vars['fieldType']): ?>
	<input type="checkbox" name="<?php echo $this->_tpl_vars['field']->name; ?>
<?php if (! empty ( $this->_tpl_vars['index'] )): ?>[<?php echo $this->_tpl_vars['index']; ?>
]<?php endif; ?>" id="<?php echo $this->_tpl_vars['field']->name; ?>
" <?php if (! empty ( $this->_tpl_vars['field']->checked )): ?>checked="checked"<?php endif; ?> <?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?>class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?> value="<?php if (! empty ( $this->_tpl_vars['field']->value )): ?><?php echo $this->_tpl_vars['field']->value; ?>
<?php endif; ?>"/>
<?php endif; ?>

<?php echo $this->_plugins['function']['pta_const'][0][0]->ptaConst(array('name' => "PTA_Control_Form_Field::TYPE_SUBMIT",'to' => 'fieldType'), $this);?>

<?php if ($this->_tpl_vars['field']->type == $this->_tpl_vars['fieldType']): ?>
	<input type="submit" name="<?php echo $this->_tpl_vars['field']->name; ?>
" id="<?php echo $this->_tpl_vars['field']->name; ?>
" value="<?php echo $this->_tpl_vars['field']->value; ?>
" <?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?>class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?>/>
<?php endif; ?>

<?php echo $this->_plugins['function']['pta_const'][0][0]->ptaConst(array('name' => "PTA_Control_Form_Field::TYPE_TEXTAREA",'to' => 'fieldType'), $this);?>

<?php if ($this->_tpl_vars['field']->type == $this->_tpl_vars['fieldType']): ?>
	<textarea name="<?php echo $this->_tpl_vars['field']->name; ?>
<?php if (! empty ( $this->_tpl_vars['index'] )): ?>[<?php echo $this->_tpl_vars['index']; ?>
]<?php endif; ?>" id="<?php echo $this->_tpl_vars['field']->name; ?>
" <?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?>class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?>><?php if (! empty ( $this->_tpl_vars['field']->value )): ?><?php echo $this->_tpl_vars['field']->value; ?>
<?php endif; ?></textarea>
<?php endif; ?>