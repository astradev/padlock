<?php
namespace Controller;

class Roles extends Backend {

	public function __construct() {
		parent::__construct();
		$f3 = \BASE::instance();
		$f3->set( 'component', 'roles' );
	}

	public function create_edit( $f3, $params ) {
		$f3->set( 'section', 'roleform.html' );
		$role = new \Model\Role();

		if( isset( $params['id'] ) && is_numeric( $params['id'] ) ) {
			$role->load( array( "id=?", $params['id'] ) );
		}
		$f3->set( 'formRole', $role );

		if( $f3->get( 'VERB' ) == "POST" ) {
			if( $f3->exists( 'POST.id' ) && ! is_numeric( $f3->get( 'POST.id' ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalidid' ), 1 ) );
				return;
			} elseif ( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
				$role->reset();
				$role->load( array( 'id=?', $f3->get( 'POST.id' ) ) );
				if( $role->dry() ) {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalidid' ), 1 ) );
					$f3->clear( 'formRole' );
					$f3->set( 'formRole', $role );
				}
			}
			if( ! $f3->exists( 'POST.name' ) || empty( trim( $f3->get( 'POST.name' ) ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalidname' ), 1 ) );
				return;
			} else {
				$tmp = new \Model\Role();
				$tmp->load( array( 'name=?', $f3->get( 'POST.name' ) ) );
				if( ! $tmp->dry() && $f3->get( 'POST.id' ) != $tmp->id ) {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.roleexists' ), 1 ) );
					return;
				}
				$role->name = $f3->get( 'POST.name' );
			}

			if( $role->save() ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.rolesaved' ), 0 ) );
				$f3->reroute( '/settings/roles' );
			} else {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.rolesaveerr' ), 1 ) );
			}
		}
	}

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
		$f3->set( 'formRole', $role );
		$f3->set( 'section', 'roles.html' );
	}

}

