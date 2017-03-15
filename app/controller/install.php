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
        }

        public function selectDb( $f3, $params ) {
        }

        public function createDb( $f3, $params ) {
        }

        public function injectDb( $f3, $params ) {
        }

        public function finish( $f3, $params ) {
          if( $f3->get( 'VERB' ) == "POST" ) {
              unlink( '../templates/install.html' );
              unlink( 'install.php' );

              $f3->push( 'SESSION.messages', array( $f3->get( 'L.installsuccess' ), 0 ) );
              $f3->reroute( '/login' );
          } else {
	      $f3->push( 'SESSION.messages', array( $f3->get( 'L.installerr' ), 1 ) );
	      $f3->reroute( '/install' );
	  }
        }
}
