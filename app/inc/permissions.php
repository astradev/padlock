<?php

class Permissions {
	protected static $_instance = null;

	public function getFolderPermission( $folder_id ) {
		if( $this->isSuperuser() ) return 2;
		$f3 = \BASE::instance();
		$folder = new \Model\Folder( $folder_id );
		if( $folder->dry() ) return false;
		if( $this->isSuperuser() ) return 2;
		$query = "SELECT perm FROM ( SELECT permissions.folder_id, perm, folders.lft FROM permissions LEFT JOIN folders ON folders.id = permissions.folder_id WHERE permissions.role_id IN ( SELECT role_id FROM users_roles WHERE user_id=:user_id) AND folder_id IN ( SELECT id FROM folders WHERE lft <= :lft AND rgt >= :rgt ) order by folders.lft desc ) tmp LIMIT 1";
		$result = $f3->DB->exec( $query, array( "user_id" => $f3->get( 'SESSION.user.id' ), "lft" => $folder->lft, "rgt" => $folder->rgt ) );
		return $result[0]['perm'];
	}

	public function isSuperuser() {
		$f3 = \BASE::instance();
		return $f3->get( 'SESSION.user.superuser' );
	}

	public function getUserPermissions() {
		$f3 = \BASE::instance();
		$query = "SELECT folder_id, perm FROM permissions WHERE role_id IN ( SELECT role_id FROM users_roles WHERE user_id = ? )";
		$perms = $f3->DB->exec( $query, $f3->get( 'SESSION.user.id' ) );
		$return = array();
		foreach( $perms as $perm ) {
			$return[$perm['folder_id']] = $perm['perm'];
		}
		return $return;
	}

	public function getAllPermissionsByRole() {
		$f3 = \BASE::instance();
		$query = "SELECT permissions.folder_id AS folder_id, folders.name AS folder_name, permissions.role_id AS role_id, roles.name AS role_name, permissions.perm AS perm FROM permissions LEFT JOIN folders ON folders.id=permissions.folder_id LEFT JOIN roles ON roles.id=permissions.role_id ORDER BY role_id, folder_id";
		$items = $f3->DB->exec( $query );
		$return = array();
		$currentRole = array( 'id' => 0, 'name' => '' );
		$fl = array();

		foreach( $items as $item ) {
			if( $currentRole['id'] != $item['role_id'] ) {
				if( $currentRole['id'] != 0 )
					array_push( $return, array( 'role_id' => $currentRole['id'], 'role_name' => $currentRole['name'], 'perms' => $fl ) );
				$fl = array();
				$currentRole['id'] = $item['role_id'];
				$currentRole['name'] = $item['role_name'];
			}
			array_push( $fl, array( 'id' => $item['folder_id'], 'name' => $item['folder_name'], 'perm' => $item['perm'] ) );
		}
		if( count( $fl ) > 0 )
			array_push( $return, array( 'role_id' => $currentRole['id'], 'role_name' => $currentRole['name'], 'perms' => $fl ) );
		$f3->logger->write("permission arr: ".print_r( $return, true ));
		return $return;
	}

	public function getAllPermissionsByFolder() {
		$f3 = \BASE::instance();
		$query = "SELECT permissions.folder_id AS folder_id, folders.name AS folder_name, permissions.role_id AS role_id, roles.name AS role_name, permissions.perm AS perm FROM permissions LEFT JOIN folders ON folders.id=permissions.folder_id LEFT JOIN roles ON roles.id=permissions.role_id ORDER BY folder_id, role_id";
		return $f3->DB->exec( $query );
	}

	public static function instance()
	{
		if (null === self::$_instance)
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	protected function __clone() {}

	protected function __construct() {}

}
