<?php

namespace Controller;

class Dashboard extends Base {
	public function show( \Base $f3, $params ) {
		$f3->set( 'folders', \Permissions::instance()->getFolders( $f3->get( 'SESSION.user.id' ) ) );
		$f3->logger->write( 'dash: msg: ' . print_r( $f3->get( 'SESSION.messages' ), true ) );
		// template information
		$f3->set( 'content', 'dashboard.html' );
	}
}
