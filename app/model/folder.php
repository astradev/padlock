<?php
namespace Model;

class Folder extends Base {
	protected $_dbtable = "folders";
	protected $parent_id;

	public function __construct( $id=false ) {
		parent::__construct();
		if( is_int( $id ) ) {
			$this->load( array( 'id=?', $id ) );
		}
	}

	public function save() {
		$f3 = \BASE::instance();
		if( ! empty( $this->id ) && ! empty( $this->lft ) && ! empty( $this->rgt ) ) {
			return $ret = $f3->DB->exec( "UPDATE folders SET name=? WHERE id=?", array( $this->name, $this->id ) );
		} elseif( is_int( $this->parent_id ) ) {
			$parentFolder = new \Model\Folder( $this->parent_id );
			if( ! $parentFolder->dry() ) {
				return $f3->DB->exec(
						array(
							"UPDATE folders SET rgt=rgt+2 WHERE rgt>:start",
							"UPDATE folders SET lft=lft+2 WHERE lft>:start",
							"INSERT INTO folders SET name=:name, lft=:lft, rgt=:rgt",
							),
						array(
							"start" => $parentFolder->rgt,
							"lft" => $parentFolder->rgt + 1,
							"rgt" => $parentFolder->rgt + 2,
							)
						);

			} else
				trigger_error( "Saving changed failed: parent folder could not be found. ID=".$this->parent_id );
		} else {
			$maxid = $f3->DB->exec( "SELECT MAX(rgt) as maxid FROM folders" );
			return $f3->DB->exec( "INSERT INTO folders SET name=:name, lft=:lft, rgt=:rgt", array( 'name' => $this->name, 'lft' => $maxid[0]['maxid']+1, 'rgt' => $maxid[0]['maxid']+2 ) );
		}
	}

}
