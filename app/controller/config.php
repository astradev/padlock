<?php

namespace Controller;

class Config extends Backend {

	public function __construct() {
		parent::__construct();
		$f3 = \BASE::instance();
		$f3->set( 'component', 'config' );
	}

	public function edit( $f3, $params ) {
	
	}
  
	public function open_ini( $array, $indent = 0 ) {
	  global $str;
	  foreach( $array as $k => $v ) {
	    if( is_array( $v ) ) {
	      for( $i=0; $i < $indent * 5; $i++ ) {
		$str .= " ";
	      }
	      open_ini( $v, $indent + 1 );
	    } else {
	      for( $i=0; $i < $indent * 5; $i++) {
		$str .= " ";
	      }
	    }
	  }
	}

	public function edit_ini( $array, $file ) {
	}

	public function show( \Base $f3 ) {
		$ini = new \Controller\Config();

		$f3->set( 'configOut', $ini->open_ini() );
		$f3->set( 'section', 'config.html' );
	}

}

