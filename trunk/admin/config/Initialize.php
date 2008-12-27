<?php

class Initialize
{
    
    public static function startInit()
    {
        Zend_Loader::registerAutoload();
        
        Zend_Loader::loadClass('Zend_Registry');

        self::initLoader();        
        self::initDB();
        self::initTemplates();
    }
    
    public function initLoader()
    {
//        Zend_Loader::registerAutoload();
    }
    
    public function initTemplates()
    {
        $smarty = new Smarty();

        $smarty->template_dir = STYLESDIR . '/templates/';
        $smarty->compile_dir = STYLESDIR . '/templates_c/';
        $smarty->config_dir =  SMARTY_DIR . '/configs/';
        $smarty->cache_dir = SMARTY_DIR . '/cache/';
        
        $smarty->debugging = APPDEBUG;
        
        Zend_Registry::set('Smarty', $smarty);
        
    }
    
    public function initDB()
    {
        $dbConfig = array(
                        'host'        => DBHOST,
                        'username'    => DBLOGIN,
                        'password'    => DBPASSWD,
                        'dbname'      => DBNAME,
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
    
}