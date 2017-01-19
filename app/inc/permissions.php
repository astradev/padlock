<?php

class Permissions {
	protected static $_instance = null;

	public function getFolders() {
		$f3 = \BASE::instance();
		return array();
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
