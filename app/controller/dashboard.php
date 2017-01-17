<?php

namespace Controller;

class Dashboard extends Base {
	public function show( \Base $f3, $params ) {
          $myUser = new \Model\User( 'SESSION.login' );
          $f3->set( 'fullname', $myUser->name );
          $f3->set( 'content', 'dashboard.html' );
	}
}
