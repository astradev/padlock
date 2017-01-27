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
      if( \Permissions::instance()->getFolderPermission( $this->id ) > 0 ) {
        return $f3->DB->exec( "SELECT * FROM passwords WHERE folder_id = ?", $this->id );
      } else {
		  $f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
		  return false;
      }
    } else {
      return false;
    }
  }

  public function save() {
    $f3 = \BASE::instance();
	if( $this->parent_id == 0 && ! \Permissions::instance()->isSuperuser() ) {
		return false;
	} elseif( \Permissions::instance()->getFolderPermission( $this->parent_id ) < 2 ) {
		$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
		return false;
	}
    if( ! empty( $this->id ) && ! empty( $this->lft ) && ! empty( $this->rgt ) ) {
      return $ret = $f3->DB->exec( "UPDATE folders SET name=:name WHERE id=:id", array( "name" => $this->name, "id" => $this->id ) );
    } elseif( is_numeric( $this->parent_id ) && ! empty( trim( $this->name ) ) ) {
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
    } elseif( empty( $this->parent_id ) && ! empty( trim( $this->name ) ) ) {
      $maxid = $f3->DB->exec( "SELECT MAX(rgt) as maxid FROM folders" );
      return $f3->DB->exec( "INSERT INTO folders SET name=:name, lft=:lft, rgt=:rgt", array( 'name' => $this->name, 'lft' => $maxid[0]['maxid']+1, 'rgt' => $maxid[0]['maxid']+2 ) );
    } else {
      return false;
    }
  }

  public function delete() {
	if( \Permissions::instance()->getFolderPermission( $this->id ) < 2 ) {
		$f3 = \BASE::instance();
		$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
		return false;			
	}
    $f3 = \BASE::instance();
    if( ! empty( $this->id ) ) {
		return $f3->DB->exec(
				array(
				"DELETE FROM permissions WHERE folder_id IN ( SELECT id FROM ( SELECT id FROM folders WHERE lft >= :lft and rgt <= :rgt ) ids )",
				"DELETE FROM passwords WHERE folder_id IN ( SELECT id FROM ( SELECT id FROM folders WHERE lft >= :lft and rgt <= :rgt ) ids )",
				"DELETE FROM folders WHERE id IN ( SELECT id FROM ( SELECT id FROM folders WHERE lft >= :lft and rgt <= :rgt ) ids )"
				),
				array(
					array( "lft" => $this->lft, "rgt" => $this->rgt ),
					array( "lft" => $this->lft, "rgt" => $this->rgt ),
					array( "lft" => $this->lft, "rgt" => $this->rgt )
				)
				);
    }
	return false;
  }

}
