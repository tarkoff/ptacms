<?php
define('PTA_APP_DEBUG', true);

require_once realpath(dirname(dirname(dirname(__FILE__)))) . '/global/config.inc';

define('PTA_SITE_ID', 2);

define('PTA_CACHE_PATH', PTA_ROOT_PATH . '/system/cache/site');
define('PTA_LOG_PATH', PTA_ROOT_PATH . '/system/logs/site');

define('PTA_DESIGN_NAME', 'catalogio');

define('PTA_DESIGN_PATH', PTA_PUBLIC_PATH . '/style/site/' . PTA_DESIGN_NAME);
define('PTA_DESIGN_URL', '/public/style/site/' . PTA_DESIGN_NAME);
define('PTA_DESIGN_CSS_URL', PTA_DESIGN_URL . '/css');
define('PTA_DESIGN_IMAGES_URL', PTA_DESIGN_URL . '/img');

define('PTA_TEMPLATES_DIR', PTA_DESIGN_PATH . '/templates/');
define('PTA_TEMPLATES_CDIR', PTA_CACHE_PATH . '/templates_c/');
