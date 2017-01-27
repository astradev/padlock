<?php

class Cookies {
	protected static $_instance = null;

	public function clearTree() {
		$f3 = \BASE::instance();
		setcookie( 'padlock_tree', NULL, -1, $f3->get( 'BASE' ).'/' );
	}

	public static function instance() {
		if (null === self::$_instance)
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	protected function __clone() {}

	protected function __construct() {}

}
