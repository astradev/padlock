<?php
namespace Model;

class Permission extends Base {
  protected $_dbtable = "permissions";

  public function __construct( $id=false ) {
    parent::__construct();
  }

  public function sort( $file, $content ) {
    if( ! $handle = fopen( $file, "w" ) ) {
      $f3->push( 'SESSION.messages', array( $f3->get( 'L.configupdatederr' ), 1 ) );
      return false;
    }

    $success = fwrite( $handle, $content );
    fclose( $handle );
  }
}
