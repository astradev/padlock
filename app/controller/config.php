<?php

namespace Controller;

class Config extends Backend {

	public function __construct() {
		parent::__construct();
		$f3 = \BASE::instance();
		$f3->set( 'component', 'config' );
	}

	public function show( \Base $f3, $params ) {
		$f3->set( 'section', 'config.html' );
	}

}

