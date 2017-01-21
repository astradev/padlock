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
}
