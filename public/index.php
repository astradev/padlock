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

// language
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
$f3->route( 'GET /dashboard', 'Controller\Folders->show' );

//Folder routes
$f3->route( 'GET /folder', 'Controller\Folders->show' );
$f3->route( 'GET /folder/@id', 'Controller\Folders->show' );
$f3->route( 'GET|POST /folder/add', 'Controller\Folders->create_edit' );
$f3->route( 'GET|POST /folder/edit', 'Controller\Folders->create_edit' );
$f3->route( 'GET|POST /folder/edit/@id', 'Controller\Folders->create_edit' );
$f3->route( 'GET|POST /folder/delete', 'Controller\Folders->delete' );
$f3->route( 'GET|POST /folder/delete/@id', 'Controller\Folders->delete' );

//Password routes
$f3->route( 'GET|POST /password/add', 'Controller\Passwords->create_edit' );
$f3->route( 'GET|POST /password/edit', 'Controller\Passwords->create_edit' );
$f3->route( 'GET|POST /password/edit/@id', 'Controller\Passwords->create_edit' );
$f3->route( 'GET|POST /password/delete', 'Controller\Passwords->delete' );
$f3->route( 'GET|POST /password/delete/@id', 'Controller\Passwords->delete' );

//User routes
$f3->route( 'GET|POST /user/add', 'Controller\Users->edit' );
$f3->route( 'GET|POST /user/edit', 'Controller\Users->edit' );
$f3->route( 'GET|POST /user/edit/@id', 'Controller\Users->edit' );

//Settings
$f3->redirect( 'GET /settings', '/settings/config' );
$f3->route( 'GET /settings/config', 'Controller\Config->show' );
$f3->route( 'GET /settings/users', 'Controller\Users->show' );
$f3->route( 'GET /settings/roles', 'Controller\Roles->show' );
$f3->route( 'GET /settings/permissions', 'Controller\Permissions->show' );


// API
$f3->route( 'GET /api/foldertree', 'Controller\API->foldertree' );
$f3->route( 'GET /api/test', 'Controller\API->testout' );

$f3->run();
