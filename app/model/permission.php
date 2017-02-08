<?php
namespace Model;

class Permission extends Base {
  protected $_dbtable = "permissions";

  public function __construct( $id=false ) {
    parent::__construct();
  }

}
