<?php
/**
 * Catrgorys
 *  
 * @author taras
 * @version 
 */
class PTA_Catalog_Value_Table extends PTA_DB_Table 
{
    /**
     * The default table name 
     */
    protected $_name = 'PRODUCTSVALUES';
    protected $_primary = 'PRODUCTSVALUES_ID';
    protected $_sequence = true;
    
    
    public function getFieldsByCategoryId($categoryId)
    {
        $select = $this->select()->where(
                                        $this->getFieldByAlias('category') . '=?',
                                        (int)$categoryId
                                    );
        $res = $this->fetchAll($select)->toArray();
//var_dump($res);        
        return $res;
    }
}
