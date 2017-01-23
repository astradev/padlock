<?php

namespace Controller;

class Folders extends Base {

	protected $response;

        public function edit( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
			$folder = new \Model\Folder( $f3->get( 'POST.id' ) );
                        if( $folder->dry() || empty( trim( $f3->get( 'POST.name' ) ) ) ) {
                          $f3->push( 'SESSION.messages', array( $f3->get( 'L.couldnoteditfolder' ), 1 ) );
                          $f3->set( 'content', 'folderform.html' );
                        } else {
                          $folder->name = $f3->get( 'POST.name' );
                          if( $folder->save() ) {
                            $f3->push( 'SESSION.messages', array( $f3->get( 'L.folderedited' ), 0 ) );
                            if( isset( $params['id'] ) )
                              $f3->reroute( '/folder/' . $params['id'] );
                            else
                              $f3->reroute( '/dashboard' );
                          } else {
                            $f3->push( 'SESSION.messages', array( $f3->get( 'L.couldnoteditfolder' ), 1 ) );
                            $f3->set( 'content', 'folderform.html' );
                          }
                        }
                } elseif( $f3->exists( 'POST.name' ) ) {
			$folder = new \Model\Folder();
			$folder->reset();
			$folder->name = $f3->get( 'POST.name' );
			if( $f3->exists( 'POST.parent_id' ) && is_numeric( $f3->get( 'POST.parent_id' ) ) ) {
				$folder->parent_id = $f3->get( 'POST.parent_id' );
			}
			if( $folder->save() ) {
				$f3->push( "SESSION.messages", array( $f3->get( 'L.foldercreatesuccessful' ), 0 ) );
			} else {
				$f3->push( "SESSION.messages", array( $f3->get( 'L.foldercreateerror' ), 1 ) );
			}
			$f3->reroute( '/dashboard' );
		} else {
			$f3->set( 'content', 'folderform.html' );
                }
	}

	public function delete( $f3, $params ) {
		if( $f3->exists( 'POST.id' ) && is_numeric( $f3->get( 'POST.id' ) ) ) {
			$folder = new \Model\Folder( $params['id'] );
			$folder->delete();
			$f3->SESSION->messages[] = array( "Folder was successfully deleted", 0 );
                        if( isset( $params['id'] ) )
                          $f3->reroute( '/folder/' . $params['id'] );
                        else
                          $f3->reroute( '/dashboard' );
		} else {
			$f3->SESSION->messages[] = array( "Could not delete folder: No valid folder id given", 1 );
			$f3->reroute( '/dashboard' );
		}
	}

	public function show( \Base $f3, $params ) {
		if( ! isset( $params['id'] ) || ! is_numeric( $params['id'] ) ) {
			$folderList = \TreeBuilder::instance()->loadTree();
			$params['id'] = $folderList[0]['id'];
		}
		$folder = new \Model\Folder( $params['id'] );
		$pws = $folder->getPasswords();

		$f3->set( 'folderTree', \TreeBuilder::instance()->generateTree() );
		$f3->set( 'folderList', \TreeBuilder::instance()->loadTree() );
		$f3->set( 'folderID', $folder->id );
		$f3->set( 'passwords', $folder->getPasswords() );
		$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree( $params['id'] ) );
		$f3->set( 'content', 'overview.html' );
	}
}

