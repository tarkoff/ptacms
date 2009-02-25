<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: Table.php 5 2008-12-27 18:39:21Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_CategoryField_Table extends PTA_DB_Table 
{
    /**
     * The default table name 
     */
    protected $_name = 'CATEGORIESFIELDS';
    protected $_primary = 'CATEGORIESFIELDS_ID';
    protected $_sequence = true;
    
    /**
     * find all category fields
     *
     * @param unknown_type $categoryId
     * @param unknown_type $equal
     * @return unknown
     */
    public function getFieldsByCategory($categoryId, $equal = true)
    {
    	$select = $this->select()->from(
    								$this->getTableName(),
    								array_values($this->getFields())
    							);
    	if ($equal) {
        	$select->where(
        				$this->getFieldByAlias('categoryId') . ' = ?',
                	    (int)$categoryId
                    );
    	} else {
        	$select->where(
            	        $this->getFieldByAlias('categoryId') . ' <> ?',
                	    (int)$categoryId
                    );
    	}
        $fieldsTable = new PTA_Catalog_Field_Table();
    	$select->join(
    				array('fields' => $fieldsTable->getTableName()),
    				$this->getFullFieldName('fieldId') . ' = fields.' . $fieldsTable->getPrimary(),
    				array('fields.' . $fieldsTable->getFieldByAlias('title'))
    			);
    	
        $select->order($this->getFieldByAlias('sortOrder'));
        $select->setIntegrityCheck(false);

        return $this->fetchAll($select)->toArray();
    }
    
    public function getFieldsByNotCategory($categoryId)
    {
    	return $this->getFieldsByCategory($categoryId, false);
    }
    
    public function clearbyCategoryId($categoryId)
    {
        $fields = array(
                    array(
                    	'field' => $this->getFieldByAlias('categoryId'),
                    	'value' => (int)$categoryId
                    )
                  );
        return $this->clearByFields($fields);
    }
}
