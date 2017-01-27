<?php

namespace Controller;

class Users extends Base {

	public function delete( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
			$user = new \Model\Users( $params['id'] );
			$user->delete();
			$f3->SESSION->messages[] = array( "L.userdeletesuccessful", 0 );
                        if( isset( $params['id'] ) )
                          $f3->reroute( '/settings/user/' . $params['id'] );
                        else
                          $f3->reroute( '/settings/user' );
		} else {
			$f3->SESSION->messages[] = array( "L.userdeleteerror", 1 );
			$f3->reroute( '/settings/user' );
		}
	}

}

