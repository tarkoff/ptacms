<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Initialize
{
	
	public static function startInit()
	{
		Zend_Loader::registerAutoload();
		Zend_Loader::loadClass('Zend_Registry');

		self::initLoader();
		self::initDB();
		self::initTemplates();
		self::initRouter();
	}

	public static function initLoader()
	{
		Zend_Loader::registerAutoload();
	}

	public static function initTemplates()
	{
		$smarty = new Smarty();

		$smarty->template_dir = TEMPLATESDIR;
		$smarty->compile_dir = TEMPLATES_CDIR;
		$smarty->config_dir =  SMARTY_DIR . '/configs/';
		$smarty->cache_dir = SMARTY_DIR . '/cache/';

		$smarty->debugging = APPDEBUG;

		Zend_Registry::set('Smarty', $smarty);
	}

	public static function initDB()
	{
		$dbConfig = array(
						'host'		=> DBHOST,
						'username'	=> DBLOGIN,
						'password'	=> DBPASSWD,
						'dbname'	  => DBNAME,
						'profiler' => true
					);

		$db = Zend_Db::factory(DBADAPTER, $dbConfig);
		$profiler = $db->getProfiler();

		if (defined('PROFILER_TIME')) {
			$profiler->setFilterElapsedSecs( PROFILER_TIME );
		}

		$profiler->setFilterQueryType(
								Zend_Db_Profiler::SELECT |
								Zend_Db_Profiler::INSERT |
								Zend_Db_Profiler::UPDATE |
								Zend_Db_Profiler::DELETE
				   );

		$db->setProfiler($profiler);

		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);	 
	}

	public static function initRouter()
	{
		$router = PTA_Router::getInstance();
		$router->parseRoute();

		Zend_Registry::set('router', $router);
	}

}