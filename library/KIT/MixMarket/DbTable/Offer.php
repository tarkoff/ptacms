<?php
/**
 * MixMarket Offers Database Table
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_MIxMArket
 * @copyright  Copyright (c) 2009-2011 KIT Studio
 * @license    New BSD License
 * @version    $Id: Product.php 402 2010-05-06 20:50:13Z TPavuk $
 */

class KIT_MixMarket_DbTable_Offer extends KIT_Db_Table_Abstract
{
	protected $_name = 'MIXMARKET_OFFERS';
	protected $_primary = 'OFFERS_ID';

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
	public function init()
	{
	}

	public function getProductOffers($productId)
	{
		$productId = (int)$productId;
		if (empty($productId)) {
			return false;
		}

		$select = $this->select()->from(
			array('offers' => $this->getTableName())
		);

		$select->join(
			array('mixoffers' => 'MIXMARKET_LINKOFFERS'),
			'offers.OFFERS_ID = mixoffers.LINKOFFERS_MIXID'
		);

		$select->join(
			array('mixbrands' => 'MIXMARKET_BRANDS'),
			'offers.OFFERS_BRANDID = mixbrands.BRANDS_ID'
		);

		$select->join(
			array('adv' => 'MIXMART_ADVERTIZERS'),
			'offers.OFFERS_ADVID = adv.ADVERTIZERS_ID',
			array('ADVERTIZERS_TITLE')
		);

		$select->joinLeft(
			array('advgt' => 'MIXMARKET_ADVREGIONGEOTARGET'),
			'advgt.ADVREGIONGEOTARGET_ADVID = adv.ADVERTIZERS_ID',
			array()
		);

		$select->joinLeft(
			array('rgt' => 'MIXMARKET_REGIONSGEOTAGRET'),
			'advgt.ADVREGIONGEOTARGET_RGTID=rgt.REGIONSGEOTAGRET_ID',
			array('rgt.REGIONSGEOTAGRET_TITLE')
		);

		if (($ip = KIT_Util::getRemoteIp())) {
			$ipNum = KIT_Util::ipToNum($ip);
			$geoId = $this->getAdapter()
						  ->fetchOne('SELECT geo.GEO_TARGETID FROM GEO_BLOCKS AS geo '
									 . 'WHERE geo.GEO_STARTNUM <=' . $ipNum
											. ' AND geo.GEO_ENDNUM >=' . $ipNum
									 . ' LIMIT 1'
									);
			if (empty($geoId)) {
				$select->where('rgt.REGIONSGEOTAGRET_ID is null');
			} else {
				$select->where('rgt.REGIONSGEOTAGRET_ID = ' . (int)$geoId);
			}
		}

		$select->where('mixoffers.LINKOFFERS_CATALOGID = ?', $productId);

		$select->setIntegrityCheck(false);
		return $this->fetchAll($select);
	}
}

