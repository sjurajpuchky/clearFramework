<?php
ini_set ( 'display_errors', '1' );
//session_start ();
mb_internal_encoding ( "UTF-8" );

include_once ("config.php");
include_once ("include/db.php");
include_once ("include/Mailer.php");



// Autoloader
function autoload($class) {
	if (! class_exists ( $class )) {
		if (mb_strpos ( $class, "Controller" ) === false)
			require (__DIR__ . "/models/$class.php");
		else
			require (__DIR__ . "/controllers/$class.php");
	}
}
spl_autoload_register ( "autoload" );

