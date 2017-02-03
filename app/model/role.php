<?php
namespace Model;

class Role extends Base {
  protected $_dbtable = "roles";

  public function __construct( $id=false ) {
      parent::__construct();
      if( is_numeric( $id ) ) {
        $this->load( array( 'id=?', $id ) );
     }
  }

  public function getAllRoles() {
	  $f3 = \BASE::instance();
	  $result = $this->find();
	  $roles = array();
	  foreach( $result as $item ) {
		  $roles[$item->id] = $item->name;
	  }
	  return $roles;
  }

  public function getAllRolesWithUsers() {
	  $f3 = \BASE::instance();
	  return $f3->DB->exec( "SELECT roles.id, roles.name, anzusers.users FROM roles LEFT JOIN ( SELECT COUNT(user_id) as users, role_id FROM users_roles GROUP BY role_id ) anzusers ON anzusers.role_id = roles.id" );
  }

}
