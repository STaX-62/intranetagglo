<?php

namespace OCA\IntranetAgglo\AppInfo;


use OCA\IntranetAgglo\Notification\Notifier;
use OCA\IntranetAgglo\Dashboard\Widget;

use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App
{
	public const APP_ID = 'intranetagglo';

	public function __construct()
	{
		parent::__construct(self::APP_ID);

		$container = $this->getContainer();
		$this->container = $container;
		$this->config = $container->query(\OCP\IConfig::class);
	}

	public function register(IRegistrationContext $context): void
	{
		$filePath = $this->config->getAppValue(self::APP_ID, 'filePath', '');
		if ($filePath) {
			$context->registerDashboardWidget(Widget::class);
		}
		$context->registerNotifierService(Notifier::class);
	}

	public function boot(IBootContext $context)
	{
	}
}
