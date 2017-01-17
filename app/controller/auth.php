<?php

namespace Controller;

class Auth extends Base {

	protected $response;

    static public function isLoggedIn() {
        $f3 = \Base::instance();
        if ($f3->exists('SESSION.user_id')) {
            $user = new \Model\User();
            $user->load( array( 'id=?', $f3->get( 'SESSION.user_id' ) ) );
            if( !$user->dry() ) {
                return true;
            }
        }
        return false;
    }

    public function login( $f3,$params ) {
        if( $f3->exists( 'POST.login' ) && $f3->exists( 'POST.password' ) ) {
            sleep( 3 ); // login should take a while to kick-ass brute force attacks
            $user = new \Model\User();
            $user->load( array( 'login = ?', $f3->get( 'POST.username' ) ) );
            if( ! $user->dry() ) {
                // check hash engine
                $valid = false;
                if( $hash_engine == 'bcrypt' ) {
                    $valid = \Bcrypt::instance()->verify( $f3->get( 'POST.password' ), $user->password );
                } elseif( $hash_engine == 'md5' ) {
                    $valid = ( md5( $f3->get( 'POST.password' ) . $f3->get( 'password_md5_salt' ) ) == $user->password );
                }
                if( $valid ) {
                    @$f3->clear( 'SESSION' ); //recreate session id
                    $f3->set( 'SESSION.user_id', $user->id );
                    $f3->reroute('/dashboard');
                }
            }
        }
        $f3->set('content', 'login.html');
    }

    public function logout( $f3, $params ) {
        $f3->clear( 'SESSION' );
        $f3->reroute( 'http://' . $f3->get('HOST') . $f3->get('BASE') . '/' );
    }

} 
