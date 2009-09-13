<?php
/**
 * Sitemap builder util
 *
 * @package PTA_Util
 * @copyright  2009 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Table.php 65 2009-06-04 21:30:33Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class SitemapBuilder 
{
	private $_xml;
	private $_localUrls = array();
	private $_rootUrl;
	private $_allowedExt = array();
	private $_host;
	private $_savePath = '.';

	/**
	 * Return url passed for sitemap creation
	 * 
	 * @return string
	 */
	public function getRootUrl()
	{
		return $this->_rootUrl;
	}

	/**
	 * Set url for sitemap creation
	 * 
	 * @param string $url
	 */
	public function setRootUrl($url)
	{
		$this->_rootUrl = (string)$url;
		$url = parse_url($url);
		$this->setHost($url['scheme'] . '://' . $url['host']);
		unset($url);
	}

	/**
	 * Return site host
	 * 
	 * @return string
	 */
	public function getHost()
	{
		return $this->_host;
	}

	/**
	 * Set site host
	 * 
	 * @param string $host
	 */
	public function setHost($host)
	{
		$this->_host = (string)$host;
	}

	/**
	 * Return save path for sitemap
	 * 
	 * @return string
	 */
	public function getSavePath()
	{
		return $this->_savePath;
	}

	/**
	 * Set save path for sitemap
	 * 
	 * @param string $host
	 */
	public function setSavePath($path)
	{
		$this->_savePath = (string)$path;
	}

	/**
	 * Return allowed extentions for sitemap
	 * 
	 * @return string
	 */
	public function getAllowedExt()
	{
		return $this->_allowedExt;
	}

	/**
	 * Add allowed extentons for sitemap
	 * 
	 * @param array|string $ext
	 */
	public function addAllowedExt($ext = array())
	{
		$ext = (array)$ext;

		if (is_array($ext)) {
			$ext = array_map('strtolower', $ext);
			$ext = array_combine($ext, $ext);
		}

		$this->_allowedExt = array_merge($this->_allowedExt, $ext);
	}

	/**
	 * Build and save sitemap for setted root url
	 */
	public function build()
	{
		$start = microtime(true);

		$urls = $subUrls = $this->_localUrls[0] = array($this->getHost());

		$i = 0;
		while (!empty($subUrls)) {
			$subUrls = array();
			foreach ($urls as $url) {
				$subUrls = array_merge($subUrls, $this->getPageUrls($url));
			}

			$subUrls = array_unique($subUrls);
			foreach ($this->_localUrls as $levelUrls) {
				$subUrls = array_diff($subUrls, $levelUrls);
			}

			if (!empty($subUrls)) {
				$urls = $this->_localUrls[++$i] = $subUrls;
			}
		}

		$this->buildXml();
	}

	/**
	 * Parse setted url and return all loacal links from this page
	 * 
	 * @param string $url
	 * @return array
	 */
	public function getPageUrls($url)
	{
		$urls = array();
		$host = $this->getHost();

		preg_match_all(
			"/<[aA][^'\"<>]+href=['\"]((" . addcslashes($host, './') ."|[\/.])[^'\"<>]+)['\"]/",
			$this->getUrlContent($url),
			$matches,
			PREG_SET_ORDER
		);

		foreach ($matches as $url) {
			if (strlen($url[1]) > 1 && $this->isCorrectUrl($url[1])) {
				if (0 === strpos($url[1], '/')) {
					$url[1] = rtrim($host, '/') . $url[1];
				}
				$urls[] = $url[1];
			}
		}

		unset($matches, $url);

		return $urls;
	}

	/**
	 * Return page content by url
	 * 
	 * @param string $url
	 * @return string
	 */
	public function getUrlContent($url)
	{
		// create a new cURL resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// grab URL and pass it to the browser
		$html = curl_exec($ch);

		// close cURL resource, and free up system resources
		curl_close($ch);

		return $html;
	}

	/**
	 * Return true if setted url correct
	 * 
	 * @param string $url
	 * @return boolean
	 */
	public function isCorrectUrl($url)
	{
		if (empty($url)) {
			return false;
		}

		if (!empty($this->_allowedExt)) {
			$ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
			if (!empty($ext) && !isset($this->_allowedExt[$ext])) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Build/Save result sitemap xml
	 */
	public function buildXml()
	{
		if (empty($this->_localUrls)) {
			trigger_error('URL\'s list for sitemap is empty!');
			return false;
		}

		$xmlTemplate = dirname(__FILE__) . '/sitemap_template.xml';

		if (!file_exists($xmlTemplate)) {
			trigger_error('Sitemap Template file missed!');
			return false;
		}

		$priority = 1;
		$changefreq = array(
			0 => 'daily', 1 => 'weekly',
			2 => 'monthly', 3 => 'monthly',
			4 => 'monthly', 5 => 'monthly',
			6 => 'monthly', 7 => 'monthly',
			8 => 'monthly', 9 => 'monthly'
		);

		$xml = simplexml_load_file($xmlTemplate);

		foreach ($this->_localUrls as $level => $levelUrls) {
			foreach ($levelUrls as $url) {
				$xmlUrl = $xml->addChild('url');
				$xmlUrl->addChild('loc', $url);
				$xmlUrl->addChild('changefreq', $changefreq[$level]);
				$xmlUrl->addChild('priority', $priority);
			}

			if ($priority > 0.6) {
				$priority -= 0.1;
			}
		}

		file_put_contents($this->getSavePath(), $xml->asXML());
	}
}
