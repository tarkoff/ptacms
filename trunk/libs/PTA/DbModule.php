<?php

class PTA_DbModule extends PTA_Module {
	protected $_peer = null;
	private $_vars = array();
	
	public function __construct($prefix, $tpl=null)
	{
		parent::__construct($prefix, $tpl);

		$peer = get_class($this) . '_Peer';
		$this->_peer = new $peer;
	}

	public function init()
	{
		;
	}
	
	public function run(){
		;
	}
	public function shutdown(){
		;
	}
	
}
