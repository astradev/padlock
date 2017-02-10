<?php
namespace Model;

class Password extends Base {
	protected $_dbtable = "passwords";
	public $newpassword;

	public function __construct( $pw_id = false) {
		parent::__construct();
		if( is_numeric( $pw_id ) ) {
			$this->load( array( 'id=?', $pw_id ) );
		}
	}

	public function save() {
		$f3 = \BASE::instance();
		if( \Permissions::instance()->getFolderPermission( $this->folder_id ) < 2 ) {
			$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
			return false;			
		} else {
			if( isset( $this->newpassword ) && $this->newpassword != '' && in_array( $this->enc_method, openssl_get_cipher_methods() ) ) {
				$f3->logger->write("encrypted password");
				$this->iv = base64_encode( mcrypt_create_iv( openssl_cipher_iv_length( $this->enc_method ) ) );
				$this->password = openssl_encrypt( $this->newpassword, $this->enc_method, $f3->get( 'GLOBAL_SALT' ), 0, base64_decode( $this->iv ) );
			}
			return parent::save();
		}
	}

	public function getDecryptedPassword() {
		$f3 = \BASE::instance();
		if( in_array( $this->enc_method, openssl_get_cipher_methods() ) )
			return openssl_decrypt( $this->password, $this->enc_method, $f3->get( 'GLOBAL_SALT' ), 0, base64_decode( $this->iv ) );
		else
			return $this->password;
	}

	public function delete() {
		if( \Permissions::instance()->getFolderPermission( $this->folder_id ) < 2 ) {
			$f3 = \BASE::instance();
			$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
			return false;			
		} else {
			return parent::erase();
		}
	}

}
