<?php
/**
 * Data View
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Control_View extends PTA_Object
{
	const DEFAULT_RPP = 20;
	
	const MODE_SIMPLEGRID = 0;
	const MODE_JGRID = 1;

	private $_table;
	private $_select;

	/**
	 * __construct
	 * 
	 * @param string $prefix
	 * @param object $object 
	 * @param array $fields
	 * @param int $workMode
	 * @access public
	 */
	public function __construct($prefix, $object = null, $fields = null, $workMode = self::MODE_SIMPLEGRID)
	{
		if (empty($prefix) || empty($object)) {
			return false;
		}
		$this->setPrefix($prefix);

		$this->setMinRpp(10);
		$this->setMaxRpp(100);
		//$this->setRpp(20);

		if (empty($object)) {
			return;
		}

		$this->_table = $object->getTable();
		$this->_select = $this->_table->select();
		$this->setVar('workMode', $workMode);

		if (!empty($fields)) {
			$this->_select->from($this->_table->getTableName(), (array)$fields);
		} else {
			$tableFields = array_values((array)$this->_table->getFields());
			if (empty($tableFields)) {
				$this->_select->from($this->_table->getTableName());
			} else {
				$this->_select->from($this->_table->getTableName(), $tableFields);
			}
		}
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
		$resultObject = $this->toString();

		$resultObject->rpps = array();
		$minRpp = $this->getMinRpp();
		$maxRpp = $this->getMaxRpp();
		$rpp = $this->getRpp();

		for ($rppStep = $minRpp; $rppStep <= $maxRpp; $rppStep += $minRpp) {
			$resultObject->rpps[$rppStep] = $rppStep;
		}

		$resultObject->rpp = $rpp;
		$page = $this->getPage();
		$lastPage = $this->getLastPage();

		$this->_select->limitPage($page, $rpp);
		//$result = $this->_select->query()->fetchAll();
		$this->_select->setIntegrityCheck(false);
		$result = $this->_table->fetchAll($this->_select)->toArray();

		$fields = (array)@array_keys(current($result));
		$resultObject->fields = array_map(array($this, '_FieldToAlias'), $fields);
		$resultObject->data = $result;
		$resultObject->commonActions = $this->getCommonActions();

		$resultObject->prevPage = (($page > 1) ? $page - 1 : 1);
		$resultObject->page = $page;
		$resultObject->lastPage = $lastPage = (empty($result) ? 1 : $lastPage);
		$resultObject->nextPage = (($page < $lastPage) ? $page + 1 : $lastPage);

		if (!empty($resultObject->commonActions)) {
			$resultObject->fieldsCount = @count($resultObject->fields) + 1;
			$resultObject->fields[] = 'Actions';
			$resultObject->actionField = $this->_table->getPrimary();
		} else {
			$resultObject->fieldsCount = @count($resultObject->fields);
		}

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
	 * set rpp 
	 *
	 * @param int $rpp
	 */
	public function setRpp($rpp)
	{
		$this->setVar('rpp', (int)$rpp);
		$this->getApp()->setCookie('rpp', $rpp, 0);
	}

	/**
	 * return current records per page
	 *
	 * @return int
	 */
	public function getRpp()
	{
		$rpp = $this->getVar('rpp');

		if (!empty($rpp)) {
			return $rpp;
		}

		$rpp = $this->getHttpVar('rpp');
		$minRpp = $this->getMinRpp();
		$maxRpp = $this->getMaxRpp();

		if (empty($rpp)) {
			$rpp = $this->getApp()->getCookie('rpp');
		}

		if (empty($rpp)) {
			$rpp = self::DEFAULT_RPP;
		} elseif ($rpp < $minRpp) {
			$rpp = $minRpp;
		} elseif ($rpp > $maxRpp) {
			$rpp = $maxRpp;
		}

		$this->setRpp($rpp);

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
	
	/**
	 * Get Current View Page
	 *
	 * @return int
	 */
	public function getPage()
	{
		$page = $this->getVar('page');
		if (!empty($page)) {
			return $page;
		}

		$page = $this->getHttpVar('page');
		$firstPage = 1;
		$lastPage = $this->getLastPage();

		if (empty($page)) {
			$page = (int)$this->getApp()->getCookie('page');
		}

		if ($page < $firstPage) {
			$page = $firstPage;
		} elseif ($page > $lastPage) {
			$page = $lastPage;
		}
		
		$this->setPage($page);

		return $page;
	}
	/**
	 * Set Current View Page
	 *
	 * @param int $page
	 */
	public function setPage($page)
	{
		$this->setVar('page', intval($page));
		$this->getApp()->setCookie('page', $page, 0);
	}
	
	/**
	 * Get Last View Page
	 *
	 * @return int
	 */
	public function getLastPage()
	{
		$page = $this->getVar('lastPage');
		if (!empty($page)) {
			return $page;
		}
		
		$recsCnt = $this->getTotalRecordsCnt();
		if (empty($recsCnt)) {
			$page = 1;
		} else {
			$page = ceil($recsCnt / $this->getRpp());
		}

		$this->setVar('lastPage', $page);

		return $page;
	}
	
	/**
	 * Get View Total Records Count
	 *
	 * @return int
	 */
	public function getTotalRecordsCnt($where = '')
	{
		$recCnt = $this->getVar('recsCnt');
		if (!empty($recCnt)) {
			return $recCnt;
		}

		$sql = 'select count(*) from '. $this->_table->getTableName();
		if (!empty($where)) {
			$sql .= ' where ' . $where;
		}

		$recCnt = $this->_table->getAdapter()->fetchOne($sql);

		$this->setTotalRecordsCnt($recCnt);
		return $recCnt;
	}
	
	/**
	 * Set View Total Records Count
	 *
	 * @param int $cnt
	 */
	public function setTotalRecordsCnt($recCnt)
	{
		$this->setVar('recsCnt', intval($recCnt));
	}
	
	/**
	 * Set View Table
	 *
	 * @param PTA_DB_Table $table
	 */
	public function setTable(PTA_DB_Table $table)
	{
		$this->_table = $table;
	}
	
	/**
	 * Set View Select /object
	 *
	 * @param Zend_Db_Table_Select $select
	 */
	public function setSelect(Zend_Db_Select $select)
	{
		$this->_select = $select;
	}

	/**
	 * Get View Select /object
	 *
	 * @return Zend_Db_Table_Select
	 */
	public function getSelect()
	{
		return $this->_select;
	}
}