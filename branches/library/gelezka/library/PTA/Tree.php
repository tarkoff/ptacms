<?php
/**
 * Class for Tree Building
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: User.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_Tree
{
	private $_inData = array();
	private $_tree = array();
	private $_keyField;
	private $_parentKeyField;
	
	private $_prefix;
	
	protected static $_trees = array();
	
	public function __construct($prefix, $data = null)
	{
		if (empty($prefix)) {
			throw new PTA_Exception('Tree prefix does not setted!');
		}
		
		if (isset(self::$_trees[$prefix])) {
			trigger_error('Tree with prefix "' . $prefix . '" allready exist.');
			return false;
		} else {
			self::$_trees[$prefix] = $this;
		}
		
		if (!empty($data)) {
			$this->setData($data);
		}
	}

	/**
	 * Set Input data For Tree Building
	 *
	 * @param array $data
	 */
	public function setData($data)
	{
		if (!empty($data)) {
			$this->_inData = (array)$data;
		}
	}
	
	private function _cmpNodes($a, $b)
	{
		if ($a[$this->_parentKeyField] == $b[$this->_parentKeyField]) {
			return 0;
		}
		return ($a[$this->_parentKeyField] < $b[$this->_parentKeyField]) ? -1 : 1;
	}

	/**
	 * Set Key Field For Tree Building
	 *
	 * @param string $field
	 */
	public function setKeyField($field)
	{
		$this->_keyField = $field;
	}
	
	/**
	 * Set Parent Key Field For Tree Building
	 *
	 * @param string $parentField
	 */
	public function setParentKeyField($parentField)
	{
		$this->_parentKeyField = $parentField;
	}

	/**
	 * Get builded tree
	 *
	 * @return array
	 */
	public function getTree()
	{
		return $this->_tree;
	}
	
	/**
	 * Build New Tree
	 *
	 * @param int $rootNodeId
	 */
	public function buildTree($rootNodeId = 0)
	{
		uasort($this->_inData, array($this, '_cmpNodes'));
		$inData = $this->_inData;
		$idField = $this->_keyField;
		$parentId = $this->_parentKeyField;
//var_dump($inData);

		$this->_tree = array();
		foreach ($inData as $id => $treeNode) {
			if ($rootNodeId == $treeNode[$parentId]) {
				$treeNode['childs'] = $this->_buildChilds($treeNode[$idField]);
				$this->_tree[$treeNode[$idField]] = $treeNode;
				unset($this->_inData[$id]);
			}
		}
	}

	/**
	 * Get All childs of tree node by node id
	 *
	 * @param int $parentNodeId
	 * @return array
	 */
	private function _buildChilds($parentNodeId = 0)
	{
		$parentId = $this->_parentKeyField;
		$idField = $this->_keyField;

		$childs = array();
		$inData = $this->_inData;
		foreach ($inData as $id => $treeNode) {
			if ($treeNode[$parentId] == $parentNodeId) {
				unset($this->_inData[$id]);
				$treeNode['childs'] = $this->_buildChilds($treeNode[$idField]);
				$childs[$treeNode[$idField]] = $treeNode;
			}
		}

		return $childs;
	}
	
	/**
	 * Get Tree Branche By Root Noede Id
	 *
	 * @param int $rootNodeId
	 * @param array $tree
	 * @return array
	 */
	public function getBrancheFrom($rootNodeId = 0, $tree = null)
	{
		if (empty($tree)) {
			$tree = $this->_tree;
		}
		
		if (empty($rootNodeId)) {
			return array('childs' => $tree);
		}

		foreach ($tree as $nodeId => $treeNode) {
			if ($nodeId == $rootNodeId) {
				return $treeNode;
			} else {
				if (!empty($treeNode['childs'])) {
					$this->getBrancheFrom($rootNodeId, $treeNode['childs']);
				}
			}
		}
		
		return null;
	}
	
	public function getBrancheTo($rootNodeId, $tree = null)
	{
		foreach ($this->_tree as $nodeId => $treeNode) {
			if (isset($treeNode['childs'][$rootNodeId])) {
				$treeNode['childs'] = $treeNode['childs'][$rootNodeId];
				$this->getBrancheTo($nodeId, array($nodeId => $treeNode));
			} elseif (!empty($treeNode['childs'])) {
				$this->getBrancheTo($rootNodeId, $tree);
			}
		}
		return $tree;
	}
	
	/**
	 * Get One level of childs
	 *
	 * @param int $rootNodeId
	 * @return array
	 */
	public function getLeaves($rootNodeId = 0)
	{
		$leaves = $this->getBrancheFrom($rootNodeId);
		foreach ($leaves['childs'] as &$leave) {
			$leave['childs'] = array();
		}
		return $leaves['childs'];
	}
	
	/**
	 * Get Tree By Prefix
	 *
	 * @param string $treePrefix
	 * @return PTA_Tree
	 */
	public static function get($treePrefix)
	{
		if (!isset(self::$_trees[$treePrefix])) {
			self::$_trees[$treePrefix] = new self($treePrefix);
		}
		
		return self::$_trees[$treePrefix];
	}
	
	
}
