<?php

class PermissionHelper {
	protected static $_instance = null;

	public function getFolderPermission( $folder_id ) {
		$f3 = \BASE::instance();
		$folder = new \Model\Folder( $folder_id );
		if( $folder->dry() ) return false;
		if( $this->isSuperuser() ) return 2;
		$query = "SELECT CASE WHEN COUNT(*) > 0 THEN MIN( perm ) ELSE 0 END AS permission FROM ( SELECT folder_id, role_id, perm FROM permissions WHERE role_id IN ( SELECT role_id FROM users_roles WHERE user_id=:user_id ) AND folder_id IN ( SELECT id FROM folders WHERE lft < :lft AND rgt > :rgt UNION SELECT :folder_id ) ) perms";
		$result = $f3->DB->exec( $query, array( "user_id" => $f3->get( 'SESSION.user.id' ), "lft" => $folder->lft, "rgt" => $folder->rgt, "folder_id" => $folder->id ) );
		return $result[0]['permission'];
	}

	public function isSuperuser() {
		return $f3->get( 'SESSION.user.superuser' );
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
