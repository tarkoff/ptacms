<?php
/**
 * Catalog Currency Model
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
 * @version    $Id: Brand.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_Currency extends KIT_Model_Abstract
{
	private $_title;
	private $_reduction;

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function getReduction()
	{
		return $this->_reduction;
	}

	public function setReduction($reduction)
	{
		$this->_reduction = $reduction;
	}
}
