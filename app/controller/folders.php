<?php

namespace Controller;

class Folders extends Base {

	protected $response;

	public function __construct() {
		$f3 = \BASE::instance();
		$f3->set( 'formFolder', new \Model\Folder() );
		$f3->set( 'formPassword', new \Model\Password() );
	}

	public function create_edit( $f3, $params ) {
		$f3->set( 'content', 'folderform.html' );
		$folder = new \Model\Folder();

		if( isset( $params['id'] ) && is_numeric( $params['id'] ) ) {
			$folder->load( array( "id=?", $params['id'] ) );
			if( ! $folder->dry() ) {
				$f3->set( 'formFolder', $folder );
				$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree( $params['id'] ) );
			}
		} else {
			$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree() );
		}

		if( $f3->get( 'VERB' ) == "POST") {
			if( $f3->exists( 'POST.id' ) && ! is_numeric( $f3->get( 'POST.id' ) ) ) {
				$f3->push( 'SESSION.messages', array( "FEHLER 1", 1 ) );
				return;
			} elseif ( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
				$folder->reset();
				$folder->load( array( 'id=?', $f3->get( 'POST.id' ) ) );
				if( $folder->dry() ) {
					$f3->push( 'SESSION.messages', array( "FEHLER 2", 1 ) );
					$f3->clear( 'formFolder' );
					$f3->set( 'formFolder', $folder );
					$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree( $f3->get( 'POST.id' ) ) );
				}
			}
			if( ! $f3->exists( 'POST.parent_id' ) || ! is_numeric( $f3->get( 'POST.parent_id' ) ) ) {
				$f3->push( 'SESSION.messages', array( "FEHLER 3", 1 ) );
				return;
			} else {
				if( $f3->get( 'POST.parent_id' ) > 0 ) {
					$folder->parent_id = $f3->get( 'POST.parent_id' );
				}
			}
			if( ! $f3->exists( 'POST.name' ) || empty( trim( $f3->get( 'POST.name' ) ) ) ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.invalidfoldername' ), 1 ) );
				return;
			} else {
				$folder->name = $f3->get( 'POST.name' );
			}

			if( $folder->save() ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.foldersaved' ), 0 ) );
				$f3->set( 'SESSION.treeUpdateTrigger', "true" );
				if( isset( $params['id'] ) )
					$f3->reroute( '/folder/' . $params['id'] );
				else
					$f3->reroute( '/dashboard' );
			} else {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.foldersaveerr' )."NANANANANANANANA", 1 ) );
			}
		}
	}

	public function delete( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
			$folder = new \Model\Folder( $f3->get( 'POST.id' ) );
			if( $folder->dry() ) {
				$f3->push( 'SESSION.messages', array( $f3->get( 'L.deletefoldererr'), 1 ) );
			} else {
				$folder->delete();
				$f3->push( 'SESSION.messages', array( "Folder was successfully deleted", 0 ) );
				$f3->set( 'SESSION.treeUpdateTrigger', "true" );
			}
		} else {
			$f3->push( 'SESSION.messages', array( "Could not delete folder: No valid folder id given", 1 ) );
		}

		$f3->reroute( '/dashboard' );
	}

	public function show( \Base $f3, $params ) {
		if( ! isset( $params['id'] ) || ! is_numeric( $params['id'] ) ) {
			$folderList = \TreeBuilder::instance()->getFolderList();
			$f3->logger->write("default folder: ".print_r( $folderList , true ) );
			$params['id'] = $folderList[0]['id'];
		}
		$folder = new \Model\Folder( $params['id'] );
		$pws = $folder->getPasswords();

		$f3->set( 'folderTree', \TreeBuilder::instance()->generateTree() );
		$f3->set( 'folderList', \TreeBuilder::instance()->getFolderList() );
		$f3->set( 'folder', $folder );
		$f3->set( 'passwords', $folder->getPasswords() );
		$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree( $params['id'] ) );
		$f3->set( 'content', 'overview.html' );
	}
}

