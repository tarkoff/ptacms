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

			case 'offer':
				$this->_section = 'offer';
				$this->_parseOffers($attrs, self::SECTION_INIT);
			break;
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

			case 'url':
				if ('adv' == $this->_section) {
					$this->_parseAdvertizers(array(), self::SECTION_RUN);
				}
			break;

			case 'offer':
				$this->_parseOffers(array(), self::SECTION_FLUSH);
				$this->_section = null;
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

	protected function _parseAdvertizers($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $values = array(), $geotargets = array();
		$table = $this->_config->tables->advertizers;
		$geoTargetTable = $this->_config->tables->adv_region_geotarget;

		switch ($this->_currentTag) {
			case 'offer':
				if (self::SECTION_INIT == $mode) {
					$values = array();
					$values['OFFERS_ID'] = (int)$attrs['id'];
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
				$values['OFFERS_BRANDID'] = (int)$attrs['id'];;
			break;

			case 'categoryId':
				$values['OFFERS_ADVID'] = (int)trim($this->_currentText);
			break;

			case 'categoryId':
				$values['OFFERS_CATID'] = (int)trim($this->_currentText);
			break;

			case 'categoryId':
				$values['OFFERS_CATID'] = (int)trim($this->_currentText);
			break;
		}
	}

	protected function _parseOffers($attrs = array(), $mode = self::SECTION_INIT)
	{
		static $values = array(), $shopId = 0;
		$table = $this->_config->tables->advertizers;

		switch ($this->_currentTag) {
			case 'offer':
				if (self::SECTION_INIT == $mode) {
					$values = array();
					$values[''] = (int)$attrs['id'];
					$values['ADVERTIZERS_PRICEMARKUP'] = (int)$attrs['price_markup'];
					$values['ADVERTIZERS_UPDATED'] = 0;
				} else if (self::SECTION_FLUSH == $mode) {
					$this->_insertOrUpdate($table->name, $values);
					foreach ($geotargets as $geo) {
						$this->_insertOrUpdate($geoTargetTable->name, $geo);
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
				$geotargets[] = array(
					'ADVREGIONGEOTARGET_ADVID' => $values['ADVERTIZERS_ID'],
					'ADVREGIONGEOTARGET_RGTID' => $attrs['id'],
					'ADVREGIONGEOTARGET_PRICEMARKUP' => $attrs['price_markup']
				);
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
		$this->_db->query($sql);
		$this->alert($sql);

		return $this->_db->commit();
	}
}
