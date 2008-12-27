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
        
        $smarty->debugging = true;
        
        Zend_Registry::set('Smarty', $smarty);
        
    }
    
    public function initDB()
    {
        $dbConfig = array(
                        'host'        => DBHOST,
                        'username'    => DBLOGIN,
                        'password'    => DBPASSWD,
                        'dbname'      => DBNAME
                    );
         
         $db = Zend_Db::factory(DBADAPTER, $dbConfig);
         
         Zend_Db_Table_Abstract::setDefaultAdapter($db);
         Zend_Registry::set('db', $db);     
    }
    
}