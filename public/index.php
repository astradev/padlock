<?php
/*
   Padlock
*/

/*
   Initialization
*/
$f3 = require( '../lib/base.php' );

// check config
if( is_readable( '../config/config.ini' ) ) {
	$f3->config( '../config/config.ini' );
} else {
	trigger_error( 'Could not load configuration: ' . dirname( __FILE__) . '/../config/config.ini' );
}

// check tmp dir

// set db
$f3->set( 'DB', new \DB\SQL( 'mysql:host=localhost;port=3306;dbname=padlock', 'padlock', 'Schlagbohrumschwunggewicht' ) );

$f3->route( 'GET /', 'Controller\Test->main' );
$f3->route( 'GET /login', 'Controller\Auth->login' );

$f3->run();
