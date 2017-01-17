<?php

namespace Controller;

class Test extends Base {
	public function main( \Base $f3, $params ) {
		$myUser = new \Model\User( 'admin' );
		$f3->set( 'TESTVAR', $myUser->email );
	}
}
