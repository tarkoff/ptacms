<?php
class PTA_Category extends PTA_DB_Object 
{

    private $_parentId;
    private $_title;
    
    /**
     * 
     */
    
    public function getParentCategoryid()
    {
        return $this->_parentId;
    }
    
    public function setParentCategoryid($value)
    {
        $this->_parentId = (int)$value;
    }
    
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setTitle($title)
    {
        $this->_title = $title;
    }
    
	/**
 	 * loadById - Load Category By ID
	 *
	 * @method loadById
	 * @access public
	 * @param int $id
	 * @return boolean
	*/	
    public function loadById($id)
    {
        $info = $this->_table->getCategoryById(intval($id));
        
        if (empty($info)) {
            return false;
        }
        
        return $this->fillFrom($info[0], $this);
    }
    
	/**
	 * getAllCategories - Load all Categories
	 *
	 * @method getAllCategories
	 * @access public
	 * @return array
	*/	
    public function getAll()
    {
        $categoriesArray = $this->_table->getAll();
        
        if (empty($categoriesArray)) {
            return false;
        }
        
        $categories = array();
        foreach ($categoriesArray as $cat) {
            $category = new self('Category_' . $cat['CATEGORIES_ID']);
            $categories[] = $this->fillFrom($cat, $category); 
        }
        
        return $categories;
    }
    
	/**
 	 * _buildCategory - set qll properties from DB result
	 *
	 * @method _buildCategory
	 * @access private
	 * @param array $res
	 * @return void
	*/	
    public function fillFrom($res, $category = null)
    {
        if (empty($category)) {
            $category = $this;
        }
        
        $category->setId(@$res['CATEGORIES_ID']);
        $category->setParentCategoryid(@$res['CATEGORIES_PARENTID']);
        $category->setTitle(@$res['CATEGORIES_TITLE']);
        
        return $category;
    }
    
	/**
	 * save - save category to DB
	 *
	 * @method save
	 * @access public
	 * @return boolean
	*/	
    public function save()
    {
        $data = array(
                    'CATEGORIES_TITLE'	    => mysql_real_escape_string($this->getTitle()),
                    'CATEGORIES_PARENTID'	=> (int)$this->getParentCategoryid()
                );
                
        return parent::save($data);
    }
    
}
