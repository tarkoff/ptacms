<?php

class PTA_Control_Pager extends PTA_Object 
{
    private static $_rpp = 20;
    
    private static $_currentPage = 1;
    private static $_lastPage = 1;
    
    private static $_itemsCount = 0;

    
    public static function getCurrentPage()
    {
        self::detectCurrentPage();
        
        return self::$_currentPage;
    }
    
    public static function detectCurrentPage()
    {
        if (isset($_REQUEST['page'])) {
            self::$_currentPage = (int)$_REQUEST['page'];
        }
    }
    
    public static function getPrevPage()
    {
        return ((self::getCurrentPage() - 1) < 0 ? 0 : self::getCurrentPage() - 1);
    }
    
    public static function getNextPage()
    {
        ((self::getCurrentPage() + 1) > (int)(self::$maxItems/self::$rpp) ? 0 : self::getCurrentPage() + 1);
        
        return self::$prevPage;
    }

    public static function setItemsCount($cnt)
    {
        self::$pagesCount = (int)($cnt / self::$rpp);
        
        self::$_itemsCount = $cnt;
    }
    
    public static function getRpp()
    {
        return self::$_rpp;
    }
    
    public function setRpp($rpp)
    {
        self::$rpp = (int) $rpp;
    }
    
    public static function incPage()
    {
        self::$_currentPage++;
    }
}