<?php /* Smarty version 2.6.19, created on 2009-03-21 15:33:50
         compiled from _generic/view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_image', '_generic/view.tpl', 11, false),array('function', 'cycle', '_generic/view.tpl', 30, false),)), $this); ?>
<div class="viewDiv">
<table class="viewTable" cellspacing="0" cellpadding="0" cols="<?php echo $this->_tpl_vars['view']->fieldsCount; ?>
">
	<?php if (! empty ( $this->_tpl_vars['view']->singleActions )): ?>
		<tr>
			<th colspan="<?php echo $this->_tpl_vars['view']->fieldsCount; ?>
" style="text-align:left;">
				<div class="mtb">
				<?php $_from = $this->_tpl_vars['view']->singleActions; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['action']):
?>
					<span class="mtbItem">
						<a href="<?php echo $this->_tpl_vars['action']->url; ?>
">
						<?php if (! empty ( $this->_tpl_vars['action']->img )): ?>
							<span><?php echo smarty_function_html_image(array('file' => (@IMAGESURL)."/view/actions/".($this->_tpl_vars['action']->img),'alt' => ($this->_tpl_vars['action']->title)), $this);?>
</span>
						<?php endif; ?>
							<span><?php echo $this->_tpl_vars['action']->title; ?>
</span>
						</a>
					</span>
					<em class="btnseparator"></em>
				<?php endforeach; endif; unset($_from); ?>
				</div>
			</th>
		</tr>
	<?php endif; ?>
	<tr>
	<?php $_from = $this->_tpl_vars['view']->fields; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fieldTitle']):
?>
		<th class="columnNames" valign="middle">
		<?php echo $this->_tpl_vars['fieldTitle']; ?>

		</th>
	<?php endforeach; endif; unset($_from); ?>
	</tr>
	<?php $_from = $this->_tpl_vars['view']->data; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['record']):
?>
	<tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#FFFFFF,#EEEEEE"), $this);?>
">
		<?php $_from = $this->_tpl_vars['record']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
			<td><?php echo $this->_tpl_vars['field']; ?>
</td>
		<?php endforeach; endif; unset($_from); ?>
		<?php if (! empty ( $this->_tpl_vars['view']->commonActions )): ?>
			<td>
			<?php $this->assign('editField', ($this->_tpl_vars['view']->actionField)); ?>
			<?php $_from = $this->_tpl_vars['view']->commonActions; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['action']):
?>
				<a href="<?php echo $this->_tpl_vars['action']->url; ?>
/<?php echo $this->_tpl_vars['record'][$this->_tpl_vars['editField']]; ?>
/">
					<?php echo smarty_function_html_image(array('file' => (@IMAGESURL)."/view/actions/".($this->_tpl_vars['action']->img),'alt' => ($this->_tpl_vars['action']->title),'title' => ($this->_tpl_vars['action']->title)), $this);?>

				</a>
			<?php endforeach; endif; unset($_from); ?>
			</td>
		<?php endif; ?>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th colspan="<?php echo $this->_tpl_vars['view']->fieldsCount; ?>
" style="text-align:left;" valign="middle" height="30px">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_generic/nav.tpl", 'smarty_include_vars' => array('form' => $this->_tpl_vars['data'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</th>
	</tr>
</table>
</div>
<?php echo '
<script type="text/javascript" language="JavaScript">
function setRpp(rpp)
{
	var ww;
	ww = \'?rpp=\' + rpp;
 
	location.href = ww;
}
</script>
'; ?>