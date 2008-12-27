<?php /* Smarty version 2.6.20, created on 2008-12-23 00:13:38
         compiled from Footer.tpl */ ?>
    <div id="footer">
    <?php if (isset ( $this->_tpl_vars['app']->debug )): ?>
    	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "debuger.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    </div>

</div>
</body>
</html>