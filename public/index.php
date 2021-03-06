<?php
if(get_magic_quotes_gpc()) {
	$in = array(&$_GET,&$_POST,&$_COOKIE);
	while(list($k,$v) = each($in)) {
		foreach($v as $key=>$val) {
			if(!is_array($val)) $in[$k][$key] = stripslashes($val);
			else $in[] =& $in[$k][$key];
		}
	}
	unset($in);
}
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();