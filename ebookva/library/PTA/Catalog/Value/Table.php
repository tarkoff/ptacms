<?php
/**
 * Short description for file
 *
 * @package Catalog
 * @copyright  2008 PTA Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Catalog_Value_Table extends PTA_DB_Table 
{
    /**
     * The default table name 
     */
    protected $_name = 'PRODUCTSVALUES';
    protected $_primary = 'PRODUCTSVALUES_ID';
    protected $_sequence = true;
    
    
    public function getValuesByProductId($productId)
    {
        $select = $this->select()->where(
                                        $this->getFieldByAlias('productId') . '=?',
                                        (int)$productId
                                    );
        $res = $this->fetchAll($select)->toArray();
//var_dump($res);        
        return $res;
    }
}
