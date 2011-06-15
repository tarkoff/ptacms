<?php
/**
 * User Site Header Controller
 *
 * @package PTA_Core
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/
class Prices extends PTA_WebModule
{
	private $_price;

	function __construct ($prefix)
	{
		parent::__construct($prefix, 'Prices.tpl');
		$this->setModuleUrl(PTA_BASE_URL . '/Prices/View');
		$this->_price = PTA_DB_Object::get('Catalog_Price');
	}

	public function init()
	{
		parent::init();

		if ($this->isActive()) {
			$productId = $this->getHttpProduct();
			$action = $this->getApp()->getAction();
			switch (ucfirst($action)) {
				case 'Add': 
					$this->editAction($productId);
				break;

				case 'List':
					$this->viewAction();
				break;

				case 'Edit':
					$this->editAction($productId);
				break;

				default:
					$this->listAction();
			}
		}
	}

	public function viewAction()
	{
		$productId = $this->getHttpProduct();

		$this->setVar('view', $this->getPrices($productId));
	}

	public function editAction($productId = null)
	{
		$this->setVar('tplMode', 'edit');

		if (empty($productId)) {
			return false;
		}

		$guest = PTA_DB_Object::get('User_Guest');

		$this->_price->setUserId($guest->getId());
		$this->_price->setProductId($productId);

//		var_dump($this->_price);

		$this->addVisual(new Prices_EditForm('EditForm', $this->_price));
	}

	public static function getPrices($productId)
	{
		if (empty($productId)) {
			return array();
		}

		return PTA_DB_Table::get('Catalog_Price')->getPrices($productId);
	}

	public function getHttpProduct()
	{
		return $this->getApp()->getHttpVar('Product');
	}

}
