<?php
define('PTA_APP_DEBUG', true);

require_once realpath(dirname(dirname(dirname(__FILE__)))) . '/global/config.inc';

define('PTA_SITE_ID', 1);

define('PTA_CACHE_PATH', PTA_ROOT_PATH . '/system/cache/admin');
define('PTA_LOG_PATH', PTA_ROOT_PATH . '/system/logs/admin');

define('PTA_DESIGN_NAME', 'default');

define('PTA_DESIGN_PATH', PTA_PUBLIC_PATH . '/style/admin/' . PTA_DESIGN_NAME);
define('PTA_DESIGN_URL', PTA_BASE_URL . '/public/style/admin/' . PTA_DESIGN_NAME);
define('PTA_DESIGN_CSS_URL', PTA_DESIGN_URL . '/css');
define('PTA_DESIGN_IMAGES_URL', PTA_DESIGN_URL . '/img');

define('PTA_TEMPLATES_DIR', PTA_DESIGN_PATH . '/templates/');
define('PTA_TEMPLATES_CDIR', PTA_CACHE_PATH . '/templates_c/');
