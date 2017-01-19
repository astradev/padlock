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

// logger
$f3->set( 'logger', new \Log('debug.log') );

//messages
if( ! $f3->get( 'SESSION.messages' ) ) $f3->set( 'SESSION.messages', array() );

// set db
$f3->set( 'DB', new \DB\SQL( 'mysql:host=localhost;port=3306;dbname=padlock', 'padlock', 'Schlagbohrumschwunggewicht' ) );

$f3->route( 'GET /', 'Controller\Test->main' );
$f3->route( 'GET|POST /login', 'Controller\Auth->login' );
$f3->route( 'GET /logout', 'Controller\Auth->logout' );
$f3->route( 'GET /dashboard', 'Controller\Dashboard->show' );
$f3->route( 'GET|POST /folder/new', 'Controller\Folders->newfolder' );
$f3->route( 'GET /settings', 'Controller\Settings->all' );

$f3->run();
