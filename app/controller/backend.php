<?php

namespace Controller;

class Backend extends Base {

	public function __construct() {
		if( ! \Permissions::instance()->isSuperuser() ) {
			$f3 = \BASE::instance();
			$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
			$f3->reroute( '/dashboard' );
		}
	}

}
