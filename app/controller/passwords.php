<?php

namespace Controller;

class Passwords extends Base {

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
				$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree( $ref_matches[1] ) );
			} else {
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

	public function delete( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
			$password = new \Model\Password( $f3->get( 'POST.id' ) );
			if( $password->dry() ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.deletepassworderr'), 1 ) );
			} else {
				$fid = $password->folder_id;
				if( $password->delete() ) {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.deletepasswordok' ), 0 ) );
				} else {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.deletepassworderr' ), 1 ) );
				}
				$f3->reroute( '/folder/' . $fid );
			}
		} else {
			$f3->push( 'SESSION.messages', array( $f3->get( 'L.deletepassworderr' ), 1 ) );
			$f3->reroute( '/dashboard' );
		}
	}

}
