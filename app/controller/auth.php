<?php

namespace Controller;

class Auth {

  protected $response;

  public function beforeroute($f3) {
    $this->response = new \View\Frontend();
  }

  static public function isLoggedIn() {
    $f3 = \Base::instance();
    if ($f3->exists('SESSION.user.id')) {
      $user = new \Model\User();
      $user->load( array( 'id=?', $f3->get( 'SESSION.user.id' ) ) );
      if( !$user->dry() ) {
        return true;
      }
    }
    return false;
  }

  public function login( $f3,$params ) {
    if( $f3->exists( 'POST.login' ) && $f3->exists( 'POST.password' ) ) {
      //sleep( 3 ); // login should take a while to kick-ass brute force attacks
      $user = new \Model\User();
      $user->load( array( 'login = ?', $f3->get( 'POST.login' ) ) );
      if( ! $user->dry() ) {
        if( $user->password == password_hash( $f3->get( 'POST.password' ), PASSWORD_BCRYPT, array( "salt" => $f3->get( 'global_salt' ) ) ) ) {
          @$f3->clear( 'SESSION' ); //recreate session id
          $f3->set( 'SESSION.user.id', $user->id );
          $f3->set( 'SESSION.user.name', $user->name );
          $f3->reroute('/dashboard');
        }
      }
	  $f3->push( 'SESSION.messages', array( $f3->get( 'L.loginfailed' ), 1 ) );
    }
    $f3->set( 'content', 'login.html' );
  }

  public function logout( $f3, $params ) {
    $f3->clear( 'SESSION' );
    $f3->reroute( 'http://' . $f3->get('HOST') . $f3->get('BASE') . '/' );
  }

  public function afterroute() {
    if (!$this->response)
      trigger_error('No View has been set.');
    echo $this->response->render();
  }
}
