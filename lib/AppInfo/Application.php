<?php

namespace OCA\IntranetAgglo\AppInfo;


use OCA\IntranetAgglo\Notification\Notifier;
use OCA\IntranetAgglo\Dashboard\Widget;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
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
		$context->registerDashboardWidget(Widget::class);
	}

	public function boot(IBootContext $context)
	{
		$this->registerNotifier();
	}

	public function registerNotifier(): void
	{
		$manager = \OC::$server->get(\OCP\Notification\IManager::class);
		$manager->registerNotifierService(Notifier::class);
	}
}
