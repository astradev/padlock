<?php

namespace Controller;

class Installer {

	public function STEP_start( $f3, $params ) {
	  $f3->set( 'content', 'install.html' );
	  $f3->set( 'file', '../installer/install.html' );
        }

	//Einleitung zum installer
	//1. Standardsprache und Zeitzone wählen
	//(1.1 Check für Verzeichnisrechte? z.B. Upload, ...?)
	//2. Systemcheck (PHP Ver., Apache Ver., ...)
	//3. Datenbank Verbindung festlegen + SQL Import 
	//4. Admin Account erstellen
	//5. Config erstellen und mit werten füttern

	public function STEP_CreateAdminAccount( $f3, $params ) {
	  $f3->set( 'step', 'create-account' );

	  if( file_exists( $f3->get( 'file' ) ) ) {
	  if( $f3->get( 'VERB' ) == "POST" ) {
	    if( $f3->get( 'POST.login' ) != '' && $f3->get( 'POST.password' ) != '' ) {

	      if( $f3->get( 'name' ) != '' ) {
		$name = $f3->get( 'name' );
	      } else {
		$name = 'No name given';
	      }

	      if( $f3->get( 'email' ) != '' ) {
		if( preg_match( '/@./', $f3->get( 'email' ) {
		  $email = $f3->get( 'email' );
		} else {
		  $f3->push( 'SESSION.messages', array( $f3->get( 'L.bademailformat' ), 1 ) );
		}
	      } else {
		$email = 'No email given';
	      }

	      $f3->DB->exec( "INSERT INTO ". $f3->get( 'DBPREF' ) ."users(
				login, name, email, password, superuser, repository
			      ) VALUES ( :login, :name, :email, :password, :superuser, :repository )",
			    array( 
			      'login' => $f3->get( 'login' ),
			      'name' => $name,
			      'email' => $email,
			      'password' => password_hash( $f3->get( 'POST.password' ), PASSWORD_BCRYPT, array( "salt" => $f3->get( 'GLOBAL_SALT' ) ) ),
			      'superuser' => 1,
			      'repository' => $f3->get( 'repository' )
			    ) );

	      $f3->push( 'SESSION.messages', array( $f3->get( 'L.createaccountsuccess' ), 0 ) );
	      $f3->reroute( 'installer/finish' );
	    } else {
	      $f3->push( 'SESSION.messages', array( $f3->get( 'L.createaccountfailed' ), 1 ) );
	    }
	  }
	  }
	}

	public function STEP_CreateConfig( $f3, $params ) {
	  $f3->set( 'step', 'create-config' );

	  if( file_exists( $f3->get( 'file' ) ) ) {
	  session_start();

	  $config = new \Model\Config();
	  $file = "../config/config.ini";

	  $content .= "[globals]";
	  $content .= "TITLE=Padlock";
	  $content .= "TZ=". $_SESSION["time"];
	  $content .= "DEFAULT_ENC_METHOD=AES-256-CTR";
	  $content .= "GLOBAL_SALT=". mcrypt_create_iv( 48, MCRYPT_DEV_URANDOM );
	  $content .= "DBHOST=none";
	  $content .= "DBPORT=none";
	  $content .= "DBNAME=PADLOCK";
	  $content .= "DBUSER=none";
	  $content .= "DBPASS=none";
	  $content .= "DBPASS=none";
	  $content .= "DBPREF=PADLOCK_";
	  $content .= "UPLOADS=../data/uploads";
	  $content .= "CACHE=../tmp/";
	  $content .= "TEMP=../tmp";

	  return $config->write_readIni( $file, $content );
	  $f3->reroute( 'installer/check-permissions' );
	  }
	}

	public function STEP_finish( $f3, $params ) {
	  if( file_exists( $f3->get( 'file' ) ) ) {
          if( $f3->get( 'VERB' ) == "POST" ) {
	      $installFiles = glob( '../installer/*' );

	      foreach( $installFiles as $files ) {
		if( is_file( $files ) ) {
		  unlink( $files );
		}
	      }

	      if( is_dir( '../installer' ) ) {
		rmdir( '../installer' );
	      }

              $f3->push( 'SESSION.messages', array( $f3->get( 'L.installsuccess' ), 0 ) );
              $f3->reroute( '/login' );
          } else {
	      $f3->push( 'SESSION.messages', array( $f3->get( 'L.installerr' ), 1 ) );
	      $f3->reroute( '/install' );
	  }
	  }
        }
}
