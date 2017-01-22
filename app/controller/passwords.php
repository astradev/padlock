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
			} else {
				$f3->set( 'content', 'passwordform.html' );
			}
		} else {
			if( is_numeric( $params['id'] ) ) {
				$password = new \Model\Password( $params['id'] );
				if( ! $password->dry() &&  ) {
					if( \PermissionHelper::instance()->hasPermission( $password->folder_id ) < 2 ) {
						$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
						$f3->reroute( '/dashboard ');
					} else {
						foreach( $password->fields() as $key ) {
							$pw[$key] = $password->$key;
						}
						$f3->set( 'pw', $pw );
					}
				} else {
					$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopwid' ), 1 , true ) );
				}
			}
			$f3->set( 'content', 'passwordform.html' );
		}
	}

}



public function edit( $f3, $params ) {
	if( $f3->exists( 'POST.name' ) ) {
		$folder = new \Model\Folder();
		$folder->reset();
		$folder->name = $f3->get( 'POST.name' );
		$f3->logger->write( "POST parent_id exists: ".$f3->get( 'POST.parent_id' ) );
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
