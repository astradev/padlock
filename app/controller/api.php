<?php
namespace Controller;

class API {
  public function folderTreJSON( $f3, $params ) {
    print \TreeBuilder::instance()->loadTree();
  }
  public function foldertree( $f3, $params ) {
    print \TreeBuilder::instance()->generateTree();
  }
}
