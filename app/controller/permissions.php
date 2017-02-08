<?php

namespace Controller;

class Permissions extends Backend {

	public function __construct() {
		parent::__construct();
		$f3 = \BASE::instance();
		$f3->set( 'component', 'permissions' );
	}

	public function show( \Base $f3, $params ) {
//		if( ! in_array( $params['mode'], array( 'byrole', 'byfolder' ) ) ) {
//			$f3->reroute( '/settings/permissions/byfolder' );
//		}
		$role = new \Model\Role();

		$f3->set( 'permissions', \Permissions::instance()->getAllPermissionsByRole() );
		$f3->set( 'roles', $role->find() );
		$f3->set( 'optionFolders', \Treebuilder::instance()->generateOptionTree() );
		$f3->set( 'section', 'permissions.html' );
	}

	public function add( \Base $f3, $params ) {
		$folder = new \Model\Folder( $f3->get( 'POST.folder' ) );
		if( $folder->dry() ) {
			$f3->push( 'SESSION.messages', array( "Folder does not exist.", 1 ) );
			$f3->reroute( '/settings/permissions' );
		}
		$role = new \Model\Role( $f3->get( 'POST.role' ) );
		if( $role->dry() ) {
			$f3->push( 'SESSION.messages', array( "Role does not exist.", 1 ) );
			$f3->reroute( '/settings/permissions' );
		}
		$perm = $f3->get( 'POST.perm' );
		if( $perm != 0 && $perm != 1 && $perm != 2 ) {
			$f3->push( 'SESSION.messages', array( "Unknown permission.", 1 ) );
			$f3->reroute( '/settings/permissions' );
		}
		$permission = new \Model\Permission();
		$permission->folder_id = $folder->id;
		$permission->role_id = $role->id;
		$permission->perm = $perm;
		if( $permission->save() ) {
			$f3->push( 'SESSION.messages', array( "Permission successfull saved.", 0 ) );
			$f3->reroute( '/settings/permissions' );
		} else {
			$f3->push( 'SESSION.messages', array( "Error while saving permission.", 1 ) );
			$f3->reroute( '/settings/permissions' );
		}
	}

	public function delete( \Base $f3, $params ) {
		$permission = new \Model\Permission();
		$permission->load( array( "folder_id = :fid and role_id = :rid", ':fid' => $f3->get( 'POST.folder' ), ':rid' => $f3->get( 'POST.role' ) ) );
		if( $permission->dry() ) {
			$f3->push( 'SESSION.messages', array( "Permission could not be found.", 1 ) );
			$f3->reroute( '/settings/permissions' );
		} else {
			if( $permission->erase() ) {
				$f3->push( 'SESSION.messages', array( "Permission successfully deleted.", 0 ) );
				$f3->reroute( '/settings/permissions' );
			} else {
				$f3->push( 'SESSION.messages', array( "Permission could not be deleted.", 1 ) );
				$f3->reroute( '/settings/permissions' );
			}
		}
	}

}
