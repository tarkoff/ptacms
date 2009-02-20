<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Object.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

abstract class PTA_DB_Object extends PTA_Object 
{
    protected $_id;
    protected $_table;

    function __construct ($prefix)
    {
        $this->setPrefix($prefix);
        
        $tableName = get_class($this) . '_Table';
        $this->_table = new $tableName;
    }
        
    public function getId()
    {
        return $this->_id;
    }
    
    public function setId($value)
    {
        $this->_id = (int)$value;
    }
    
    public function getTable()
    {
        return $this->_table;
    }
    
    public function setTable(PTA_DB_Table $table)
    {
        $this->_table = $table;
    }

	/**
 	 * Load object By ID
	 *
	 * @method loadById
	 * @access public
	 * @param int $id
	 * @return boolean
	*/	
    public function loadById($id)
    {
        $info = $this->getTable()->findById(intval($id));

        if (empty($info)) {
            return false;
        }
        
        $this->loadFrom(current($info));
        return true;
    }
    
	/**
	 * Load All Objects
	 *
	 * @method getAll
	 * @access public
	 * @return array
	*/	
    public function getAll()
    {
        $objectsArray = $this->getTable()->getAll();
        
        if (empty($objectsArray)) {
            return false;
        }
        
        $objects = array();
        $className = get_class($this);
        foreach ($objectsArray as $objectRecord) {
            $object = new $className($this->getPrefix() . '_' . $objectRecord[$this->getTable()->getPrimary()]);
            $objects[] = $object->loadFrom($objectRecord ); 
        }
        
        return $objects;
    }
        
    
	/**
	 * save data to DB
	 *
	 * @method save
	 * @param array $data
	 * @access public
	 * @return boolean
	*/	
    public function save()
    {
        $primary = $this->_table->getPrimary();
        
        $data = new stdClass();
        $data = (array)$this->loadTo($data, true);

        if (empty($data)) {
        	return false;
        }

        try {
            if ($this->getId()) {
                $where = $this->_table->getAdapter()->quoteInto("$primary = ?", (int)$this->getId());
                $result = $this->_table->update($data, $where);
            } else {
                $result = $this->_table->insert($data);
            }
        } catch (PTA_Exception $e) {
            echo $e->getMessage();
            return false;
        }
        
        return $result;
    }
    
	/**
 	 * Remove object from database
	 *
	 * @method remove
	 * @access public
	 * @return boolean
	*/	
    public function remove()
    {
        $primary = $this->_table->getPrimary();

        $where = $this->_table->getAdapter()->quoteInto("$primary = ?", (int)$this->getId());
        return $this->_table->delete($where);
    }
    
	/**
 	 * set properties from other objects or sql results
	 *
	 * @method loadFrom
	 * @access public
	 * @param array $info
	 * @param object $manufacturer
	 * @return object
	*/	
    public function loadFrom($info)
    {
        $table = $this->getTable();        
        $fields = $table->getFields();
        
        $aliases = array_keys($fields);
        $realFields = array_values($fields);

        $info = (array)$info;
        foreach ($info as $alias=>$value) {
            $method = '';
            if (in_array(strtoupper($alias), $aliases)) {
                $method = 'set' . ucfirst(strtolower($alias));
            } elseif (false !== ($key = array_search(strtoupper($alias), $realFields))) {
                $method = 'set' .  ucfirst(strtolower($aliases[$key]));
            }
            
            if (!empty($method) && method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

	/**
 	 * set properties from object to stdClass
	 *
	 * @method loadTo
	 * @access public
	 * @param stdClass $info
	 * @param object $object
	 * @return stdClass
	*/	
    public function loadTo(&$info, $isDbFields = false)
    {
        $table = $this->getTable();        
        $fields = $table->getFields();

        foreach ($fields as $alias=>$field) {
            $method = 'get' . ucfirst(strtolower($alias));
            $alias = ( $isDbFields ? $field : strtolower($alias));               
            if (method_exists($this, $method)) {
                $info->$alias = $this->$method();
            }
        }

        return $info;
    }
    
}
