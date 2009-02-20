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
    
    
    public function getFieldsByCategory($categoryId)
    {
        $select = $this->select()->where(
                                        $this->getFieldByAlias('categoryId') . '=?',
                                        (int)$categoryId
                                    );
        $select->order($this->getFieldByAlias('sortOrder'));

        return $this->fetchAll($select)->toArray();
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
