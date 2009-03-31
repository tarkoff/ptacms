<?php /* Smarty version 2.6.21, created on 2009-03-29 15:56:46
         compiled from LeftMenu.tpl */ ?>
<div id="left">
	<p class="catagory">Тематика</p>
	<ul class="lftNav">
	<?php if (!function_exists('smarty_fun_cattree')) { function smarty_fun_cattree(&$smarty, $params) { $_fun_tpl_vars = $smarty->_tpl_vars; $smarty->assign($params);  ?>
	<?php $_from = $smarty->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$smarty->_foreach['cat'] = array('total' => count($_from), 'iteration' => 0);
if ($smarty->_foreach['cat']['total'] > 0):
    foreach ($_from as $smarty->_tpl_vars['alias'] => $smarty->_tpl_vars['title']):
        $smarty->_foreach['cat']['iteration']++;
?>
		<?php if (($smarty->_foreach['cat']['iteration'] == $smarty->_foreach['cat']['total'])): ?>
			<li class="noImg"><a href="<?php echo $smarty->_tpl_vars['data']->url; ?>
<?php echo $smarty->_tpl_vars['alias']; ?>
"><?php echo $smarty->_tpl_vars['title']; ?>
</a></li>
		<?php else: ?>
			<li><a href="<?php echo $smarty->_tpl_vars['data']->url; ?>
<?php echo $smarty->_tpl_vars['alias']; ?>
"><?php echo $smarty->_tpl_vars['title']; ?>
</a></li>
		<?php endif; ?>
		<?php if (! empty ( $smarty->_tpl_vars['category']['childs'] )): ?>
			<ul><?php smarty_fun_cattree($smarty, array('list'=>$smarty->_tpl_vars['childs']));  ?></ul>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php  $smarty->_tpl_vars = $_fun_tpl_vars; }} smarty_fun_cattree($this, array('list'=>$this->_tpl_vars['data']->Categories));  ?>
	</ul>
</div>