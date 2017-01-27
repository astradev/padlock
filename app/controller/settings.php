<?php

namespace Controller;

class Settings extends Base {

	public function __construct() {
		if( ! \Permissions::instance()->isSuperuser() ) {
			$f3 = \BASE::instance();
			$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
			$f3->reroute( '/dashboard' );
		}
	}
	public function all( \Base $f3, $params ) {
	    // template information
		$f3->set( 'content', 'settings.html' );
	}
}
