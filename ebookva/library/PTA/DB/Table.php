<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Table.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_DB_Table extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_sequence = true;
    
    protected $_dbFields;
    
    public function __construct($config = array())
    {
        parent::__construct($config);

        if (empty($this->_dbFields)) {
        	$this->initStaticFields();
        }

        if (!empty($this->_cols)) {
        	$aliases = array_map(array($this, '_FieldToAlias'), (array)$this->_cols);
        	$this->_dbFields = array_combine((array)$aliases, (array)$this->_cols);
        }
    }
    
    public function initStaticFields()
    {
        $fields = (array)$this->getDefaultAdapter()->describeTable($this->getTableName());
        
    	foreach ($fields as $field) {
    		$alias = $this->_FieldToAlias($field['COLUMN_NAME']);
    		$this->_dbFields[$alias] = $field['COLUMN_NAME'];
    	}
    }
    
    public function getAll($fields = null)
    {
        $select = $this->select();
        
        if (!empty($fields)) {
            $fields = (array)$fields;
            foreach ($fields as $field) {
                if (!empty($field['field']) && !empty($field['value'])) {
                    $select->where("{$field['field']} = ?", $field['value']);
                }
            }
        }
        
        return $this->fetchAll($select)->toArray();
    }
    
    protected function _buildCondition($fields)
    {
        if (empty($fields)) {
            return '';
        }
        
        $cond = '';
        foreach ($fields as $field) {
            if ($field === end($fields)) {
                $cond .= "$field = ?";
            } else {
                $cond .= "$field = ? and ";
            }
        }
        
        return $cond;
    }
    
    public function findById($id)
    {
        return $this->find(intval($id))->toArray();
    }
        
    public function getPrimary()
    {
        $result = $this->_primary;
        
        if (is_array($this->_primary)) {
            if (count($result) < 2) {
                $result = current($result);
            }
        } elseif (is_string($this->_primary)) {
            $result = $this->_primary;
        }
        
        return $result;
    }
    
    public function getTableName()
    {
        return $this->_name;
    }
    
    public function getFullPrimary()
    {
        $result = null;
        
        if (is_array($this->_primary)) {
            foreach ($this->_primary as $primary) {
               $result[] = $this->getFullFieldName($primary); 
            }
            
            if (count($result) < 2) {
                $result = current($result);
            }
        } elseif (is_string($this->_primary)) {
            $result = $this->getFullFieldName($this->_primary, true);
        }
        
        return $result;
    }
    
    /**
     * return array of DB Table fields in format ALIAS => REALFIELDNAME
     *
     * @return array
     */
    public function getFields()
    {
        return (array)$this->_dbFields;
    }
    
    /**
     * return real DB Table field name by alias 
     *
     * @param string $alias
     * @return string
     */
    public function getFieldByAlias($alias)
    {
        $alias = strtoupper($alias);
        
        return (isset($this->_dbFields[$alias]) ? $this->_dbFields[$alias] : false);
    }
    
    /**
     * return full field name in format TABLENAME.FIELDNAME
     *
     * @param string $field
     * @return string
     */
    public function getFullFieldName($field, $isDbField = false)
    {
        if ($isDbField) {
            if (in_array($field, array_values($this->_dbFields))) {
                return "{$this->_name}.{$field}";
            }
        } else {
            $alias = strtoupper($field);
            if (in_array($alias, array_keys($this->_dbFields))) {
                return "{$this->_name}.{$this->_dbFields[$alias]}";
            }
        }
        
        return $field;
    }
    
	/**
	 * extract field alias from full fiield name
	 *
	 * @param string $field
	 * @return string
	 */
	private function _FieldToAlias($field)
	{
	    list($table, $alias) = explode('_', $field);
	    
	    return (empty($alias) ? $table : $alias);
	}
	
	/**
	 * delete from table by fields
	 *
	 * @param array $fields
	 * @return boolean
	 */
	public function clearByFields($fields)
	{
	    $fields = (array)$fields;
	    
	    $where = '';	    
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (!empty($field['field']) && !empty($field['value'])) {
                    //$where = "{$field['field']} = {$field['value']}";
                    if ($field === end($fields)) {
                        $where .= $this->_db->quoteInto(" {$field['field']} = ?", $field['value']);
                    } else {
                        $where .= $this->_db->quoteInto(" {$field['field']} = ? and", $field['value']);
                    }
                }
            }
            
        }
        
        return $this->delete($where);
	}
    
}
