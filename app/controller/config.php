<?php

namespace Controller;

class Config extends Backend {

	public function __construct( $filename, $safefile = false ) {
		parent::__construct();
		$f3 = \BASE::instance();
    
		$f3->set( 'component', 'config' );
	}

	public function write( \Base $f3 ) {
	  $file = "../config/config.ini";

	  if( $f3->get( 'VERB' ) == "POST" ) {
	    $data = $f3->get( 'POST' );
	    $content = "";

	    $ini = parse_ini_file( $file, TRUE );

	    foreach( $data as $section => $values ) {
	      $content .= "[". $section ."]\r\n";
	      foreach( $values as $key => $value ) {
		$content .= $key ."=". $value ."\r\n";
	      }
	    }

	    if( ! $handle = fopen( $file, "w" ) ) {
	      $f3->push( 'SESSION.messages', array( $f3->get( 'L.configupdatederr' ), 1 ) );
	      return false;
	    }

	    $success = fwrite( $handle, $content );
	    fclose( $handle );

	    $f3->push( 'SESSION.messages', array( $f3->get( 'L.configupdated' ), 0 ) );
	    $f3->reroute( '/settings/config' );
	  }
	}

	public function show( \Base $f3 ) {
	  $config = new \Model\Config();
	  $tz = $config->tz();

	  $file = "../config/config.ini";
	  $ini = parse_ini_file( $file, TRUE );
	  $encmethods = openssl_get_cipher_methods( false );

	  $f3->set( 'timezone', $tz );
	  $f3->set( 'ini', $ini );
	  $f3->set( 'encmethods', $encmethods );
	  $f3->set( 'section', 'config.html' );
	}

}

