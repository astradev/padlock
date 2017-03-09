<?php
namespace Model;

class Config extends Base {
  protected $_dbtable = "users";
  
  public function __construct() {
      parent::__construct();
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
