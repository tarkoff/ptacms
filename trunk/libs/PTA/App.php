<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_App extends PTA_WebModule 
{
    private $_templateEngine;
    private $_modules = array();
    
    private $_appStartTime;
    private $_initStartTime;
    private $_runStartTime;
    private $_shutdownStartTime;

    private static $_instance;
    
    public function __construct($prefix, $tpl=null)
    {
        $this->_appStartTime = self::getmicrotime();
        
         $this->setPrefix($prefix);
        $this->setRequestVars();
        
        $this->_templateEngine = PTA_TemplateEngine::getInstance();
                
        if(!empty($tpl)){
            $this->getTemplateEngine()->registerTemplate($this->getPrefix(), $tpl);
            $this->setVar('tpl', $tpl);
        }
        
        self::$_instance = $this;
    }
    
    public static function getmicrotime() 
    { 
        list($usec, $sec) = explode(" ", microtime()); 
        return ((float)$usec + (float)$sec); 
    }
    
    
    public function setRequestVars()
    {
        $urlParams = @parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        
        if (empty($urlParams)) {
            return true;
        }
        
        $matches = array();
        preg_match_all("/(([a-z0-9_-]+)=([a-z0-9_-]+))+/", $urlParams, $matches);
       
        if (!empty($matches[2])) {
            for ($index = 0; $index < count($matches[2]); $index++) {
                $_REQUEST[$matches[2][$index]] = $matches[3][$index];
            }
        }
    }
    
    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self('app');
        }
        
        return self::$_instance;
    }
    
    public function initModules($modules = null)
    {
        if(empty($modules)) {
            return false;
        }
        
        foreach ($modules as $module) {
            $module->init();
        }
        
        return true;
    }
    
    public function init()
    {
        $this->_initStartTime = self::getmicrotime();
        
        parent::init();
        
        $this->_templateEngine->init();
        $this->initModules($this->getModules());

        $this->setVar('appInitTime', number_format((self::getmicrotime() - $this->_initStartTime), 4, '.', ''));
    }

    public function run()
    {
        $this->_runStartTime = self::getmicrotime();
                
        parent::run();

        $this->_templateEngine->run();
        $this->runModules($this->getModules());
        
        $this->setVar('appRunTime', number_format((self::getmicrotime() - $this->_runStartTime), 4, '.', ''));        
    }
    
    public function runModules($modules)
    {
        if (empty($modules)) {
            return false;
        }
            
        foreach ($modules as $module) {
            $module->run();
        }
        
        return true;
    }

    public function shutdown()
    {
        $this->_shutdownStartTime = self::getmicrotime();
                
        parent::shutdown();
        
        if (empty($this->_modules)) {
            return false;
        }
        
        foreach ($this->_modules as $module) {
            $module->shutdown();
        }
        
        $this->_sqlLog();
        
        $this->setVar('appShutdownTime', number_format((self::getmicrotime() - $this->_shutdownStartTime), 4, '.', ''));
        $this->setVar('globalAppTime', number_format((self::getmicrotime() - $this->_appStartTime), 4, '.', ''));
        
        $this->getTemplateEngine()->display();

        return true;
    }
    
    private function _sqlLog()
    {
        $db = $this->getDB();
        $profiler = $db->getProfiler();
        
        if (empty($profiler) || !$profiler->getEnabled()) {
            return false;
        }
        
        $queries = $profiler->getQueryProfiles();
        if (empty($queries)) {
            return false;
        }
        
        $db->beginTransaction();
        foreach ($queries as $query) {
            $data = array(
                        'SQLLOG_QUERY' => $query->getQuery(),
                        'SQLLOG_QUERYTYPE' => $query->getQueryType(),
                        'SQLLOG_RUNTIME' => $query->getElapsedSecs()
                    );
            
            $db->insert('SQLLOG', $data);
        }
        $db->commit();
         
        $this->setVar('sqlQueriesCnt', $profiler->getTotalNumQueries());
        $this->setVar('sqlRunTime', number_format($profiler->getTotalElapsedSecs(), 4, '.', ''));
        $this->setVar('memoryUsage', memory_get_peak_usage(true));
        $this->setVar('debug', APPDEBUG);

        return true;
    }
    
    public function getDB()
    {
        return Zend_Registry::get('db');
    }
    
    public function setDB($db)
    {
        Zend_Registry::set('db', $db);
    }
    
    public function getTemplateEngine()
    {
        return $this->_templateEngine;
    }
    
    public function setTemplateEngine($engine)
    {
        $this->_templateEngine = $engine;
    }
    
    public function insertModule($prefix, $module)
    {
        if (isset($this->_modules[$prefix] )) {
            return false;
        }
        
        try {
            $this->_modules[$prefix] = new $module($prefix);
        } catch (Zend_Exception $e){
            echo $e->getMessage();
        }
        
        return true;
    }

    public function getActiveModule()
    {
        $activeModule = $this->getModule('activeModule');
        
        return (isset($activeModule) ? $activeModule : false);
    }
    
    public function getModules()
    {
        return $this->_modules;
    }
    
    public function getModule($prefix)
    {
        return (isset($this->_modules[$prefix]) ? $this->_modules[$prefix] : false);
    }
    
}
