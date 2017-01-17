<?php
namespace Model;

class Folder extends Base {
	protected $_dbtable = "folders";

	public function __construct( $id=false ) {
		parent::__construct();
		if( is_int( $id ) ) {
			$this->load( array( 'id=?', $id ) );
		}
	}
}
