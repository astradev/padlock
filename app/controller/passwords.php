<?php

namespace Controller;

class Passwords extends Base {

	public function edit( $f3, $params ) {
			$password = new \Model\Password();
		if( $f3->exists( 'POST.password' ) ) {
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
		$f3->set( 'content', 'passwordform.html' );
	}

	public function create_edit( $f3, $params ) {
		$f3->set( 'content', 'passwordform.html' );
		$password = new \Model\Password();

		if( isset( $params['id'] ) && is_numeric( $params['id'] ) ) {
			$password->load( array( "id=?", $params['id'] ) );
			if( ! $password->dry() ) {
				$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree( $params['id'] ) );
			}
		} elseif( isset( $_SERVER['HTTP_REFERER'] ) )   {
			$ref_matches = array();
			if( preg_match( ',folder/([0-9]+)$,', $_SERVER['HTTP_REFERER'], $ref_matches ) == 1 ) {
				$f3->logger->write( "MATCHED: ".print_r($ref_matches, true) );
				$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree( $ref_matches[1] ) );
			} else {
				$f3->logger->write( "NOTMATCHED" );
				$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree() );
			}
		} else {
			$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree() );
		}
		$f3->set( 'formPassword', $password );

		if( $f3->get( 'VERB' ) == "POST") {
			if( $f3->exists( 'POST.id' ) && ! is_numeric( $f3->get( 'POST.id' ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalididerr' ), 1 ) );
				return;
			} elseif ( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
				$password->reset();
				$password->load( array( 'id=?', $f3->get( 'POST.id' ) ) );
				if( $password->dry() ) {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalididerr' ), 1 ) );
					$f3->clear( 'formPassword' );
					$f3->set( 'formPassword', $password );
				}
			}
			if( ! $f3->exists( 'POST.folder_id' ) || ! is_numeric( $f3->get( 'POST.folder_id' ) ) || ! $f3->get( 'POST.folder_id' ) > 0 ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.novalidfoldererr' ), 1 ) );
				return;
			} else {
				$password->folder_id = $f3->get( 'POST.folder_id' );
				$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree( $password->folder_id ) );
			}
			if( ! $f3->exists( 'POST.label' ) || empty( trim( $f3->get( 'POST.label' ) ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.invalidpasswordlabel' ), 1 ) );
				return;
			} else {
				$password->label = $f3->get( 'POST.label' );
			}
			$password->description = $f3->get( 'POST.description' );
			$password->login = $f3->get( 'POST.login' );

			if( $password->save() ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.passwordsaved' ), 0 ) );
			} else {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.passwordsaveerr' ), 1 ) );
			}
			if( isset( $password->folder_id ) )
				$f3->reroute( '/folder/' . $password->folder_id );
			else
				$f3->reroute( '/dashboard' );
		}
	}


}
