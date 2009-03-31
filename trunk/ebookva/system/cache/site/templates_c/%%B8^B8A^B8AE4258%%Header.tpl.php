<?php /* Smarty version 2.6.21, created on 2009-03-29 10:59:40
         compiled from Header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_image', 'Header.tpl', 15, false),)), $this); ?>
<div id="top">
	<ul>
		<li><a href="/" <?php if (empty ( $this->_tpl_vars['TopMenu']->selected )): ?>class="hover"<?php endif; ?>>Главная</a></li>
		<?php $_from = $this->_tpl_vars['TopMenu']->Categories; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cat']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['alias'] => $this->_tpl_vars['title']):
        $this->_foreach['cat']['iteration']++;
?>
		<?php if (($this->_foreach['cat']['iteration'] == $this->_foreach['cat']['total'])): ?>
			<li class="noImg <?php if ($this->_tpl_vars['TopMenu']->selected == $this->_tpl_vars['alias']): ?>hover<?php endif; ?>"><a href="<?php echo $this->_tpl_vars['TopMenu']->url; ?>
<?php echo $this->_tpl_vars['alias']; ?>
"><?php echo $this->_tpl_vars['title']; ?>
</a></li>
		<?php else: ?>
			<li><a <?php if ($this->_tpl_vars['TopMenu']->selected == $this->_tpl_vars['alias']): ?>class="hover"<?php endif; ?> href="<?php echo $this->_tpl_vars['TopMenu']->url; ?>
<?php echo $this->_tpl_vars['alias']; ?>
"><?php echo $this->_tpl_vars['title']; ?>
</a></li>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	</ul>
</div>
<div class="logoBlock">
	<a href="/">
		<?php echo smarty_function_html_image(array('file' => (@IMAGESURL)."/logo.gif",'alt' => @BASEURL), $this);?>

	</a>
</div>