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
    $query = "SELECT CASE WHEN COUNT(*) > 0 AND min( perm )<>0 THEN 1 ELSE 0 AS permission FROM (
      SELECT perm FROM permissions WHERE role_id=(SELECT role_id FROM users_roles WHERE user_id=".$f3->get( 'SESSION.user.id' ).") 
      AND folder_id IN ( SELECT id FROM folders WHERE lft < ".$folder->lft." AND rgt > ".$folder->rgt." ) )";

  }

}
