<?php
namespace Model;

class Config extends Base {
  protected $_dbtable = "users";
  
  public function __construct() {
      parent::__construct();
  }

  public function write_readIni( $file, $content ) {
    if( $f3->get( 'VERB' ) == "POST" ) {
      $data = $f3->get( 'POST' );
      $ini = parse_ini_file( $file, TRUE );
      foreach( $data as $section => $values ) {
	$content .= "[". $section ."]\r\n";
	foreach( $values as $key => $value ) {
	  $content .= $key ."=". $value ."\r\n";
	}
      }
      if( ! $handle = fopen( $file, "w" ) ) {
	$f3->push( 'SESSION.messages', array( $f3->get( 'L.configupdatederr' ), 1 ) );
	return false;
      }
      $success = fwrite( $handle, $content );
      fclose( $handle );
      $f3->push( 'SESSION.messages', array( $f3->get( 'L.configupdated' ), 0 ) );
      $f3->reroute( '/settings/config' );
    }
  }

  public function tz() {
	$f3 = \BASE::instance();
      
	$array = array();
	$ts = time();

	foreach( timezone_identifiers_list() as $k => $zone ) {
	  date_default_timezone_set( $zone );
	  $array[$k]['zone'] = $zone;
	  $array[$k]['diff_from_GMT'] = 'UTC/GMT' . date( 'P', $ts );
	}
	return $array;
  }
}
