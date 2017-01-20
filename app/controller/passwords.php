<?php

namespace Controller;

class Passwords extends Base {

	public function add( $f3, $params ) {
		if( $f3->exists( 'POST.name' ) ) {
			$f3->reroute( '/dashboard' );
		} else {
			$f3->set( 'content', 'newpassword.html' );
		}
	}

}
