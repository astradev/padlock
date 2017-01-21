<?php

namespace Controller;

class Passwords extends Base {

  public function add( $f3, $params ) {
    if( $f3->exists( 'POST.name' ) ) {
      $f3->reroute( '/dashboard' );
    } else {
      $f3->set( 'content', 'newpassword.html' );
    }
  }

  public function getPasswords( $folder_id ) {
	  $folder = new \Model\Folder( $folder_id );
	  if( ! $folder->dry() ) {
		  $query = "SELECT CASE WHEN COUNT(*) > 0 AND min( perm )<>0 THEN 1 ELSE 0 END AS permission FROM ( SELECT folder_id, role_id, perm FROM permissions WHERE role_id IN ( SELECT role_id FROM users_roles WHERE user_id=:user_id ) AND folder_id IN ( SELECT id FROM folders WHERE lft < :lft AND rgt > :rgt ) ) perms";
		  if( $f3->DB->exec( $query, array( "user_id" => $f3->get( 'SESSION.user.id' ) ) === (int)1 ) ) {
			  return $f3->DB->exec( "SELECT * FROM passwords WHERE folder_id = ?", $folder_id );
		  }
	  } else {
		  return false;
	  }
  }

}
