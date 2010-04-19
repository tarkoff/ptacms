<?php
/**
 * Tree Database Table
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id$
 */

abstract class KIT_Db_Table_Tree_Abstract extends KIT_Db_Table_Abstract
{

	/**
	 * Get maximum right position of tree node
	 *
	 * @param int $parentId
	 * @param boolean $allRecord
	 * @return int|Zend_Db_Table_Row_Abstract
	 */
	public function getMaxRight($parentId = null, $allRecord = false)
	{
		$rightField = $this->getFieldByAlias('right');
		$leftField = $this->getFieldByAlias('left');

		$select = $this->select()->from(
			$this->getTableName(),
			array('MAX_RIGHT' => 'MAX(' . $rightField . ')')
		);

		if (!empty($parentId) && ($parent = $this->find((int)$parentId))) {
			$parent = $parent->current();
			$select->where($leftField . '>' . (int)$parent->$leftField);
			$select->where($rightField . '<' . (int)$parent->$rightField);
		}
		
		$maxRight = (int)$this->getAdapter()->fetchOne($select);
		if ($allRecord) {
			return $this->fetchRow($rightField . '=' . $maxRight);
		} else {
			return $maxRight;
		}
	}

	/**
	 * Prepear place for new tree item in database
	 *
	 * @param int $rightId
	 * @return boolean
	 */
	public function updateBeforeChild($rightId)
	{
		if (empty($rightId)) {
			return false;
		}

		$rightField = $this->getFieldByAlias('right');
		$leftField = $this->getFieldByAlias('left');
		
		$sql = 'UPDATE '.$this->getTableName().' SET '
			. $leftField . '=IF(' . $leftField .'>' . $rightId.',' . $leftField . '+2,' . $leftField . '),'
			. $rightField . '=IF(' . $rightField . '>=' . $rightId . ',' . $rightField . '+2,' . $rightField . ')'
				 . ' WHERE ' . $rightField . '>=' . $rightId;
		return $this->getAdapter()->query($sql);
	}

	/**
	 * Prepear place for new leaf item in database
	 *
	 * @param int $rightId
	 * @return boolean
	 */
	public function updateBeforeNeighbor($rightId)
	{
		if (empty($rightId)) {
			return false;
		}

		$rightField = $this->getFieldByAlias('right');
		$leftField = $this->getFieldByAlias('left');
		
		$sql = 'UPDATE '.$this->getTableName().' SET '
			. $leftField . '=IF(' . $leftField .'>' . $rightId.',' . $leftField . '+2,' . $leftField . '),'
			. $rightField . '=IF(' . $rightField . '>' . $rightId . ',' . $rightField . '+2,' . $rightField . ')'
				 . ' WHERE ' . $rightField . '>' . $rightId;
		return $this->getAdapter()->query($sql);
	}

	public function updateBeforeMoveUp($node, $newParentNode)
	{
		$rightField = $this->getFieldByAlias('right');
		$leftField = $this->getFieldByAlias('left');
		$levelField = $this->getFieldByAlias('level');

		$sql = 'UPDATE ' . $this->getTableName() . ' SET '
			. $levelField . '=IF(' . $leftField . ' BETWEEN ' . $node['left'] . ' AND ' . $node['right'] . ', '
			. $levelField . sprintf('%+d', -($node['level'] - 1) + $newParentNode['level']) . ', ' . $levelField . '), '
			. $rightField . '=IF(' . $rightField . ' BETWEEN ' . ($node['right'] + 1) . ' AND ' . ($newParentNode['right'] - 1) . ', '
			. $rightField . '-' . ($node['right'] - $node['left'] + 1) . ', '
			. 'IF(' . $leftField . ' BETWEEN ' . $node['left'] . ' AND ' . $node['right'] . ', '
				. $rightField . '+'
			. ((($newParentNode['right'] - $node['right'] - $node['level'] + $newParentNode['level']) / 2) * 2 + $node['level'] - $newParentNode['level'] - 1) . ', ' . $rightField.')), '
			. $leftField . '=IF(' . $leftField . ' BETWEEN ' . ($node['right'] + 1) . ' AND ' . ($newParentNode['right']-1) . ', '
			. $leftField . '-' . ($node['right'] - $node['left'] + 1) . ', '
			. 'IF(' . $leftField . ' BETWEEN ' . $node['left'] . ' AND ' . $node['right'] . ', '
			. $leftField . '+' . ((($newParentNode['right'] - $node['right'] - $node['level'] + $newParentNode['level']) / 2) * 2 + $node['level'] - $newParentNode['level'] - 1) . ', ' . $leftField . ')) '
			. 'WHERE ' . $leftField . ' BETWEEN ' . ($newParentNode['left'] + 1) . ' AND ' . ($newParentNode['right'] - 1);
		return $this->getAdapter()->query($sql);
	}

	public function updateBeforeMoveDown($node, $newParentNode)
	{
		$rightField = $this->getFieldByAlias('right');
		$leftField = $this->getFieldByAlias('left');
		$levelField = $this->getFieldByAlias('level');

		$sql = 'UPDATE '.$this->getTableName().' SET '
            . $levelField . '=IF(' . $leftField . ' BETWEEN ' . $node['left']
            . ' AND ' . $node['right'] . ', ' . $levelField . sprintf('%+d', -($level - 1) + $levelP) . ', ' . $levelField .'), '
            . $leftField . '=IF(' . $leftField . ' BETWEEN ' . $newParentNode['right']
            . ' AND ' . ($node['left'] - 1) . ', ' . $leftField . '+' . ($node['right'] - $node['left'] +1) . ', '
               . 'IF(' . $leftField . ' BETWEEN ' . $node['left']
               		. ' AND ' . $node['right'] . ', ' . $leftField . '-'
               		. ($node['left'] - $newParentNode['right']) . ', '.$leftField . ') '
            . '), '
            . $rightField . '=IF(' . $rightField . ' BETWEEN ' . $newParentNode['right']
            . ' AND ' . $node['left'] . ', ' . $rightField . '+' . ($node['right'] - $node['left'] + 1) . ', '
               . 'IF(' . $rightField . ' BETWEEN ' . $node['left']
               		. ' AND ' . $node['right'] . ', ' . $rightField . '-'
               		. ($node['left'] - $newParentNode['right']) . ', ' . $rightField . ') '
            . ') ' . 'WHERE ' . $leftField . ' BETWEEN ' . $newParentNode['left'] . ' AND ' . $node['right']
            . ' OR ' . $rightField . ' BETWEEN ' . $newParentNode['left'] . ' AND ' . $node['right'];
        return $this->getAdapter()->query($sql);
	}

	public function updateBeforeMoveNextTree($node, $newParentNode)
	{
		$rightField = $this->getFieldByAlias('right');
		$leftField = $this->getFieldByAlias('left');
		$levelField = $this->getFieldByAlias('level');

		$sql = 'UPDATE '.$this->getTableName().' SET '
            . $levelField . '=IF(' . $leftField . ' BETWEEN ' . $node['left']
            	. ' AND ' . $node['right'] . ', ' . $levelField . sprintf('%+d', -($level - 1) + $levelP) . ', ' . $levelField
            . '), '
            . $leftField . '=IF(' . $leftField . ' BETWEEN ' . $node['right']
             . ' AND ' . $newParentNode['right'] . ', ' . $leftField . '-' . ($node['right'] - $node['left'] + 1) . ', '
               . 'IF(' . $leftField . ' BETWEEN ' . $node['left']
               		. ' AND ' . $node['right'] . ', ' . $leftField
               		. '+' . ($newParentNode['right'] - 1 - $node['right']) . ', ' . $leftField . ')'
               . '), '
            . $rightField . '=IF(' . $rightField . ' BETWEEN ' . ($node['right'] + 1)
            	. ' AND ' . ($newParentNode['right'] - 1) . ', ' . $rightField . '-' . ($node['right'] - $node['left']+1) . ', '
               . 'IF(' . $rightField . ' BETWEEN ' . $node['left'] . ' AND ' . $node['right'] . ', ' . $rightField
               		. '+' . ($newParentNode['right'] - 1 - $node['right']) . ', ' . $rightField . ') '
            . ') '
            . 'WHERE ' . $leftField . ' BETWEEN ' . $node['left'] . ' AND ' . $newParentNode['right']
            . ' OR ' . $rightField . ' BETWEEN ' . $node['left'] . ' AND ' . $newParentNode['right'];
        return $this->getAdapter()->query($sql);
	}

	/**
	 * Remove record from database by primary key
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function removeById($id)
	{
		if (empty($id)) {
			return false;
		}

		$forDelete = $this->find($id)->current();
		$where = $this->getAdapter()->quoteInto($this->getPrimary() . ' = ?', intval($id));
		if (!empty($forDelete) && parent::delete($where)) {
			$rightField = $this->getFieldByAlias('right');
			$leftField = $this->getFieldByAlias('left');
			$levelField = $this->getFieldByAlias('level');

			$leftId = $forDelete->$leftField;
			$rightId = $forDelete->$rightField;

			$sql = 'UPDATE ' . $this->getTableName() . ' SET '
				. $leftField.'=IF(' . $leftField . ' BETWEEN ' . $leftId
				. ' AND '.$rightId . ',' . $leftField . '-1,' . $leftField . '),'
				. $rightField . '=IF(' . $rightField . ' BETWEEN ' . $leftId . ' AND '
				.$rightId.','.$rightField.'-1,'.$rightField.'),'
				. $levelField . '=IF(' . $leftField . ' BETWEEN ' . $leftId
				. ' AND ' . $rightId . ',' . $levelField . '-1,' . $levelField . '),'
				. $leftField . '=IF(' . $leftField . '>' . $rightId . ',' . $leftField . '-2,' . $leftField . '),'
				. $rightField . '=IF(' . $rightField . '>' . $rightId . ',' . $rightField . '-2,' . $rightField . ') '
					. 'WHERE ' . $rightField . '>' . $leftId;
			$this->getAdapter()->query($sql);
			return true;
		}

		return false;
	}

	public function getSubTreeWith($menuId)
	{
		$menuId = (int)$menuId;
		if (empty($menuId) || !($menuItem = $this->find($menuId)->current())) {
			return array();
		}

		$tableName = $this->getTableName();
		$rightField = $this->getFieldByAlias('right');
		$leftField = $this->getFieldByAlias('left');
		$levelField = $this->getFieldByAlias('level');

		$select = $this->select()
					   ->from($tableName, $this->getFields(false))
					   ->order(array($leftField))
					   ->setIntegrityCheck(false);

		$select->where($leftField . '<' . $menuItem->$leftField);
		$select->where($rightField . '>' . $menuItem->$rightField);
		
		return $this->fetchAll($select);
	}

	/**
	 * Get select field options for form
	 *
	 * @param string $idField
	 * @param string $valueField
	 * @return mixed
	 */
	public function getParentSelectOptions($idField, $valueField)
	{
		static $options;

		if (empty($idField) || empty($valueField)) {
			return false;
		}

		$cacheAlias = $idField . '_' . $valueField;
		if (!empty($options[$cacheAlias])) {
			return $options[$cacheAlias];
		}

		$options[$cacheAlias] = array(0 => 'None');
		foreach ($this->fetchAll() as $menu) {
			$options[$cacheAlias][$menu->$idField] = $menu->$valueField;
		}

		return $options[$cacheAlias];
	}
}
