<?php

namespace Controller;

class Config extends Backend {

	public function __construct( $filename, $safefile = false ) {
		parent::__construct();
		$f3 = \BASE::instance();
    
		$f3->set( 'component', 'config' );
	}

	public function read( \Base $f3 ) {
	  $file = "../config/config.ini";

	  $ini = parse_ini_file( $file, TRUE );

	  foreach( $ini as $key => $value ) {
	    foreach( $value as $k => $v ) {
	      $f3->set( 'k', $k );
	      $f3->set( 'v', $v );
	    }
	  }
	}		

	public function write( \Base $f3 ) {
	  $file = "../config/config.ini";

	  if( $f3->get( 'VERB' ) == "POST" ) {
	    $data = $f3->get( 'VERB' ) == "POST";

	    $content = "";

	    $ini = parse_ini_file( $file, TRUE );

	    foreach( $data as $section => $values ) {
	      $content .= "[".$section."]n";
    	      foreach( $values as $key => $value ) {
		$content .= $key."=".$value."n";
	      }
	    }

	    if( !$handle = fopen( $file, "w" ) ) {
	      $f3->push( 'SESSION.messages', array( "Fehler", 1 ) );
	    }

	    $success = fwrite( $handle, $content );
	    fclose( $handle );

	    $f3->push( 'SESSION.messages', array( "Gut", 0 ) );
	    return $success;
	  }
	}

	public function show( \Base $f3 ) {
	  $f3->set( 'section', 'config.html' );
	}

}

