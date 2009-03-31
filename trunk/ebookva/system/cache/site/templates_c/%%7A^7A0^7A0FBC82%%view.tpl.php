<?php /* Smarty version 2.6.21, created on 2009-03-28 09:17:18
         compiled from Categories/view.tpl */ ?>
<div id="mainCategories">
	<div>
	<?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cat']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['catId'] => $this->_tpl_vars['category']):
        $this->_foreach['cat']['iteration']++;
?>
		<h1><a href="<?php echo $this->_tpl_vars['activeModule']->url; ?>
<?php echo $this->_tpl_vars['catId']; ?>
"><?php echo $this->_tpl_vars['category']['title']; ?>
</a></h1>
		<?php $_from = $this->_tpl_vars['category']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['childCats'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['childCats']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['childCatId'] => $this->_tpl_vars['childCategory']):
        $this->_foreach['childCats']['iteration']++;
?>
			<h2><a href="<?php echo $this->_tpl_vars['activeModule']->url; ?>
<?php echo $this->_tpl_vars['childCatId']; ?>
"><?php echo $this->_tpl_vars['childCategory']['title']; ?>
</a></h2>
		<?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
	</div>
</div>