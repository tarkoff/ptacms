<?php
/**
 * Catalog Product Photo Model
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Catalog
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Photo.php 288 2010-03-28 16:10:01Z TPavuk $
 */

class KIT_Catalog_Product_Stat extends KIT_Model_Abstract
{
	private $_productId;
	private $_views;

	public function getProductId()
	{
		return $this->getId();
	}

	public function setProductId($id)
	{
		$this->setId($id);
	}

	public function getViews()
	{
		return $this->_views;
	}

	public function setView($views)
	{
		$this->_views = (int)$views;
	}

}
