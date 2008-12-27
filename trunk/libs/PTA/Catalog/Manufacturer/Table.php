<?php
/**
 * Catrgorys
 *  
 * @author taras
 * @version 
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
