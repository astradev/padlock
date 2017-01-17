<?php

namespace Controller;

class Test extends Base {
	public function main( \Base $f3, $params ) {
		if( ! \Controller\Auth::isLoggedIn() ) {
			$f3->reroute( '/login' );
		} else {
                        $myUser = new \Model\User( 'SESSION.login' );
                        $f3->set( 'fullname', $myUser->name );
                        $f3->set( 'content', 'dashboard.html' );
		}
	}
}
