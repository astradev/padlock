<?php

class Permissions {
	protected static $_instance = null;

	public function getFolderPermission( $folder_id ) {
		$f3 = \BASE::instance();
		$folder = new \Model\Folder( $folder_id );
		if( $folder->dry() ) return false;
		if( $this->isSuperuser() ) return 2;
		$query = "SELECT perm FROM ( SELECT permissions.folder_id, perm, folders.lft FROM permissions LEFT JOIN folders ON folders.id = permissions.folder_id WHERE permissions.role_id IN ( SELECT role_id FROM users_roles WHERE user_id=:user_id) AND folder_id IN ( SELECT id FROM folders WHERE lft <= :lft AND rgt >= :rgt ) order by folders.lft desc ) tmp LIMIT 1";
		//$query = "SELECT CASE WHEN COUNT(*) > 0 THEN MIN( perm ) ELSE 0 END AS permission FROM ( SELECT folder_id, role_id, perm FROM permissions WHERE role_id IN ( SELECT role_id FROM users_roles WHERE user_id=:user_id ) AND folder_id IN ( SELECT id FROM folders WHERE lft < :lft AND rgt > :rgt UNION SELECT :folder_id ) ) perms";
		$result = $f3->DB->exec( $query, array( "user_id" => $f3->get( 'SESSION.user.id' ), "lft" => $folder->lft, "rgt" => $folder->rgt ) );
		return $result[0]['perm'];
	}

	public function isSuperuser() {
		$f3 = \BASE::instance();
		return $f3->get( 'SESSION.user.superuser' );
	}

	public function getAllPermissions() {
		$f3 = \BASE::instance();
		$query = "SELECT folder_id, perm FROM permissions WHERE role_id IN ( SELECT role_id FROM users_roles WHERE user_id = ? )";
		$perms = $f3->DB->exec( $query, $f3->get( 'SESSION.user.id' ) );
		$return = array();
		foreach( $perms as $perm ) {
			$return[$perm['folder_id']] = $perm['perm'];
		}
		return $return;
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
