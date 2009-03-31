<?php /* Smarty version 2.6.21, created on 2009-03-28 09:54:52
         compiled from debuger.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'debuger.tpl', 2, false),)), $this); ?>
	<div id="debuger" class="appDebuger">
	<?php echo smarty_function_math(array('equation' => "b / 1024",'b' => $this->_tpl_vars['app']->memoryUsage,'assign' => 'kb'), $this);?>

	<?php echo smarty_function_math(array('equation' => "kb / 1024",'kb' => $this->_tpl_vars['kb'],'assign' => 'mb'), $this);?>

		<table>
			<tr><td>Application Init Time:</td><td><?php echo $this->_tpl_vars['app']->appInitTime; ?>
</td></tr>
			<tr><td>Application Run Time:</td><td><?php echo $this->_tpl_vars['app']->appRunTime; ?>
</td></tr>
			<tr><td>Application Shutdwon Time:</td><td><?php echo $this->_tpl_vars['app']->appShutdownTime; ?>
</td></tr>
			<tr><td>Application Total Exec Time:</td><td><?php echo $this->_tpl_vars['app']->globalAppTime; ?>
</td></tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>MySQL Queries Count:</td><td><?php echo $this->_tpl_vars['app']->sqlQueriesCnt; ?>
</td></tr>
			<tr><td>MySQL Queries Run time:</td><td><?php echo $this->_tpl_vars['app']->sqlRunTime; ?>
</td></tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>Memory Usage:</td><td><?php echo $this->_tpl_vars['app']->memoryUsage; ?>
 Bytes / <?php echo $this->_tpl_vars['kb']; ?>
 Kb / <?php echo $this->_tpl_vars['mb']; ?>
 Mb)</td></tr>
		</table>
	</div>