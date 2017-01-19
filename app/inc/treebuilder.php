<?php

class TreeBuilder {
	protected static $_instance = null;

	public function generateTree() {
		$f3 = \BASE::instance();
		$query = "SELECT folders.id, folders.name, (COUNT(parent.name) - 1) as depth FROM folders"
				." CROSS JOIN folders AS parent WHERE folders.lft BETWEEN parent.lft AND parent.rgt"
				." AND folders.id IN (SELECT folder_id from permissions WHERE role_id IN ( SELECT role_id FROM users WHERE id=? ) )"
				." GROUP BY folders.name ORDER BY folders.id";
		$tree = $f3->DB->exec( $query, $f3->get( 'SESSION.user.id' ) );
		$f3->logger->write( "tree: ".print_r($tree, true));

		$result = '';
		$currDepth = -1;
		while( ! empty( $tree ) ) {
			$currNode = array_shift( $tree );
			if( $currNode['depth'] > $currDepth ) {
				$result .= '<ul>';
			}
			if( $currNode['depth'] < $currDepth ) {
				$result .= str_repeat( '</ul>', $currDepth - $currNode['depth'] );
			}
			$result .= '<li>' . $currNode['name'] . '</li>';
			$currDepth = $currNode['depth'];
			if( empty( $tree ) ) {
				$result .= str_repeat( '</ul>', $currDepth + 1 );
			}
		}
		return $result;
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
