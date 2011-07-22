<?php
abstract class KIT_Model_Tree_Abstract extends KIT_Model_Abstract
{
	protected $_parentId;
	protected $_left;
	protected $_right;
	protected $_level;

	public function save($data = null)
	{
		if ($this->getId()) {
			return parent::save($data);
		} else {
			return $this->insert();
		}
	}

	/**
	 * Add tree node fields to data for add to database
	 *
	 * @return boolean
	 */
	public function insert()
	{
		$table = $this->getDbTable();

		$parentIdField = $table->getFieldByAlias('parentId');
		$levelField = $table->getFieldByAlias('level');
		$leftField = $table->getFieldByAlias('left');
		$rightField = $table->getFieldByAlias('right');

		$parentId = (int)$this->getParentId();

		if (empty($parentId)) {
			//$this->setId($parentId);
			$maxRight = $table->getMaxRight();
			$left = $maxRight + 1;
			$right = $maxRight + 2;
			$level = 0;
			$parentId = 0;
		} else {
			$insertAfter = $table->getMaxRight($parentId, true);
			if (empty($insertAfter->$rightField)) {
				$parent = $table->find($parentId)->current();
				$beforeRight = $parent->$rightField;
				$left = $beforeRight;
				$right = $beforeRight + 1;
				$level = $parent->$levelField + 1;
				$table->updateBeforeChild($beforeRight);
			} else {
				$beforeRight = $insertAfter->$rightField;
				$left = $beforeRight + 1;
				$right = $beforeRight + 2;
				$level = $insertAfter->$levelField;
				$table->updateBeforeNeighbor($beforeRight);
			}
		}

		$this->setLeft($left);
		$this->setRight($right);
		$this->setLevel($level);
		$this->setParentId($parentId);

		return parent::save();
	}

	/**
	 * Update tree node with childs to another parent node
	 *
	 * @param int $parentId
	 * @return boolean
	 */
	public function moveTo($parentId = null)
	{
		if (!parent::save()) {
			return false;
		}

		$table = $this->getDbTable();

		$parentIdField = $table->getFieldByAlias('parentId');
		$levelField = $table->getFieldByAlias('level');
		$leftField = $table->getFieldByAlias('left');
		$rightField = $table->getFieldByAlias('right');

		$id = $this->getId();
		!empty($parentId) || $parentId = (int)$this->getParentId();
		$left = $this->getLeft();
		$right = $this->getRight();
		$level = $this->getLevel();

		$parent = $table->find($parentId)->current();
		if (
			empty($parent)
			|| $id == $parentId
			|| $left == $parent->$leftField
			|| ($parent->$leftField >= $left && $parent->$leftField <= $right)
		) {
			return false;
		}
		if (
			$parent->$leftField < $left
			&& $parent->$rightField > $right
			&& $parent->$levelField < ($level - 1)
		) {
			$table->updateBeforeMoveUp(
				array('left'  => $left, 'right' => $right, 'level' => $level),
				array(
					'left' => $parent->$leftField,
					'right' => $parent->$rightField,
					'level' => $parent->$levelField
				)
			);
		} else if ($parent->$leftField < $left) {
			$table->updateBeforeMoveDown(
				array('left'  => $left, 'right' => $right, 'level' => $level),
				array(
					'left' => $parent->$leftField,
					'right' => $parent->$rightField,
					'level' => $parent->$levelField
				)
			);
		} else {
			$table->updateBeforeMoveNextTree(
				array('left'  => $left, 'right' => $right, 'level' => $level),
				array(
					'left' => $parent->$leftField,
					'right' => $parent->$rightField,
					'level' => $parent->$levelField
				)
			);
		}

//Hotpoint-Ariston PH 631 MS
		return true;
	}

	public function getParentId()
	{
		return $this->_parentId;
	}

	public function setParentId($id)
	{
		$this->_parentId = $id;
	}

	public function getLeft()
	{
		return $this->_left;
	}

	public function setLeft($left)
	{
		$this->_left = $left;
	}

	public function getRight()
	{
		return $this->_right;
	}

	public function setRight($right)
	{
		$this->_right = $right;
	}

	public function getLevel()
	{
		return $this->_level;
	}

	public function setLevel($level)
	{
		$this->_level = $level;
	}

}