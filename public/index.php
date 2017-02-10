<?php
////
// Padlock - an enterprise password manager
////

// initialize the framework
$f3 = require( '../lib/base.php' );

// load config
$padlock_config = '../config/default.ini';
if( file_exists( $padlock_config ) ) {
	if( is_readable( $padlock_config ) ) {
		$f3->config( $padlock_config );
	} else {
		trigger_error( 'Could not load configuration: ' . dirname( __FILE__) . $padlock_config );
	}
} else {
	$f3->reroute( '/install' );
}

// language switch
if( $f3->get( 'COOKIE.padlock_language' ) ) {
	    $f3->set( 'LANGUAGE', $f3->get( 'COOKIE.padlock_language' ) );
}
$f3->route( 'GET /lang/@lang', 
  function($f3) { 
      $f3->set( 'COOKIE.padlock_language', $f3->get( 'PARAMS.lang' ), time() + (86400 *30) );
      $f3->reroute( '/dashboard' );
  }
);

// initialize logger
$f3->set( 'logger', new \Log( 'debug.log' ) );

// initialize messages
if( ! $f3->get( 'SESSION.messages' ) ) $f3->set( 'SESSION.messages', array() );

// initialize db
$f3->set( 'DB', new \DB\SQL( 'mysql:host=' . $f3->get( 'DBHOST' ) . ';port=' . $f3->get( 'DBPORT' ) . ';dbname=' . $f3->get( 'DBNAME' ), $f3->get( 'DBUSER' ), $f3->get( 'DBPASS' ) ) );

$f3->route( 'GET /', 'Controller\Folders->show' );
$f3->route( 'GET|POST /login', 'Controller\Auth->login' );
$f3->route( 'GET /logout', 'Controller\Auth->logout' );
$f3->route( 'GET /dashboard', 'Controller\Folders->show' );

//Folder routes
$f3->route( 'GET /folder', 'Controller\Folders->show' );
$f3->route( 'GET /folders', 'Controller\Folders->show' );
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

//Settings
$f3->redirect( 'GET /settings', '/settings/config' );
$f3->route( 'GET /settings/config', 'Controller\Config->show' );
$f3->route( 'GET /settings/config/edit', 'Controller\Config->edit' );
$f3->route( 'GET /settings/users', 'Controller\Users->show' );
$f3->route( 'GET|POST /settings/user/add', 'Controller\Users->create_edit' );
$f3->route( 'GET|POST /settings/user/edit/@id', 'Controller\Users->create_edit' );
$f3->route( 'GET|POST /settings/user/delete', 'Controller\Users->delete' );
$f3->route( 'GET|POST /settings/user/delete/@id', 'Controller\Users->delete' );
$f3->route( 'GET /settings/roles', 'Controller\Roles->show' );
$f3->route( 'GET|POST /settings/role/add', 'Controller\Roles->create_edit' );
$f3->route( 'GET|POST /settings/role/edit/@id', 'Controller\Roles->create_edit' );
$f3->route( 'GET /settings/permissions', 'Controller\Permissions->show' );
$f3->route( 'GET /settings/permissions/@mode', 'Controller\Permissions->show' );
$f3->route( 'POST /settings/permissions/add', 'Controller\Permissions->add' );
$f3->route( 'POST /settings/permissions/delete', 'Controller\Permissions->delete' );

// API
$f3->route( 'GET /api/foldertree', 'Controller\API->foldertree' );
$f3->route( 'GET /api/test', 'Controller\API->testout' );

$f3->run();
