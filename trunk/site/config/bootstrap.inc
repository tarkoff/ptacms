<?php

if (isset($_SERVER['DOCUMENT_ROOT'])) {
    $rootPath = $_SERVER['DOCUMENT_ROOT'];
} else {
    $rootPath = realpath(dirname(dirname(dirname(__FILE__))));    
}

require_once 'server.inc';
require_once 'config.inc';
require_once 'Initialize.php';

require_once $rootPath . '/libs/Zend/Loader.php';
require_once(SMARTY_DIR . 'Smarty.class.php');

set_include_path(
        implode(PATH_SEPARATOR,
                array(
                    $rootPath,
                    $rootPath . '/libs',
                    SITEPATH
                )
        ) . PATH_SEPARATOR . get_include_path()
    
);

Initialize::startInit();

