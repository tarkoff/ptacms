<?php
/**
 * MixMarket Offer Table
 *
 * @package PTA_MixMarket
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 129 2009-07-29 18:59:13Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_MixMarket_Offer_Table extends PTA_DB_Table
{
	/**
	 * The default table name
	 */
	protected $_name = 'MIXMARKET_OFFERS';
	protected $_primary = 'OFFERS_ID';
	
	public function getOffers($productId)
	{
		if (empty($productId)) {
			return array();
		}

		$advTable = self::get('MixMarket_Advertizer');

		$select = $this->select()->from(
			array('ofs' => $this->getTableName()),
			array_values($this->getFields())
		);

		$select->setIntegrityCheck(false);

		$select->join(
			array('lofs' => 'MIXMARKET_LINKOFFERS'),
			'ofs.' . $this->getPrimary() . ' = lofs.LINKOFFERS_MIXID',
			array()
		);

		$select->join(
			array('adv' => $advTable->getTableName()),
			'ofs.' . $this->getFieldByAlias('advId') . ' = adv.' . $advTable->getPrimary(),
			array($advTable->getFieldByAlias('title'))
		);

		$select->joinLeft(
			array('advgt' => 'MIXMARKET_ADVREGIONGEOTARGET'),
			'advgt.ADVREGIONGEOTARGET_ADVID = adv.' . $advTable->getPrimary(),
			array()
		);

		$select->joinLeft(
			array('rgt' => 'MIXMARKET_REGIONSGEOTAGRET'),
			'advgt.ADVREGIONGEOTARGET_RGTID=rgt.REGIONSGEOTAGRET_ID',
			array('rgt.REGIONSGEOTAGRET_TITLE')
		);

		$select->where('lofs.LINKOFFERS_CATALOGID = ?', intval($productId));
		$select->order(array('ofs.OFFERS_PRICE', 'rgt.REGIONSGEOTAGRET_TITLE'));

		return $this->fetchAll($select)->toArray();
	}

}