<?php
/*
   Padlock
*/

/*
   Initialization
*/
$f3 = require( '../lib/base.php' );

/* For the installer */
//if( file_exists( '../app/controller/install.php' )) {
//        $f3->route( 'GET /install', 'Controller\Installer->start' );
//} else { 

// check config
if( is_readable( '../config/config.ini' ) ) {
	$f3->config( '../config/config.ini' );
} else {
	trigger_error( 'Could not load configuration: ' . dirname( __FILE__) . '/../config/config.ini' );
}

// lang switch

if( $f3->get( 'COOKIE.padlock_language' ) ) {
    $f3->set( 'LANGUAGE', $f3->get( 'COOKIE.padlock_language' ) );
}

$f3->route( 'GET /lang/@lang', 
  function($f3) { 
      $f3->set( 'COOKIE.padlock_language', $f3->get( 'PARAMS.lang' ), time() + (86400 *30) );
      $f3->reroute( '/dashboard' );
  }
);

// check tmp dir

// logger
$f3->set( 'logger', new \Log('debug.log') );

//messages
if( ! $f3->get( 'SESSION.messages' ) ) $f3->set( 'SESSION.messages', array() );

// set db
$f3->set( 'DB', new \DB\SQL( 'mysql:host=localhost;port=3306;dbname=padlock', 'padlock', 'Schlagbohrumschwunggewicht' ) );

$f3->route( 'GET /', 'Controller\Folders->show' );
$f3->route( 'GET|POST /login', 'Controller\Auth->login' );
$f3->route( 'GET /logout', 'Controller\Auth->logout' );
$f3->route( 'GET|POST /folder/add', 'Controller\Folders->edit' );
$f3->route( 'GET|POST /folder/edit', 'Controller\Folders->edit' );
$f3->route( 'GET|POST /folder/edit/@id', 'Controller\Folders->edit' );
$f3->route( 'GET|POST /password/add', 'Controller\Passwords->edit' );
$f3->route( 'GET|POST /password/edit', 'Controller\Passwords->edit' );
$f3->route( 'GET|POST /password/edit/@id', 'Controller\Passwords->edit' );
$f3->route( 'GET /settings', 'Controller\Settings->all' );
$f3->route( 'GET /folder/@id', 'Controller\Folders->show' );
$f3->route( 'GET /folder', 'Controller\Folders->show' );
$f3->route( 'GET /dashboard', 'Controller\Folders->show' );

$f3->run();

//}
