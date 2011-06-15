<?php
/**
 * Class for parsing incomming MixMarket xml file and store it to database
 *
 * @package    Tools
 * @copyright  Copyright (c) 2010 Taras Pavuk (tpavuk@gmail.com)
 * @license    BSD License
 */

require_once 'Geo/Abstract.php';

class Geo_Parser extends Geo_Abstract
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

		if (file_exists($file)) {
			$this->_xmlFile = $file;
		} else {
			trigger_error('Geo file ' . $file . ' not found', E_USER_ERROR);
		}
	}

	public function __destruct()
	{
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
			trigger_error('Geo file ' . $this->_xmlFile . ' not found', E_USER_ERROR);
		}
		
		ini_set('auto_detect_line_endings', 1);

		$fp = fopen($this->_xmlFile, 'r' );

		$rec = null;
		$recPos = 1;
		$recs = array();
		while( !feof($fp) ) {
			//$data = iconv('Windows-1251', 'UTF-8',  fgetcsv($fp, 4092, '	'));
			$rec = fgetcsv($fp, 4092, '	');
			$recIps = explode('-', $rec[2]);
			$recs[] = array('GEO_STARTNUM' => (int)$rec[0],
							'GEO_ENDNUM' => (int)$rec[1],
							'GEO_STARTIP' => trim(@$recIps[0]),
							'GEO_ENDIP' => trim(@$recIps[1]),
							'GEO_CODE' => $rec[3],
							'GEO_CITY' => $rec[4],
							'GEO_STATE' => $rec[5],
							'GEO_REGION' => $rec[6]
						   );
			//var_dump($data);
			if (!empty($recs) && (count($recs) % 50 == 0)) {
				$this->_insert('GEO_BLOCKS', $recs);
				$recs = array();
			}
		}
		
		if (!empty($recs)) {
			$this->_insert('GEO_BLOCKS', $recs);
		}
		
		fclose($fp);

		$this->linkGeo();
		
		return true;
	}

	public function linkGeo()
	{
		if (!file_exists($this->_xmlFile)) {
			trigger_error('Geo file ' . $this->_xmlFile . ' not found', E_USER_ERROR);
		}

		$sqlSelect = 'SELECT REGIONSGEOTAGRET_ID, REGIONSGEOTAGRET_TITLE FROM MIXMARKET_REGIONSGEOTAGRET';
		foreach ($this->_db->fetchAssoc($sqlSelect) as $geo) {
			$keyword = strtoupper(trim($geo['REGIONSGEOTAGRET_TITLE']));
			$this->_db->query(
				'UPDATE GEO_BLOCKS SET GEO_TARGETID = ' . $geo['REGIONSGEOTAGRET_ID']
					. ' WHERE UCASE(GEO_CITY) LIKE "%' . $keyword . '%"'
							  . ' OR UCASE(GEO_STATE) LIKE "%' . $keyword . '%"'
							  . ' OR UCASE(GEO_REGION) LIKE "%' . $keyword . '%"'
			);
		}
		
		return true;
	}


	/**
	 * Save parsed data to database
	 *
	 * @param string $table
	 * @param array $data
	 * @return boolean
	 */
	protected function _insert($table, $data)
	{
		$sql = 'insert ignore into ' . $table 
				. ' (' . implode(', ', array_keys(current($data))) . ') values ';
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
}
