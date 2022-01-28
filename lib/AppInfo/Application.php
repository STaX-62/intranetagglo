<?php

namespace OCA\IntranetAgglo\AppInfo;

use OCP\AppFramework\App;

class Application extends App {
	public const APP_ID = 'intranetagglo';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}
}