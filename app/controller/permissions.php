<?php

namespace Controller;

class Permissions extends Backend {

	public function __construct() {
		parent::__construct();
		$f3 = \BASE::instance();
		$f3->set( 'component', 'permissions' );
	}

	public function show( \Base $f3, $params ) {
		$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree() );
		$f3->set( 'section', 'permissions.html' );
	}

}

