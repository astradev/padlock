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
    if( ! $this->dry() ) {
      if( \PermissionHelper::instance()->hasPermission( $this->id ) > 0 ) {
        return $f3->DB->exec( "SELECT * FROM users WHERE id = ?", $this->id );
      } else {
        // evntl message
        return false;
      }
    } else {
      return false;
    }
  }
}
