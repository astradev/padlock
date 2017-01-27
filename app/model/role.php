<?php
namespace Model;

class User extends Base {
  protected $_dbtable = "roles";

  public function __construct( $id=false ) {
      parent::__construct();
      if( is_numeric( $id ) ) {
        $this->load( array( 'id=?', $id ) );
     }
  }

  public function getAllRoles() {
	  return $this->find();
  }
}
