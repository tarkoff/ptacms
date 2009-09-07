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

	const JGRID_XML = 1;
	const JGRID_JSON = 2;

	private $_table;
	private $_select;
	private $_orderField;
	private $_orderDirection = 'ASC';
	private $_where = array('and' => array(), 'or' => array());

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
			$tableFields = $this->_table->getFields();
			if (empty($tableFields)) {
				$this->_select->from($this->_table->getTableName());
			} else {
				$this->_select->from($this->_table->getTableName(), array_values($tableFields));
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

	public function jGridMode()
	{
		return $this->getVar('workMode');
	}

	public function setJGridMode($mode = true)
	{
		$this->setVar('workMode', (boolean)$mode);
	}

	/**
	 * exec - get result set
	 * 
	 * @method exec 
	 * @access public
	 * @return array 
	 */
	public function exec($gridMode = self::JGRID_XML)
	{
		if ($this->jGridMode() && $gridMode) {
			return $this->_jGridExec($gridMode);
		} else {
			return $this->_simpleExec();
		}
	}

	/**
	 * Prepear view for using in ajax mode
	 *
	 * @param int $gridMode
	 * @return stdClass|string
	 */
	private function _jGridExec($gridMode = self::JGRID_XML)
	{
		$this->setPage(isset($_GET['page']) ? intval($_GET['page']) : 1);
		$this->setRpp(isset($_GET['rows']) ? intval($_GET['rows']) : $this->getRpp());
		$this->setOrderDirection(isset($_GET['sord']) ? $_GET['sord'] : 'ASC');
		$this->setOrderField(empty($_GET['sidx']) ? null : $_GET['sidx']);
		if (isset($_GET['_search']) && $_GET['_search'] == true) {
			$this->_setFilterOptions();
		}

		if ($gridMode == self::JGRID_XML) {
			$this->_buildXml();
		} else {
			$this->_buildJson();
		}
	}
	
	private function _buildXml()
	{
		$simpleRes = $this->_simpleExec();
		$indexField = $this->_table->getPrimary();

		$xml = new DOMDocument('1.0', 'utf-8');
		$rows = $xml->appendChild($xml->createElement('rows'));
		$rows->appendChild($xml->createElement('page', $this->getPage()));
		$rows->appendChild($xml->createElement('total', $this->getLastPage()));
		$rows->appendChild($xml->createElement('records', $this->getTotalRecordsCnt()));
		foreach ($simpleRes->data as $record) {
			$row = $xml->createElement('row');
			$idAttr = $xml->createAttribute('id');
			$row->appendChild($idAttr->appendChild($xml->createTextNode($record[$indexField])));
			foreach ($record as $fieldValue) {
				$field = $xml->createElement('cell');
				if (is_numeric($fieldValue)) {
					$field->appendChild($xml->createTextNode($fieldValue));
				} else {
					$field->appendChild($xml->createTextNode($fieldValue));
				}
				$row->appendChild($field);
			}
			$rows->appendChild($row);
		}

		if ( strpos($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
			header("Content-type: application/xhtml+xml;charset=utf-8");
		} else {
			header("Content-type: text/xml;charset=utf-8"); 
		}

		unset($simpleRes);
		echo $xml->saveXML();
		exit(0);
	}

	private function _buildJson()
	{
		$simpleRes = $this->_simpleExec();
		$indexField = $this->_table->getPrimary();

		$responce = new stdClass();
		$responce->page = $this->getPage();
		$responce->total = $this->getLastPage();
		$responce->records = $this->getTotalRecordsCnt();
		foreach ($simpleRes->data as $record) {
			$id = $record[$indexField];
			$responce->rows[$id]['id']=$id;
			$responce->rows[$id]['cell']= array_values($record);
		}

		echo json_encode($responce);
		exit(0);
	}

	private function _setFilterOptions()
	{
//http://gelezka/admin/Fields/?app_ajaxMode=1&app_asXml=1&nd=1250508298853&_search=true&rows=90&page=1&sidx=&sord=asc&searchField=PRODUCTSFIELDS_ID&searchString=30&searchOper=eq
		if (empty($_REQUEST['searchField'])) {
			return false;
		}

		$searchField = $this->quote($_REQUEST['searchField']);
		$searchCond = $this->quote($_REQUEST['searchOper']);
		$searchValue = $this->quote($_REQUEST['searchString']);
		
		$qopers = array(
				'eq'=>" = ",
				'ne'=>" <> ",
				'lt'=>" < ",
				'le'=>" <= ",
				'gt'=>" > ",
				'ge'=>" >= ",
				'bw'=>" LIKE ",
				'bn'=>" NOT LIKE ",
				'in'=>" IN ",
				'ni'=>" NOT IN ",
				'ew'=>" LIKE ",
				'en'=>" NOT LIKE ",
				'cn'=>" LIKE " ,
				'nc'=>" NOT LIKE " 
		);

		$this->addWhere(
			$searchField 
			. (isset($qopers[$searchCond]) ? $qopers[$searchCond] : ' = ') 
			. $searchValue
		);

		return true;
	}

	/**
	 * prepear view for using in static html
	 * 
	 * @method _simpleExec 
	 * @access private
	 * @return array 
	 */
	private function _simpleExec()
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

		if (($orderField = $this->getOrderField())) {
			$this->_select->order(array($orderField . ' ' . $this->getOrderDirection()));
		}
		
		if (!empty($this->_where['and']) || !empty($this->_where['or'])) {
			foreach ($this->_where['and'] as $where) {
				$this->_select->where($where);
			}
			foreach ($this->_where['or'] as $where) {
				$this->_select->orWhere($where);
			}
		}

		$this->_select->limitPage($page, $rpp);
		//$result = $this->_select->query()->fetchAll();
		$this->_select->setIntegrityCheck(false);
		$result = $this->_table->fetchAll($this->_select)->toArray();

		if (!empty($result)) {
			$fields = (array)array_keys(current($result));
			$resultObject->fields = array_combine(array_map(array($this, '_FieldToAlias'), $fields), $fields);
		} else {
			$resultObject->fields = array();
		}
		$resultObject->fieldsCnt = count($resultObject->fields);
		$resultObject->data = $result;
		$resultObject->commonActions = $this->getCommonActions();

		$resultObject->prevPage = (($page > 1) ? $page - 1 : 1);
		$resultObject->page = $page;
		$resultObject->lastPage = $lastPage = (empty($result) ? 1 : $lastPage);
		$resultObject->nextPage = (($page < $lastPage) ? $page + 1 : $lastPage);

		if (!empty($resultObject->commonActions)) {
			$resultObject->fieldsCount = @count($resultObject->fields) + 1;
			//$resultObject->fields[] = 'Actions';
			$resultObject->actionField = $this->_table->getPrimary();
		} else {
			$resultObject->fieldsCount = @count($resultObject->fields);
		}

		$resultObject->singleActions = $this->getSingleActions();

		return $resultObject;
	}

	public function getWhere()
	{
		return $this->_where;
	}
	
	public function addWhere($where, $and = true)
	{
		if (empty($where)) {
			return false;
		}

		if ($and) {
			$this->_where['and'][] = $where;
		} else {
			$this->_where['or'][] = $where;
		}

		return true;
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

	public function getOrderField()
	{
		return $this->_orderField;
	}

	public function setOrderField($field)
	{
		$this->_orderField = $this->quote($field);
	}

	public function getOrderDirection()
	{
		return $this->_orderDirection;
	}

	public function setOrderDirection($direction = 'ASC')
	{
		$this->_orderDirection = $direction;
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
		if (is_numeric($recCnt)) {
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