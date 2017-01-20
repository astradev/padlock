<?php

class TreeBuilder {
	protected static $_instance = null;

	public function generateTree() {
		$f3 = \BASE::instance();
		$query = "SELECT folders.id, folders.name, (COUNT(parent.name) - 1) as depth FROM folders"
				." CROSS JOIN folders AS parent WHERE folders.lft BETWEEN parent.lft AND parent.rgt"
				." AND folders.id IN ( SELECT fc.id FROM permissions"
				." LEFT JOIN folders AS fb ON fb.id=permissions.folder_id"
				." LEFT JOIN folders AS fc ON fc.lft BETWEEN fb.lft AND fb.rgt WHERE permissions.perm > 0 AND role_id IN ( SELECT role_id FROM users WHERE id=:id ) )"
				." AND folders.id NOT IN ( SELECT fc.id FROM permissions"
				." LEFT JOIN folders AS fb ON fb.id=permissions.folder_id"
				." LEFT JOIN folders AS fc ON fc.lft BETWEEN fb.lft AND fb.rgt WHERE permissions.perm = 0 AND role_id IN ( SELECT role_id FROM users WHERE id=:id ) )"
				." GROUP BY folders.name ORDER BY folders.lft";
		$tree = $f3->DB->exec( $query, array( "id" => $f3->get( 'SESSION.user.id' ) ) );

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
