<?php
namespace Model;

class Password extends Base {
	protected $_dbtable = "passwords";

	public function __construct() {
		parent::__construct();
	}
}
