<?php

namespace Controller;

class Test extends Base {
	public function main( \Base $f3, $params ) {
		if( ! \Controller\Auth::isLoggedIn() ) {
			$f3->reroute( '/login' );
		} else {
			$myUser = new \Model\User( 'admin' );
			$f3->set( 'TESTVAR', $myUser->email );
		}
	}
}
