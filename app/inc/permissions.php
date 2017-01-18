<?php

class Permissions {
	protected static $_instance = null;

	public function getFolders() {
		$f3 = \BASE::instance();
		return $f3->DB->exec(
				"select * from folders where id IN ( SELECT folder_id FROM permissions where r=true and role_id in ( SELECT role_id FROM users_roles WHERE user_id = ? ) ) ORDER BY parent_id",
				$f3->get( 'SESSION.user.id' )
				);
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
