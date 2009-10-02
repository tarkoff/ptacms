<?php
/**
 * Catalog Product Comment
 *
 * @package PTA_Catalog
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Brand.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Post extends PTA_DB_Object
{
	private $_author;
	private $_post;
	private $_productId;
	private $_date;

	public function getAuthor()
	{
		return $this->_author;
	}

	public function setAuthor($author)
	{
		$this->_author = $author;
	}

	public function getPost()
	{
		return $this->_post;
	}

	public function setPost($post)
	{
		$this->_post = $post;
	}

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($id)
	{
		$this->_productId = intval($id);
	}

	public function getDate()
	{
		return $this->_date;
	}

	public function setDate($date)
	{
		$this->_date = $date;
	}
}
