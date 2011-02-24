<?php
/**
 * Catalog Post Model
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
 * @version    $Id: Field.php 397 2010-05-04 20:46:34Z TPavuk $
 */

class KIT_Catalog_Post extends KIT_Model_Abstract
{
	private $_productId;
	private $_postDate;
	private $_post;
	private $_author;

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = (int)$id;
	}

	public function getPostDate()
	{
		return $this->_postDate;
	}

	public function setPostDate($date)
	{
		$this->_postDate = $date;
	}

	public function getPost()
	{
		return $this->_post;
	}

	public function setPost($post)
	{
		$this->_post = $post;
	}
	
	public function getAuthor()
	{
		return $this->_author;
	}
	
	public function setAuthor($author)
	{
		$this->_author = $author;
	}
	
}
