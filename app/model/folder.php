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

	public function save() {
		$f3 = \BASE::instance();
		$f3->logger->write("model parent_id: ".$this->parent_id);
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
		} else {
			$maxid = $f3->DB->exec( "SELECT MAX(rgt) as maxid FROM folders" );
			return $f3->DB->exec( "INSERT INTO folders SET name=:name, lft=:lft, rgt=:rgt", array( 'name' => $this->name, 'lft' => $maxid[0]['maxid']+1, 'rgt' => $maxid[0]['maxid']+2 ) );
		}
	}

}
