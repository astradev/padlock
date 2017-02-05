<?php

class TreeBuilder {
	protected static $_instance = null;

	protected $tmpIncrementer;

	private function getFolderListAll() {
		$f3 = \BASE::instance();
		$query = "SELECT folders.id, folders.name, (COUNT(parent.name) - 1) as depth FROM folders CROSS JOIN folders AS parent WHERE folders.lft BETWEEN parent.lft AND parent.rgt GROUP BY folders.name ORDER BY folders.lft";
		return $f3->DB->exec( $query );
	}

	public function processSubtree( $allowed, &$list, &$permissions, $depthOffset=0 ) {
		$f3 = \BASE::instance();
		$listSection = array();
		while( $this->tmpIncrementer < count( $list ) ) {
			$thisPerm = $allowed;
			if( in_array( $list[$this->tmpIncrementer]['id'], array_keys( $permissions ) ) ) {
			  $thisPerm = $permissions[$list[$this->tmpIncrementer]['id']];
			}
			if( $thisPerm ) {
				$listSection[] = array( 'id' => $list[$this->tmpIncrementer]['id'], 'name' => $list[$this->tmpIncrementer]['name'], 'depth' => $depthOffset, 'perm' => $thisPerm );
			}
			if( isset( $list[$this->tmpIncrementer+1] ) ) {
				$myIndex = $this->tmpIncrementer;
				if( $list[$this->tmpIncrementer+1]['depth'] > $list[$this->tmpIncrementer]['depth'] ) {
					$this->tmpIncrementer++;
					$listSection = array_merge( $listSection, $this->processSubtree( $thisPerm, $list, $permissions, ($thisPerm)?$depthOffset+1:$depthOffset ) );
					if( $list[$myIndex]['depth'] > $list[$this->tmpIncrementer]['depth'] ) {
						return $listSection;
					}
				} elseif( $list[$this->tmpIncrementer+1]['depth'] == $list[$this->tmpIncrementer]['depth'] ) {
					$this->tmpIncrementer++;
				} else {
					$this->tmpIncrementer++;
					return $listSection;
				}
			} else {
				return $listSection;
			}
		}
		return $listSection;
	}

	public function getFolderList() {
		$list = $this->getFolderListAll();

		if( \Permissions::instance()->isSuperuser() ) {
			for( $i = 0; $i < count( $list ); $i++ ) {
				$list[$i]['perm'] = 2;
			}
			return $list;
		} else {
			$permissions = \Permissions::instance()->getUserPermissions();
			$this->tmpIncrementer = 0;
			return $this->processSubtree( false, $list, $permissions, $this->tmpIncrementer, 0 );
		}
	}

	public function generateTree() {
		$f3 = \BASE::instance();
		$tree = $this->getFolderList();
		$result = '';
		$currDepth = -1;
		while( ! empty( $tree ) ) {
			$currNode = array_shift( $tree );
			if( $currNode['depth'] > $currDepth ) {
				$result .= '<ul >';
			}
			if( $currNode['depth'] < $currDepth ) {
				$result .= str_repeat( '</ul>', $currDepth - $currNode['depth'] );
			}
			$result .= '<li class="isFolder"><a href="' . $f3->get( 'BASE' ) . '/folder/' . $currNode['id'] . '">' . $currNode['name'] . '</a>';
			$currDepth = $currNode['depth'];
			if( empty( $tree ) ) {
				$result .= str_repeat( '</ul>', $currDepth + 1 );
			}
		}
		return $result;
	}

	public function generateOptionTree( $selected = false ) {
		$f3 = \BASE::instance();
		$tree = $this->getFolderList();

		$result = '<option value="0"';
		if( ! $selected ) $result .= ' selected="selected"';
		$result .= '>- (kein)</option>';
		while( !empty( $tree ) ) {
			$currNode = array_shift( $tree );
                        $result .= '<option value="'.$currNode['id'].'"';
                        if( $selected == $currNode['id'] ) $result .= ' selected="selected"';
			if( $currNode['perm'] == 1 ) $result .= ' data-icon="fa-lock"';
			$result .= '>'.str_repeat( '–', $currNode['depth']).' '.$currNode['name'].'</option>';
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
