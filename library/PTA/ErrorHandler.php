<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 PTA Studio
 * @license	http://framework.zend.com/license   BSD License
 * @version	$Id$
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

class PTA_ErrorHandler
{
	public static function log($errno, $errstr, $errfile, $errline)
	{
		switch ($errno) {
			case E_USER_ERROR:
				echo "<b>My ERROR</b> [$errno] $errstr<br />" . PHP_EOL;
				echo "  Fatal error on line $errline in file $errfile";
				echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />" . PHP_EOL;
				echo "Aborting...<br />" . PHP_EOL;
				exit(1);
			break;

			case E_USER_WARNING:
				echo "<b>WARNING</b> [$errno] $errstr<br />\n";
			break;

			case E_USER_NOTICE:
				echo "<b>NOTICE</b> [$errno] $errstr<br />\n";
				break;

			default:
				echo "Unknown error type: [$errno] $errstr<br />\n";
			break;
		}
	}
}
