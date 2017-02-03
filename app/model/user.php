<?php
namespace Model;

class User extends Base {
  protected $_dbtable = "users";
  public $newpassword = false;

  public function __construct( $id=false ) {
      parent::__construct();
      if( is_numeric( $id ) ) {
        $this->load( array( 'id=?', $id ) );
     }
  }

  public function loadByLogin( $login=false ) {
      if( is_string( $login ) ) {
        $this->load( array( 'login=?', $login ) );
		return true;
     } else
		 return false;
  }

  public function getAllUsers() {
	  $f3 = \BASE::instance();
	  $query = "SELECT * FROM users LEFT JOIN ( SELECT user_id, GROUP_CONCAT( roles.id ) AS roles FROM users_roles LEFT JOIN roles ON roles.id = users_roles.role_id GROUP BY users_roles.user_id ) tmpRoles ON tmpRoles.user_id = users.id";
	  return $f3->DB->exec( $query );
  }
  
  public function save() {
	  $f3 = \BASE::instance();
	  $f3->logger->write( "save called: newpassword = ".$this->newpassword );
	  if(  $this->newpassword ) {
		  $f3->logger->write( "newpassword set" );
		  $this->password = password_hash( $this->newpassword, PASSWORD_BCRYPT, array( "salt" => $f3->get( 'global_salt' ) ) );
	  }
	  return parent::save();
  }

  public function getSuperuser() {
	$f3 = \BASE::instance();
	$list = $f3->DB->exec( "SELECT * FROM users WHERE id = 2" );

	while( !empty( $list ) ) {
		$result = "";
		$currNode = array_shift( $list );
                $result .= '<option value="'.$currNode['superuser'].'"';
                if( $currNode['superuser'] == 1 ) { $result .= ' selected="selected"'; } else { $result .= ' selected="selected"'; }
		$result .= '>';
		if( $currNode["superuser"] == 1 ) { $result .= 'Yes'; } else { $result .= 'No'; }
		$result .= '</option>';
	}
	return $result;
  }
}
