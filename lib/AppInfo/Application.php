<?php

namespace OCA\IntranetAgglo\AppInfo;


use OCA\IntranetAgglo\Notification\Notifier;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App
{
	public const APP_ID = 'intranetagglo';

	public function __construct()
	{
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void
	{
		$manager = \OC::$server->get(\OCP\Notification\IManager::class);
		$manager->registerNotifierService(\OCA\IntranetAgglo\Notification\Notifier::class);
		// $context->registerDashboardWidget(Widget::class);
	}
}
