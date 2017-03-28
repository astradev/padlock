<?php
////
// Padlock - an enterprise password manager
//
// Copyright (c) 2017 Marco Dickert <marco@misterunknown.de>, Leonardo Riedel <leo@astradev.de>

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
  $f3->route( 'GET|POST /install', 'System->install' );
  $f3->route( 'GET|POST /syscheck', 'System->syscheck' );
  $f3->reroute( '/install' );
}

// language switch
if( $f3->get( 'COOKIE.padlock_language' ) ) {
  $f3->set( 'LANGUAGE', $f3->get( 'COOKIE.padlock_language' ) );
}
$f3->route( 'GET */@lang/back/@URI', 
  function($f3, $params) { 
    $f3->set( 'COOKIE.padlock_language', $f3->get( 'PARAMS.lang' ), time() + (86400 *30) );
    $f3->reroute( $f3->get( 'DEFAULT_URL' ) . urldecode( str_replace( " ", "/", $f3->get( 'PARAMS.URI' ) ) ) );
  }
);

// initialize logger
$f3->set( 'logger', new \Log( 'debug.log' ) );

// initialize messages
if( ! $f3->get( 'SESSION.messages' ) ) $f3->set( 'SESSION.messages', array() );

// initialize db
$f3->set( 'DB', new \DB\SQL( 'mysql:host=' . $f3->get( 'DBHOST' ) . ';port=' . $f3->get( 'DBPORT' ) . ';dbname=' . $f3->get( 'DBNAME' ), $f3->get( 'DBUSER' ), $f3->get( 'DBPASS' ) ) );

$f3->run();
