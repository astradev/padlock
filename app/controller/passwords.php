<?php

namespace Controller;

class Passwords extends Base {

	public function edit( $f3, $params ) {
		if( $f3->exists( 'POST.label' ) && $f3->exists( 'POST.password' ) ) {
			$password = new \Model\Password();
			if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
				$password->load( array( "id=?", $f3->get( 'POST.id' ) ) );
			}
			$err = 0;
			if( !is_numeric( $f3->get( 'POST.folder_id' ) ) || ! \PermissionHelper::instance()->hasPermission( $f3->get( 'POST.folder_id' ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1, true ) );
				$err++;
			}
			if( empty( trim( $f3->get( 'POST.label' ) ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.nolabelset' ), 1, true ) );
				$err++;
			}
			if( empty( trim( $f3->get( 'POST.password' ) ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopasswordset' ), 1, true ) );
				$err++;
			}
			if( $err == 0 ) {
				$password->label = $f3->get( 'POST.label' );
				$password->login = $f3->get( 'POST.login' );
				$password->password = $f3->get( 'POST.password' );
				$password->description = $f3->get( 'POST.description' );
				$password->folder_id = $f3->get( 'POST.folder_id' );

				$password->save();
				$f3->reroute( '/dashboard' );
			}
		}
		$password = new \Model\Password();
		if( isset( $params['id'] ) && is_numeric( $params['id'] ) ) {
			$password->load( array( 'id=?', $params['id'] ) );
			if( ! $password->dry() ) {
				if( \PermissionHelper::instance()->hasPermission( $password->folder_id ) < 2 ) {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
					$f3->reroute( '/dashboard ');
				}
			} else {
				$password->reset();
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopwid' ), 1 , true ) );
			}
		}
		foreach( $password->fields() as $key ) {
			$pw[$key] = $password->$key;
		}
		$f3->set( 'pw', $pw );
		$f3->set( 'content', 'passwordform.html' );
	}

}
