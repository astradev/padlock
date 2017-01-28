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

  public function getAllUsers() {
	  return $this->find();
  }

  public function getSuperuser() {
	$f3 = \BASE::instance();
	$list = $f3->DB->exec( "SELECT * FROM users WHERE id = 2" );

	while( !empty( $list ) ) {
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
