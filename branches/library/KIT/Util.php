<?php
/**
 *  Util Module
 *
 * @package PTA_Core
 * @copyright  2008 P.T.A. Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id: Util.php 433 2010-10-23 19:23:06Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class KIT_Util
{
	public static function getRemoteIp()
	{
		if ( isset($_SERVER["REMOTE_ADDR"]) ) {
			return $_SERVER["REMOTE_ADDR"];
		} else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ) {
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if ( isset($_SERVER["HTTP_CLIENT_IP"]) ) {
			return $_SERVER["HTTP_CLIENT_IP"];
		}

		return false;
	}

	/**
	 * Convert IP address to numeric presentation
	 *
	 * @param string $ip
	 * @return int
	 */
	public static function ipToNum($ip)
	{
		return sprintf("%u", ip2long($ip));

		$ipParts = explode('.', $ip);

		if (empty($ipParts)) {
			return 0;
		}

		$num = 16777216 * $ipParts[0] + 65536 * $ipParts[1] + 256 * $ipParts[2] + $ipParts[3];

		return $num;
	}

}