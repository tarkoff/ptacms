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

class PTA_Router extends PTA_Object {
	private static $_instance;	

	const SITEPART = 1;
	const ADMINPART = 2;

	//Dir, File, Mix
	protected $_routeMode = 'File';
	
	protected $_sitePart = self::SITEPART;
	protected $_activeModule = '';
	protected $_activeAction = '';
	protected $_controler = null;
	
	private function __construct() {
  	}
 
  	private function __clone() {
  	}

  	public static function getInstance() {
  		if (!self::$_instance instanceof self) {
  			self::$_instance = new self;
    	}
    return self::$_instance;
  }

  public function parseRoute($route=null)
  {
  	if(!empty($route)){
  		$route = parse_url($route);
  	}else{
  		$route = parse_url($_SERVER['REQUEST_URI']);
  	}
 
  	if ( isset($route['query'])&&empty($route['query']) ) {
  		$this->getQueryVars($route['query']);
  	}
  	
  	if (empty($route['path'])) return ;
  	
  	$route['path'] = trim($route['path'], "/");

  	$parts = explode("/", $route['path']);
//  	array_shift($parts);

    if ($this->isAdminPart($parts)) {
        $this->_sitePart = self::ADMINPART;
        array_shift($parts);
    }
    
    $partsCount = count($parts);
    if ($partsCount<2) {
        $this->_activeModule = empty($parts[0]) ? '' : ucfirst($parts[0]);
        array_shift($parts);
    } else {
        $this->_activeModule = empty($parts[0]) ? '' : ucfirst($parts[0]);
        array_shift($parts);
        $this->_activeAction = empty($parts[0]) ? '' : ucfirst($parts[0]);
        array_shift($parts);
    }

    if (count($parts)) {
        $this->setVar('query', $parts);
    }
  }
  
  public function isAdminPart($parts)
  {
      return (bool)(strtolower($parts[0])=='admin');
  }
  
  public function getSitePart()
  {
  	return $this->_sitePart;
  }
  
  private function getQueryVars($query)
  {
  	$vars = explode("&", $query);
  		
  	foreach($vars as $var){
  		$tmp = explode("=", $var);
  		$this->setVar($tmp[0], $tmp[1]);
  	}
  	
  }
  
  public function getActiveModule()
  {
  	return $this->_activeModule;
  }
  
  public function getActiveAction()
  {
  	return $this->_activeAction;
  }
 
}
