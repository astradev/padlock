<?php

namespace Controller;

class Installer {

	public function start( $f3, $params ) {
          $f3->set( 'content', 'install.html' );
        }

        public function createConfig( $f3, $params ) {
            //config mit startparametern + zusatzparameter (vom User) generieren 
        }

        public function createAccount( $f3, $params ) {
          //account createn mit eingegebenen werten vom User
          if( $f3->exsists( 'POST.login' ) && ctype_alpha( 'POST.name' ) ) {
            
          } else {
            $f3->SESSION->messages[] = array( "Please check the marked fields", 1 );
            $f3->reroute( '/install' );
        }

        public function selectDb( $f3, $params ) {
            //wählen (MySQL, MongoDb, PostgreSQL, ...)
        }

        public function createDb( $f3, $params ) {
            //eingegebenen Werte generieren (Db name, vorgegebene Werte, ....)
        }

        public function injectDb( $f3, $params ) {
            //db in dbserver injecten
        }

        public function finish( $f3, $params ) {
          //check for submit
          if( $f3->exists( 'POST.submit' ) ) {
              //delete install files
              unlink( '../templates/install.html' );
              unlink( 'install.php' );

              //set message and go to dashboard
              $f3->SESSION->messages[] = array( "Install successfully finished", 0 );
              $f3->reroute( '/dashboard' );
          } else {
              //error message + reroute
              $f3->SESSION->messages[] = array( "An error occurred during the installation", 1 );
              $f3->reroute( '/install' );
        }
}
