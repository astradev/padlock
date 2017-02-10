<?php
namespace Model;

class User extends Base {
  protected $_dbtable = "users";
  public $newpassword = false;
  public $roles = array();
  public $newroles = NULL;

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

  public function load( $filter=NULL, array $options=NULL, $ttl=0 ) {
	  parent::load( $filter, $options, $ttl );
	  $this->roles = $this->getRoles();
  }

  public function getRoles( $id=false ) {
	  $f3 = \BASE::instance();
	  $id = ( is_numeric( $id ) ? $id : ( is_numeric( $this->id ) ? $this->id : $f3->get( 'SESSION.user.id' ) ) );
	  return $f3->DB->exec( "SELECT roles.id, roles.name FROM roles WHERE roles.id IN ( SELECT users_roles.role_id FROM users_roles WHERE users_roles.user_id = ? )", $id );
  }

  public function getAllUsers() {
	  $f3 = \BASE::instance();
	  $query = "SELECT * FROM users LEFT JOIN ( SELECT user_id, GROUP_CONCAT( roles.id ) AS roles FROM users_roles LEFT JOIN roles ON roles.id = users_roles.role_id GROUP BY users_roles.user_id ) tmpRoles ON tmpRoles.user_id = users.id";
	  return $f3->DB->exec( $query );
  }
  
  public function save() {
	  $f3 = \BASE::instance();
	  if(  $this->newpassword ) {
		  $this->password = password_hash( $this->newpassword, PASSWORD_BCRYPT, array( "salt" => $f3->get( 'GLOBAL_SALT' ) ) );
	  }
	  $ret = parent::save();
	  if( is_array( $this->newroles ) ) {
		  if( ! is_numeric( $this->id ) ) $this->load( array( 'login=?', $this->login ) );
		  $this->setNewRoles( $this->newroles );
	  }
	  return $ret;
  }

  public function setNewRoles( $roles ) {
	  $f3 = \BASE::instance();
	  $f3->DB->begin();
	  if( is_numeric( $this->id ) ) 
		  $f3->DB->exec( "DELETE FROM users_roles WHERE user_id = ?", $this->id );
	  foreach( $roles as $role ) {
		  if( is_numeric( $role ) )
			  $f3->DB->exec( "INSERT INTO users_roles VALUES ( :uid, :rid )", array( 'uid' => $this->id, 'rid' => $role ) );
	  }
	  $f3->DB->commit();
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
