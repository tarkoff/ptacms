<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 P.T.A. Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: loginForm.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class Catalog_searchForm extends PTA_Control_Form
{
	public function __construct($prefix)
	{
		parent::__construct($prefix);
	}

	public function initForm()
	{
		$this->setAction($this->getApp()->getBaseUrl() . '/Catalog/Search');
		
		$title = new PTA_Control_Form_Text('searchRequest', 'Search', true, '');
		$title->setSortOrder(1);
		$this->addVisual($title);

		$submit = new PTA_Control_Form_Submit('submit', 'Search', true, 'Search');
		$submit->setSortOrder(2);
		$this->addVisual($submit);
		}

	public function onLoad()
	{
		$data = new stdClass();
		$data->password = '';
		return $data;
	}

	public function onSubmit(&$data)
	{
		if (!empty($data->searchRequest)) {
			if (($catalog = $this->getApp()->getModule('Catalog'))) {
				$catalog->setSearchData($data->searchRequest);
			}
			return true;
		}

		return false;
	}
}
