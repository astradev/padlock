<?php

namespace Controller;

class Dashboard extends Base {
	public function show( \Base $f3, $params ) {
		$f3->set( 'folders', \TreeBuilder::instance()->generateTree() );
		// template information
		$f3->set( 'content', 'dashboard.html' );
	}
}
