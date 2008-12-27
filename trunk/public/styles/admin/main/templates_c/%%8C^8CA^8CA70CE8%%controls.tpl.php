<?php /* Smarty version 2.6.20, created on 2008-12-22 22:37:20
         compiled from _generic/controls.tpl */ ?>
<?php if ($this->_tpl_vars['field']->type == 'Text'): ?>
	<input type="text" name="<?php echo $this->_tpl_vars['field']->name; ?>
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

<?php if ($this->_tpl_vars['field']->type == 'Select'): ?>
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

<?php if ($this->_tpl_vars['field']->type == 'Checkbox'): ?>
	<input type="checkbox" name="<?php echo $this->_tpl_vars['field']->name; ?>
" id="<?php echo $this->_tpl_vars['field']->name; ?>
" <?php if (! empty ( $this->_tpl_vars['field']->value )): ?>checked="checked"<?php endif; ?> <?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?>class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?>/>
<?php endif; ?>

<?php if ($this->_tpl_vars['field']->type == 'Submit'): ?>
	<input type="submit" name="<?php echo $this->_tpl_vars['field']->name; ?>
" id="<?php echo $this->_tpl_vars['field']->name; ?>
" value="<?php echo $this->_tpl_vars['field']->value; ?>
" <?php if (! empty ( $this->_tpl_vars['field']->cssClass )): ?>class="<?php echo $this->_tpl_vars['field']->cssClass; ?>
"<?php endif; ?><?php if (! empty ( $this->_tpl_vars['field']->disabled )): ?> disabled="disabled"<?php endif; ?>/>
<?php endif; ?>