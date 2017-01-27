<?php
namespace Model;

class Password extends Base {
	protected $_dbtable = "passwords";

	public function __construct( $pw_id = false) {
		parent::__construct();
		if( is_numeric( $pw_id ) ) {
			$this->load( array( 'id=?', $pw_id ) );
		}
	}

	public function save() {
		if( \Permissions::instance()->getFolderPermission( $this->folder_id ) < 2 ) {
			$f3 = \BASE::instance();
			$f3->push( 'SESSION.messages', array( $f3->get( 'L.nopermissions' ), 1 ) );
			return false;			
		} else {
			return parent::save();
		}
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
