<?php

namespace Controller;

class Users extends Backend {

	public function delete( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
			$user = new \Model\Users( $f3->get( 'POST.id' ) );
			if( ! $user->dry() && $user->delete() ) {
				$f3->SESSION->messages[] = array( "L.userdeletesuccessful", 0 );
			} else {
				$f3->SESSION->messages[] = array( "L.userdeleteerror", 1 );
			}
		} else {
			$f3->SESSION->messages[] = array( "L.userdeleteerror", 1 );
		}
		$f3->reroute( '/settings/user' );
	}

	public function show( \Base $f3, $params ) {
		$user = new \Model\User();

		$f3->set( 'users', $user->getAllUsers() );
		$f3->set( 'section', 'users.html' );
	}

}

