<?php

class PTA_Control_View extends PTA_Object 
{
	private $_table;

	/**
	 * __construct
	 * 
	 * @param string $prefix
	 * @param object $object 
	 * @param array $fields
	 * @access public
	 */
	public function __construct($prefix, $object, $fields = null)
	{
		if (empty($prefix) || empty($object)) {
			return false;
		}
		$this->setPrefix($prefix);

		$this->_select = $this->getApp()->getDb()->select();	
		$this->_table = $object->getTable();
		
		if (!empty($fields)) {
			$this->_select->from(
								$this->_table->getTableName(),
								(array)$fields
							);
		} else {
			$tableFields = array_values((array)$this->_table->getFields());
			if (empty($tableFields)) {
				$this->_select->from($this->_table->getTableName());
			} else {
		 		$this->_select->from(
									$this->_table->getTableName(),
									$tableFields
								);
			}
		}

		$this->setMinRpp(10);
		$this->setMaxRpp(100);
		$this->setRppStep(10);
	}

	/**
	 * join - inner join table to view
	 * 
	 * @param array $table
	 * @param string $condition 
	 * @param array $fields
	 * @access public
	 */
	public function join($table, $condition, $fields = array())
	{
		$this->_select->join($table, $condition, $fields);
	}

	/**
	 * leftJoin - left join table to view
	 * 
	 * @param array $table
	 * @param string $condition 
	 * @param array $fields
	 * @access public
	 */
	public function leftJoin($table, $condition, $fields = array())
	{
		$this->_select->joinLeft($table, $condition, $fields);
	}

	/**
	 * exec - get result set
	 * 
	 * @method exec 
	 * @access public
	 * @return array 
	 */
	public function exec()
	{
		$result = $this->_select->query()->fetchAll();
		$resultObject = $this->toString();

		$fields = (array)@array_keys(current($result));
		$resultObject->fields = array_map(array($this, '_FieldToAlias'), $fields);
		$resultObject->data = $result;
		$resultObject->commonActions = $this->getCommonActions();			

		if (!empty($resultObject->commonActions)) {
			$resultObject->fieldsCount = @count($resultObject->fields)+1;
			$resultObject->fields[] = 'Actions';
			$resultObject->actionField = $this->_table->getPrimary();
		} else {
			$resultObject->fieldsCount = @count($resultObject->fields);
		}

		$resultObject->rpps = array();
		$minRpp = $this->getMinRpp();
		$maxRpp = $this->getMaxRpp();
		$rppCnt = $this->getRppStep();
		for ($rpp = $minRpp; $rpp <= $maxRpp; $rpp += $rppCnt) {
			$resultObject->rpps[$rpp] = $rpp; 
		}

		$resultObject->rpp = $this->getRpp();
		$resultObject->singleActions = $this->getSingleActions();

		return $resultObject;
	}

	/**
	 * return main table name
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->_info['name'];
	}

	public function getPrimary()
	{
		return array_values($this->_info['primary']);
	}

	/**
	 * extract field alias from full fiield name
	 *
	 * @param string $field
	 * @return string
	 */
	private function _FieldToAlias($field)
	{
		list($table, $alias) = explode('_', $field);
		
		return (empty($alias) ? $table : $alias);
	}

	/**
	 * getMinRpp - get minimum recford by page count
	 *
	 * @return int
	 */
	public function getMinRpp()
	{
		return $this->getVar('minRpp');
	}

	/**
	 * setMinRpp - set minimum recford by page count
	 *
	 * @param int $rpp
	 */
	public function setMinRpp($rpp)
	{
		$this->setVar('minRpp', (int)$rpp);
	}

	/**
	 * getMaxRpp - get maximum recford by page count
	 *
	 * @return int
	 */
	public function getMaxRpp()
	{
		return $this->getVar('maxRpp');
	}

	/**
	 * setMaxRpp - set maximum record per page count
	 *
	 * @param int $rpp
	 */
	public function setMaxRpp($rpp)
	{
		$this->setVar('maxRpp', (int)$rpp);
	}

	/**
	 * getRppStep - return rpp step
	 *
	 * @return int
	 */
	public function getRppStep()
	{
		return $this->getVar('rppStep');
	}

	/**
	 * setRppStep  - set rpp step
	 *
	 * @param int $step
	 */
	public function setRppStep($step)
	{
		$this->setVar('rppStep', (int)$step);
	}

	/**
	 * return current records per page
	 *
	 * @return int
	 */
	public function getRpp()
	{
		$rpp = $this->getVar('rpp');
		if (empty($rpp)) {
			$rpp = ($this->getHttpVar('rpp') ? $this->getHttpVar('rpp') : $this->getMinRpp());
		}
		
		return $rpp;
	}

	/**
	 * return actions array
	 *
	 * @return array
	 */
	public function getSingleActions()
	{
		return $this->getVar('singleActions');
	}

	/**
	 * add single view action
	 *
	 * @param string $title
	 * @param string $image
	 */
	public function addSingleAction($title, $url, $image = null)
	{
		$action = new stdClass();
		
		$action->title = $title;
		$action->url = rtrim($url, '/');

		if (!empty($image)) {
			$action->img = $image;
		}

		$actions = (array)$this->getVar('singleActions');;
		$actions[] = $action;
		
		$this->setVar('singleActions', $actions);
	}

	/**
	 * add common view action
	 *
	 * @param string $title
	 * @param string $image
	 */
	public function addCommonAction($title, $url, $image = null)
	{
		$action = new stdClass();

		$action->title = $title;
		$action->url = rtrim($url, '/');
		
		if (!empty($image)) {
			$action->img = $image;
		}

		$actions = (array)$this->getVar('commonActions');;
		$actions[] = $action;
		
		$this->setVar('commonActions', $actions);
	}

	public function getCommonActions()
	{
		return $this->getVar('commonActions');
	}

}