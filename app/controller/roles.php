<?php
namespace Controller;

class Roles extends Backend {

	public function delete( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
			$role = new \Model\Role( $f3->get( 'POST.id' ) );
			if( ! $role->dry() && $role->delete() ) {
				$f3->SESSION->messages[] = array( "L.roledeletesuccessful", 0 );
			} else {
				$f3->SESSION->messages[] = array( "L.roledeleteerror", 1 );
			}
		} else {
			$f3->SESSION->messages[] = array( "L.roledeleteerror", 1 );
		}
		$f3->reroute( '/settings/role' );
	}

	public function show( \Base $f3, $params ) {
		$role = new \Model\Role();

		$f3->set( 'roles', $role->getAllRoles() );
		$f3->set( 'section', 'users.html' );
	}

}

