<?php

namespace Controller;

abstract class Base {

	protected $response;

	public function beforeroute($f3) {
		if( ! \Controller\Auth::isLoggedIn() ) {
			$f3->reroute( '/login' );
		} elseif( $f3->get( 'PATH' ) == "/" ) {
			$f3->reroute( '/dashboard' );
		}
		$this->response = new \View\Frontend();
		if( ! $f3->exists( 'SESSION.treeUpdateTrigger' ) ) $f3->set( 'SESSION.treeUpdateTrigger', 'false' );
	}

	public function afterroute() {
		if (!$this->response)
			trigger_error('No View has been set.');
		echo $this->response->render();
	}
}
