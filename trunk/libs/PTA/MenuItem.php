<?php

class PTA_MenuItem
{
	private $_id = null;
	private $_title = null;
	
	public function __construct($id=null, $title=null)
	{
		if (!empty($id)) {
			$this->setId($id);
		}

		if (!empty($title)) {
			$this->setTitle($title);
		}
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function setId($id)	
	{
		$this->_id = intval($id);
	}
	
	public function getTitle()
	{
		return $this->_title;
	}
	
	public function setTitle($title)
	{
		$this->_title = $title;
	}
}
