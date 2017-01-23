<?php

namespace Controller;

class Installer {

	public function start( $f3, $params ) {
          $f3->set( 'content', 'install.html' );
        }

        public function createConfig( $f3, $params ) {
        }

        public function createAccount( $f3, $params ) {
          if( $f3->exsists( 'POST.login' ) && ctype_alpha( 'POST.name' ) ) {
            
          } else {
            $f3->SESSION->messages[] = array( "Please check the marked fields", 1 );
            $f3->reroute( '/install' );
        }

        public function selectDb( $f3, $params ) {
        }

        public function createDb( $f3, $params ) {
        }

        public function injectDb( $f3, $params ) {
        }

        public function finish( $f3, $params ) {
          if( $f3->exists( 'POST.submit' ) ) {
              unlink( '../templates/install.html' );
              unlink( 'install.php' );

              $f3->SESSION->messages[] = array( "Install successfully finished", 0 );
              $f3->reroute( '/dashboard' );
          } else {
              $f3->SESSION->messages[] = array( "An error occurred during the installation", 1 );
              $f3->reroute( '/install' );
        }
}
