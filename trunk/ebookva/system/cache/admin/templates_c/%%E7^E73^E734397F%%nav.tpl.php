<?php /* Smarty version 2.6.21, created on 2009-03-22 18:21:42
         compiled from _generic/nav.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_image', '_generic/nav.tpl', 15, false),)), $this); ?>
<div class="nav">
	<div>
		<select name="rpp" id="rpp" onchange="setRpp(this.value)">
		<?php $_from = $this->_tpl_vars['view']->rpps; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['rpp']):
?>
			<?php if ($this->_tpl_vars['view']->rpp == $this->_tpl_vars['rpp']): ?>
				<option value="<?php echo $this->_tpl_vars['key']; ?>
" selected="selected"><?php echo $this->_tpl_vars['rpp']; ?>
</option>
			<?php else: ?>
				<option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['rpp']; ?>
</option>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		</select>
	</div>
	<div><em class="btnseparator"></em></div>
	<div>
		<a href="<?php echo $this->_tpl_vars['activeModule']->url; ?>
page/1"><?php echo smarty_function_html_image(array('file' => (@IMAGESURL)."/view/pager/first.png",'alt' => 'First Page'), $this);?>
</a>
	</div>
	<div>
		<a href="<?php echo $this->_tpl_vars['activeModule']->url; ?>
page/1"><?php echo smarty_function_html_image(array('file' => (@IMAGESURL)."/view/pager/back.png",'alt' => 'First Page'), $this);?>
</a>
	</div>
	<div>
		<a href="<?php echo $this->_tpl_vars['activeModule']->url; ?>
page/1"><?php echo smarty_function_html_image(array('file' => (@IMAGESURL)."/view/pager/forward.png",'alt' => 'First Page'), $this);?>
</a>
	</div>
	<div>
		<a href="<?php echo $this->_tpl_vars['activeModule']->url; ?>
page/1"><?php echo smarty_function_html_image(array('file' => (@IMAGESURL)."/view/pager/last.png",'alt' => 'First Page'), $this);?>
</a>
	</div>
</div>