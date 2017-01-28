<?php
namespace Controller;

class Backend extends Base {

	public function __construct() {
		$f3 = \BASE::instance();
		if( ! \Permissions::instance()->isSuperuser() ) {
			$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
			$f3->reroute( '/dashboard' );
		}
		$f3->set( 'content', 'settings.html' );
	}

}
