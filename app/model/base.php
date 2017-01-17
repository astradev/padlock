<?php
namespace Model;

abstract class Base extends \DB\SQL\Mapper {
	protected $_dbtable;

	public function __construct() {
		$f3 = \Base::instance();
		parent::__construct( $f3->DB, $this->_dbtable );
	}
}
