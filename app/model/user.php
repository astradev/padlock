<?php
namespace Model;

class User extends Base {
  protected $_dbtable = "users";

  public function __construct( $login=false ) {
      parent::__construct();
      if( is_string( $login ) ) {
        $this->load( array( 'login=?', $login ) );
     }
  }

  public function getUsers() {
    $f3 = \BASE::instance();
	return false;
  }
}
