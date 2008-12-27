<?php

class PTA_ErrorHandler
{
    public static function log($errno, $errstr, $errfile, $errline)
    {
        $db = Zend_Registry::get('db');

        switch ($errno) {
            case E_USER_ERROR:
                echo "<b>My ERROR</b> [$errno] $errstr<br />" . PHP_EOL;
                echo "  Fatal error on line $errline in file $errfile";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />" . PHP_EOL;
                echo "Aborting...<br />" . PHP_EOL;
                if (!empty($db)) {
                    $lastQuery = $db->getProfiler()->getLastQueryProfile();
                    
                    if (!empty($lastQuery)) {
                        echo '<b>Last SQL Query</b> ' . $lastQuery->getQuery() . '<br/>' . PHP_EOL;
                    }
                }
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
