<?php
/**
 * Product Field Edit Form
 *
 * @package PTA_Catalog
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: editForm.php 208 2009-10-23 14:55:19Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Common_FilterForm extends PTA_Control_Form
{
	public function initForm()
	{
		$submit = new PTA_Control_Form_Submit('submit', 'Apply Filter', true, 'Apply Filter');
		$submit->setSortOrder(1000);
		$this->addVisual($submit);
		
		$this->setVar(
			'conditions',
			array(
				'eq' => 'equal',
				'ne' => 'not equal',
				'lt' => 'less',
				'gt' => 'greater',
				'bw' => 'begins with',
				'ew' => 'ends with',
				'cn'=> 'contains'
			)
		);
		
		if (!$this->getVar('filterData')) {
			$this->setVar('filterData', array());
		}
	}

	public function onSubmit(&$data)
	{
		$data = (array)$data;
		unset($data[$this->getPrefix()]);

		$filterData = array();
		foreach ($data as $filterAlias => $filterValue) {
			@list($fieldAlias, $filter) = explode('_', $filterAlias);
			if (!empty($fieldAlias) && !empty($filter)) {
				$filterData[$fieldAlias][$filter] = $this->quote($filterValue);
			}
		}
//var_dump($data, $filterData);
		$this->setVar('filterData', $filterData);
		$this->getApp()->getActiveModule()->setFilterData($filterData);
		return true;
	}
}
