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

class PTA_Catalog_Manufacturer_Table extends PTA_DB_Table 
{
    /**
     * The default table name 
     */
    protected $_name = 'MANUFACTURERS';
    protected $_primary = 'MANUFACTURERS_ID';
    protected $_sequence = true;
    
    /**
     * return all manufacturers by category id
     *
     * @param int $id
     */
    public function getByCategoryId($id)
    {
        $select = $this->select()->join(
        							'MANUFACTURERS_CATEGORIES', 
        							'MANUFACTURERS.MANUFACTURERS_ID=MANUFACTURERS_CATEGORIES.MANUFACTURERS_ID', 
                                    array()
                                   );
                                   
        return $this->fetchAll($select)->toArray();
    }

}
