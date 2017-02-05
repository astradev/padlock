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
		$folder = new \Model\Folder();
		$role = new \Model\Role();

		$f3->set( 'permissions', \Permissions::instance()->getAllPermissions() );
		$f3->set( 'folders', $folder->find() );
		$f3->set( 'roles', $role->find() );
		$f3->set( 'optionFolders', \TreeBuilder::instance()->generateOptionTree() );
		$f3->set( 'section', 'permissions.html' );
	}

}

