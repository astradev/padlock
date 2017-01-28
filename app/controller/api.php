<?php
namespace Controller;

class API {
  public function foldertree( $f3, $params ) {
    print \TreeBuilder::instance()->generateTree();
  }
  public function testout( $f3, $params ) {
	  print '<pre>';
	  print_r( \TreeBuilder::instance()->generateOptionTree() );
	  print '</pre>';
  }
}
