<?php

namespace OCA\SimpleApp\AppInfo;

use OCP\AppFramework\App;

class Application extends App {
	public const APP_ID = 'simpleapp';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}
}