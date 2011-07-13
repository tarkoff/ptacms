<?php
/**
 * Class for parsing incomming MixMarket xml file and store it to database
 *
 * @package    Tools
 * @copyright  Copyright (c) 2009 Taras Pavuk (tpavuk@gmail.com)
 * @license    BSD License
 */

require_once 'Mix/Abstract.php';

class Mix_Parser extends Mix_Abstract
{
	/**
	 * Xml file name  for parsing
	 *
	 * @var string
	 */
	protected $_xmlFile;

	/**
	 * Xml parser object
	 *
	 * @var unknown_type
	 */
	protected $_parser;

	protected $_section;
	protected $_currentTag;
	protected $_currentTagAttrs;
	protected $_currentText;

	protected $_shopId = 0;

	const SECTION_INIT = 0;
	const SECTION_RUN = 1;
	const SECTION_FLUSH = 2;

	public function __construct($file)
	{
		$this->alert('Parsing started');

		$this->_parser = xml_parser_create('UTF-8');
		xml_set_object($this->_parser, $this);

		xml_parser_set_option($this->_parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
		xml_parser_set_option($this->_parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($this->_parser, XML_OPTION_SKIP_WHITE, 1);

		xml_set_element_handler($this->_parser, 'startElement', 'endElement');
		xml_set_character_data_handler($this->_parser, 'textData');

		if (file_exists($file)) {
			$this->_xmlFile = $file;
		} else {
			trigger_error('Xml file ' . $file . ' not found', E_USER_ERROR);
		}
	}

	public function __destruct()
	{
		xml_parser_free( $this->_parser );
		$this->alert('Parsing finished');
	}

	/**
	 * Parse MixMarket xml file
	 *
	 * @return boolean
	 */
	public function parse()
	{
		if (!file_exists($this->_xmlFile)) {
			trigger_error('Xml file ' . $file . ' not found', E_USER_ERROR);
		}

		$fp = fopen($this->_xmlFile, 'r' );

		$data = null;
		while( !feof($fp) ) {
			/*$data = iconv(
				'Windows-1251', 'UTF-8',
				str_replace('&', '__-xxx-__', fgets($fp))
			);*/
			$data = str_replace('&', '__-xxx-__', fgets($fp));
			if (!xml_parse($this->_parser, $data, feof($fp))) {
				trigger_error(
					sprintf(
						'XML Error: %s in line %d',
						xml_error_string(xml_get_error_code($this->_parser)),
						xml_get_current_line_number($this->_parser)
					),
					E_USER_ERROR
				);
			}
		}
		fclose($fp);

		return true;
	}

	/**
	 * Parsing xml element open tag
	 *
	 * @param object $parser
	 * @param string $name
	 * @param array $attrs
	 * @return void
	 */
	protected function startElement($parser, $name, $attrs)
	{
		$this->_currentTag = $name;
		$this->_currentTagAttrs = $attrs;

		switch ($name) {
			case 'adv':
				$this->_section = 'adv';
				$this->_parseAdvertizers($attrs, self::SECTION_INIT);
			break;

			case 'name':
				if ('adv' == $this->_section) {
					$this->_parseAdvertizers($attrs, self::SECTION_INIT);
				}
			break;

			case 'url':
				if ('adv' == $this->_section) {
					$this->_parseAdvertizers($attrs, self::SECTION_INIT);
				} else if ('offer' == $this->_section) {
					$this->_parseOffers($attrs, self::SECTION_INIT);
				}
			break;

			case 'regions_geotarget':
				if ('adv' == $this->_section) {
					$this->_parseAdvertizers($attrs, self::SECTION_INIT);
				}
			break;

			case 'geotarget':
				if ('adv' == $this->_section) {
					$this->_parseAdvertizers($attrs, self::SECTION_INIT);
				}
			break;

			case 'shop':
				$this->_parseShop($attrs, self::SECTION_INIT);
			break;

			case 'currencies':
				$this->_section = 'currencies';
				$this->_parseCurrencies($attrs, self::SECTION_INIT);
			break;

			case 'currency':
				if ('currencies' == $this->_section) {
					$this->_parseCurrencies($attrs, self::SECTION_INIT);
				}
			break;

			case 'regions_delivery':
				$this->_section = 'regions_delivery';
				$this->_parseRegionsDelivery($attrs, self::SECTION_INIT);
			break;

			case 'rd':
				if ('regions_delivery' == $this->_section) {
					$this->_parseRegionsDelivery($attrs, self::SECTION_INIT);
				}
			break;

			case 'categories':
				$this->_section = 'categories';
				$this->_parseCategories($attrs, self::SECTION_INIT);
			break;

			case 'category':
				if ('categories' == $this->_section) {
					$this->_parseCategories($attrs, self::SECTION_INIT);
				}
			break;

			case 'offer':
				$this->_section = 'offer';
				$this->_parseOffers($attrs, self::SECTION_INIT);
			break;

			case 'vendor':
				if ('offer' == $this->_section) {
					$this->_parseOffers($attrs, self::SECTION_INIT);
				}
			break;

			case 'picture':
				if ('offer' == $this->_section) {
					$this->_parseOffers($attrs, self::SECTION_INIT);
				}
		}
	}

	/**
	 * Parsing xml element close tag
	 *
	 * @param object $parser
	 * @param string $name
	 * @return void
	 */
	protected function endElement($parser, $name)
	{
		$this->_currentTag = $name;
		//$this->_currentText = trim($this->_currentText);

		switch ($name) {
			case 'adv':
				$this->_parseAdvertizers(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'name':
				if ('adv' == $this->_section) {
					$this->_parseAdvertizers(array(), self::SECTION_RUN);
				}
			break;

			case 'currencies':
				$this->_parseCurrencies(array(), self::SECTION_FLUSH);
			break;

			case 'url':
				if ('adv' == $this->_section) {
					$this->_parseAdvertizers(array(), self::SECTION_RUN);
				} else if ('offer' == $this->_section) {
					$this->_parseOffers(array(), self::SECTION_RUN);
				}
			break;

			case 'regions_delivery':
				$this->_section = null;
				$this->_parseRegionsDelivery(array(), self::SECTION_FLUSH);
			break;

			case 'rd':
				if ('regions_delivery' == $this->_section) {
					$this->_parseRegionsDelivery(array(), self::SECTION_RUN);
				}
			break;

			case 'geotarget':
				if ('adv' == $this->_section) {
					$this->_parseAdvertizers(array(), self::SECTION_RUN);
				}
			break;

			case 'offer':
				$this->_parseOffers(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'vendor':
				if ('offer' == $this->_section) {
					$this->_parseOffers(array(), self::SECTION_RUN);
				}
			break;


			case 'price':
				if ('offer' == $this->_section) {
					$this->_parseOffers(array(), self::SECTION_RUN);
				}
			break;

			case 'currencyId':
				if ('offer' == $this->_section) {
					$this->_parseOffers(array(), self::SECTION_RUN);
				}
			break;

			case 'categoryId':
				if ('offer' == $this->_section) {
					$this->_parseOffers(array(), self::SECTION_RUN);
				}
			break;

			case 'categories':
				$this->_parseCategories(array(), self::SECTION_FLUSH);
				$this->_section = null;
			break;

			case 'category':
				if ('categories' == $this->_section) {
					$this->_parseCategories(array(), self::SECTION_RUN);
				}
			break;

			case 'model':
				if ('offer' == $this->_section) {
					$this->_parseOffers(array(), self::SECTION_RUN);
				}
			break;

			case 'description':
				if ('offer' == $this->_section) {
					$this->_parseOffers(array(), self::SECTION_RUN);
				}
			break;

			case 'picture':
				if ('offer' == $this->_section) {
					$this->_parseOffers(array(), self::SECTION_RUN);
				}
			break;
		}

		$this->_currentText = null;
	}

	/**
	 * Parsing xml element text content
	 *
	 * @param object $parser
	 * @param string $text
	 * @return void
	 */
	protected function textData($parser, $text)
	{
		if (strlen($this->_currentText) < 500) {
			$this->_currentText .= str_replace(array('__-xxx-__amp;', '__-xxx-__'), '&', $text);
		}
	}

	protected function _parseShop($attrs = array(), $mode = self::SECTION_INIT)
	{
		$this->_shopId = (int)$attrs['id'];
	}

	protected function _parseCategories($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $categories = array(), $categoryId;

		$table = $this->_config->tables->categories;

		switch ($this->_currentTag) {
			case 'categories':
				if (self::SECTION_INIT == $mode) {

				} else if (self::SECTION_FLUSH == $mode) {
					foreach ($categories as $category) {
						$this->_insertOrUpdate($table->name, $category);
					}
					$categories = array();
				}
			break;
			case 'category':
				if (self::SECTION_INIT == $mode) {
					$categoryId = (int)$attrs['id'];
					$categories[$categoryId]['CATEGORIES_ID'] = $categoryId;
					$categories[$categoryId]['CATEGORIES_PID'] = (int)$attrs['parentId'];
				} else if (self::SECTION_RUN == $mode) {
					$categories[$categoryId]['CATEGORIES_TITLE'] = trim($this->_currentText);
				}
			break;
		}
	}

	protected function _parseCurrencies($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $currencies = array();

		$table = $this->_config->tables->currencies;

		switch ($this->_currentTag) {
			case 'currencies':
				if (self::SECTION_INIT == $mode) {
					$currencies = array();
				} else if (self::SECTION_FLUSH == $mode) {
					foreach ($currencies as $currency) {
						$this->_insertOrUpdate($table->name, $currency);
					}
				}
			break;
			case 'currency':
					$currencies[] = array(
						'CURRENCIES_ID' => $attrs['id'],
						'CURRENCIES_RATE' => $attrs['rate']
					);
			break;
		}
	}

	protected function _parseOffers($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $values = array(), $geotargets = array();
		$table = $this->_config->tables->offers;

		switch ($this->_currentTag) {
			case 'offer':
				if (self::SECTION_INIT == $mode) {
					$values = array();
					$values['OFFERS_ID'] = (int)$attrs['id'];
					$values['OFFERS_ADVID'] = $this->_shopId;
				} else if (self::SECTION_FLUSH == $mode) {
					$this->_insertOrUpdate($table->name, $values);
				}
			break;

			case 'price':
				$values['OFFERS_PRICE'] = (float)trim($this->_currentText);
			break;

			case 'url':
				$values['OFFERS_URL'] = trim($this->_currentText);
			break;

			case 'currencyId':
				$values['OFFERS_CURRENCYID'] = trim($this->_currentText);
			break;

			case 'vendor':
				if (self::SECTION_INIT == $mode) {
					$values['OFFERS_BRANDID'] = (int)$attrs['id'];
				} else if (self::SECTION_RUN == $mode) {
					$this->_insertOrUpdate(
						$this->_config->tables->brands->name,
						array(
							'BRANDS_ID' => $values['OFFERS_BRANDID'],
							'BRANDS_TITLE' => trim($this->_currentText)
						)
					);
				}
			break;

			case 'categoryId':
				$values['OFFERS_CATID'] = (int)trim($this->_currentText);
			break;

			case 'picture':
				if (self::SECTION_INIT == $mode) {
					$values['OFFERS_IMGW'] = (int)$attrs['w'];
					$values['OFFERS_IMGH'] = (int)$attrs['h'];
				} else if (self::SECTION_RUN == $mode) {
					$values['OFFERS_IMG'] = trim($this->_currentText);
				}
			break;

			case 'model':
				$values['OFFERS_NAME'] = trim($this->_currentText);
			break;

			case 'description':
				$values['OFFERS_DESC'] = trim($this->_currentText);
			break;
		}
	}

	protected function _parseAdvertizers($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $values = array(), $geotargets = array(), $geoId = 0;
		$table = $this->_config->tables->advertizers;
		$geoTargetTable = $this->_config->tables->regions_geotarget;
		$advGeoTargetTable = $this->_config->tables->adv_region_geotarget;

		switch ($this->_currentTag) {
			case 'adv':
				if (self::SECTION_INIT == $mode) {
					$values = array();
					$values['ADVERTIZERS_ID'] = (int)$attrs['id'];
					$values['ADVERTIZERS_PRICEMARKUP'] = (int)$attrs['price_markup'];
					$values['ADVERTIZERS_UPDATED'] = 0;
					$geotargets = array();
				} else if (self::SECTION_FLUSH == $mode) {
					$this->_insertOrUpdate($table->name, $values);
					foreach ($geotargets as $geo) {
						$this->_insertOrUpdate($geoTargetTable->name, $geo['geotarget']);
						$this->_insertOrUpdate($advGeoTargetTable->name, $geo['adv_geotarget']);
					}
				}
			break;

			case 'name':
				$values['ADVERTIZERS_TITLE'] = trim($this->_currentText);
			break;

			case 'url':
				$values['ADVERTIZERS_URL'] = trim($this->_currentText);
			break;

			case 'regions_geotarget':
				$geotarget = array();
			break;

			case 'geotarget':
				if (self::SECTION_INIT == $mode) {
					$geoId = (int)$attrs['id'];
					$geotargets[$geoId] = array(
						'adv_geotarget' => array(
							'ADVREGIONGEOTARGET_ADVID' => $values['ADVERTIZERS_ID'],
							'ADVREGIONGEOTARGET_RGTID' => $geoId,
							'ADVREGIONGEOTARGET_PRICEMARKUP' => $attrs['price_markup']
						),
						'geotarget' => array(
							'REGIONSGEOTAGRET_ID' => $geoId,
							'REGIONSGEOTAGRET_TITLE' => trim($this->_currentText)
						)
					);
				} else if (self::SECTION_RUN == $mode) {
					$geotargets[$geoId]['geotarget']['REGIONSGEOTAGRET_TITLE'] = trim($this->_currentText);
					$geoId = null;
				}
			break;
		}
	}

	protected function _parseRegionsDelivery($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $deliveries = array(), $deliveryId;
		$table = $this->_config->tables->advertizers;
		$deliveryTable = $this->_config->tables->regions_delivery;
		$advDeliveryTable = $this->_config->tables->adv_region_delivery;

		switch ($this->_currentTag) {
			case 'regions_delivery':
				if (self::SECTION_INIT == $mode) {
					$deliveries = array();
				} else if (self::SECTION_FLUSH == $mode) {
					foreach ($deliveries as $geo) {
						$this->_insertOrUpdate($deliveryTable->name, $geo['delivery_geo']);
						$this->_insertOrUpdate($advDeliveryTable->name, $geo['delivery_adv']);
					}
				}
			break;

			case 'rd':
				if (self::SECTION_INIT == $mode) {
					$deliveryId = (int)$attrs['id'];
					$deliveries[$deliveryId] = array(
						'delivery_adv' => array(
							'ADVREGIONDELIVERY_ADVID' => $this->_shopId,
							'ADVREGIONDELIVERY_RDID'  => $deliveryId
						),
						'delivery_geo' => array(
							'REGIONSDELIVERY_ID' => $deliveryId
						)
					);
				} else if (self::SECTION_RUN == $mode) {
					$deliveries[$deliveryId]['delivery_geo']['REGIONSDELIVERY_TITLE'] = trim($this->_currentText);
				}
			break;
		}
	}


	/**
	 * Save parsed data to database
	 *
	 * @param array $data
	 * @param string $table
	 * @param array $sqlFields
	 * @return boolean
	 */
	protected function _insert($data, $table, $sqlFields)
	{
		$sql = 'insert ignore into ' . $table . ' (' . implode(', ', $sqlFields) . ') values ';
		$valSql = array();
		static $inserted = array();

		foreach ($data as $values) {
			$valSql[] = $this->_db->quoteInto(' (?)', $values);
		}

		if (!empty($valSql)) {
			$this->_db->beginTransaction();
			$this->_db->query($sql . implode(',', $valSql));
			$inserted[$table] += count($valSql);
			$this->alert($table . ' ' . $inserted[$table] . ' records inserted');
			return $this->_db->commit();
		}

		return false;
	}

	protected function _insertOrUpdate($table, $data)
	{
		if (empty($table) || empty($data)) {
			return false;
		}

		$sql = 'insert ignore into ' . $table
			   . ' (' . implode(', ', array_keys($data)) . ') values '
			   . $this->_db->quoteInto(' (?)', $data)
			   . ' on duplicate key update ';

		$updateValues = array();
		foreach ($data as $fieldName => $fieldValue) {
			if (!empty($fieldName)) {
				$updateValues[] = $this->_db->quoteInto($fieldName . ' = ?', $fieldValue);
			}
		}

		$this->_db->beginTransaction();

		$sql .= implode(', ', $updateValues);
		$this->alert($sql);
		$this->_db->query($sql);

		return $this->_db->commit();
	}
}
