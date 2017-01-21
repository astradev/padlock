<?php
namespace Model;

class Folder extends Base {
	protected $_dbtable = "folders";
	public $parent_id;

	public function __construct( $id=false ) {
		parent::__construct();
		if( is_numeric( $id ) ) {
			$this->load( array( 'id=?', $id ) );
		}
	}

	public function getPasswords() {
                $f3 = \BASE::instance();
		if( ! $this->dry() ) {
			$query = "SELECT CASE WHEN COUNT(*) > 0 AND min( perm )<>0 THEN 1 ELSE 0 END AS permission FROM ( SELECT folder_id, role_id, perm FROM permissions WHERE role_id IN ( SELECT role_id FROM users_roles WHERE user_id=:user_id ) AND folder_id IN ( SELECT id FROM folders WHERE lft < :lft AND rgt > :rgt UNION SELECT :folder_id ) ) perms";
                        $result = $f3->DB->exec( $query, array( "user_id" => $f3->get( 'SESSION.user.id' ), "lft" => $this->lft, "rgt" => $this->rgt, "folder_id" => $this->id ) );
			if( $result[0]['permission'] == 1 ) {
				return $f3->DB->exec( "SELECT * FROM passwords WHERE folder_id = ?", $this->id );
			} else {
                          // evntl message
                          return false;
                        }
		} else {
			return false;
		}
	}

	public function save() {
		$f3 = \BASE::instance();
		$f3->clear( 'COOKIE.padlock-tree' );
		if( ! empty( $this->id ) && ! empty( $this->lft ) && ! empty( $this->rgt ) ) {
			return $ret = $f3->DB->exec( "UPDATE folders SET name=? WHERE id=?", array( $this->name, $this->id ) );
		} elseif( is_numeric( $this->parent_id ) ) {
			$f3->logger->write("model parent_id is set");
			$parentFolder = new \Model\Folder( $this->parent_id );
			if( ! $parentFolder->dry() ) {
				return $f3->DB->exec(
						array(
							"UPDATE folders SET rgt = rgt+2 WHERE rgt > :start",
							"UPDATE folders SET lft = lft+2 WHERE lft > :start",
							"INSERT INTO folders SET name = :name, lft = :lft, rgt = :rgt",
							),
						array(
							array( "start" => $parentFolder->lft ),
							array( "start" => $parentFolder->lft ),
							array( "name" => $this->name, "lft" => ( $parentFolder->lft + 1 ), "rgt" => ( $parentFolder->lft + 2 ) )
							)
						);

			} else
				trigger_error( "Saving changed failed: parent folder could not be found. ID=".$this->parent_id );
		} elseif( ! empty( trim( $this->name ) ) ) {
			$maxid = $f3->DB->exec( "SELECT MAX(rgt) as maxid FROM folders" );
			return $f3->DB->exec( "INSERT INTO folders SET name=:name, lft=:lft, rgt=:rgt", array( 'name' => $this->name, 'lft' => $maxid[0]['maxid']+1, 'rgt' => $maxid[0]['maxid']+2 ) );
		} else {
			return false;
		}
	}

}
