<?php

namespace Controller;

class Passwords extends Base {

  public function edit( $f3, $params ) {
    if( $f3->exists( 'POST.name' ) ) {
      $f3->reroute( '/dashboard' );
    } else {
      $f3->logger->write("params: ".print_r($params, true));
      if( is_numeric( $params['id'] ) ) {
        $password = new \Model\Password( $params['id'] );
        if( ! $password->dry() ) {
          foreach( $password->fields() as $key ) {
            $pw[$key] = $password->$key;
          }
          $f3->set( 'pw', $pw );
        }
      }
      $f3->set( 'content', 'passwordform.html' );
    }
  }

}
