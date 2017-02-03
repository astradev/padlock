<?php

namespace Controller;

class Users extends Backend {

	public function __construct() {
		parent::__construct();
		$f3 = \BASE::instance();
		$f3->set( 'component', 'users' );
	}

	public function create_edit( $f3, $params ) {
		$f3->set( 'section', 'userform.html' );
		$user = new \Model\User();

		if( isset( $params['id'] ) && is_numeric( $params['id'] ) ) {
			$user->load( array( "id=?", $params['id'] ) );
		}
		$f3->set( 'formUser', $user );

		if( $f3->get( 'VERB' ) == "POST" ) {
			if( $f3->exists( 'POST.id' ) && ! is_numeric( $f3->get( 'POST.id' ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalidid' ), 1 ) );
				return;
			} elseif ( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
				$user->reset();
				$user->load( array( 'id=?', $f3->get( 'POST.id' ) ) );
				if( $user->dry() ) {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalidid' ), 1 ) );
					$f3->clear( 'formUser' );
					$f3->set( 'formUser', $user );
				}
			}
			if( ! $f3->exists( 'POST.login' ) || empty( trim( $f3->get( 'POST.login' ) ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalidlogin' ), 1 ) );
				return;
			} else {
				$tmp = new \Model\User();
				$tmp->load( array( 'login=?', $f3->get( 'POST.login' ) ) );
				if( ! $tmp->dry() && $tmp->id != $f3->get( 'POST.id' ) ) {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.loginexists' ), 1 ) );
					return;
				}
				$user->login = $f3->get( 'POST.login' );
			}
			if( $f3->exists( 'POST.newpassword' ) && ! empty( trim( $f3->get( 'POST.newpassword' ) ) ) ) {
				$user->newpassword = $f3->get( 'POST.newpassword' );
			}
			$user->email = $f3->get( 'POST.email' );
			$user->name = $f3->get( 'POST.name' );
			if( $f3->get( 'POST.superuser' ) == "yes" ) {
				$user->superuser = true;
			}
			if( $f3->exists( 'POST.newroles' ) ) {
				$newroles = $f3->get( 'POST.newroles' );
				if( ! is_array( $newroles ) ) $newroles = array( $newroles );
				$oldroles = array_column( $user->roles, 'id' );
				sort( $newroles );
				sort( $oldroles );
				if( $newroles != $oldroles )
					$user->newroles = $newroles;
			}

			if( $user->save() ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.usersaved' ), 0 ) );
				$f3->reroute( '/settings/users' );
			} else {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.usersaveerr' ), 1 ) );
			}
		}
	}

	public function delete( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
			$user = new \Model\User( $f3->get( 'POST.id' ) );
			if( ! $user->dry() && $user->delete() ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.userdeletesuccessful' ), 0 ) );
			} else {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.userdeleteerror' )." User not found", 1 ) );
			}
		} else {
			$f3->push( 'SESSION.messages', array( $f3->get( 'L.userdeleteerror' )." ID not given or smth.", 1 ) );
		}
		$f3->reroute( '/settings/users' );
	}

	public function show( \Base $f3, $params ) {
		$user = new \Model\User();
		$role = new \Model\Role();
		$allUsers = $user->getAllUsers();
		$allRoles = $role->getAllRoles();

		for( $i = 0; $i < count( $allUsers ); $i++ ) {
			$hisRoles = explode( ',', $allUsers[$i]['roles'] );
			$allUsers[$i]['roles'] = array();
			foreach( $hisRoles as $role ) {
				if( intval( $role ) > 0 )
					array_push( $allUsers[$i]['roles'], array_values( array_filter( $allRoles, function( $row ) use ( $role ) { return $row['id'] == intval( $role ); } ) )[0] );
			}
		}

		$f3->set( 'users', $allUsers );
		$f3->set( 'formUser', $user );
		$f3->set( 'allRoles', $allRoles );
		$f3->set( 'section', 'users.html' );
	}

}

