<?php

declare(strict_types=1);

namespace OCA\IntranetAgglo\Notification;

use OCA\IntranetAgglo\AppInfo\Application;
use OCA\IntranetAgglo\Service\NewsService;

use InvalidArgumentException;
use OCP\IURLGenerator;
use OCP\Notification\INotification;
use OCP\Notification\INotifier;
use OCP\Notification\AlreadyProcessedException;
use OCP\Notification\IManager as INotificationManager;

use OCP\IUserManager;
use OCP\IUser;

class Notifier implements INotifier
{
    /** @var IURLGenerator */
    private $urlGenerator;

    public function __construct(IURLGenerator $urlGenerator,  INotificationManager $notificationManager, NewsService $service, IUserManager $userManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->notificationManager = $notificationManager;
        $this->service = $service;
        $this->userManager = $userManager;
    }

    /**
     * Identifier of the notifier
     * @return string
     */
    public function getID(): string
    {
        return Application::APP_ID;
    }

    /**
     * Human readable name describing the notifier
     */
    public function getName(): string
    {
        return 'Intranet CA2BM';
    }

    /**
     * @param INotification $notification
     * @param string $languageCode The code of the language that should be used to prepare the notification
     */
    public function prepare(INotification $notification, string $languageCode): INotification
    {
        if ($notification->getApp() !== Application::APP_ID) {
            throw new \InvalidArgumentException();
        }

        $i = $notification->getSubject();

        if ($i !== 'published') {
            throw new InvalidArgumentException('Unknown subject');
        }

        $news = $this->service->find((int)$notification->getObjectId());

        $notification->setIcon($this->urlGenerator->getAbsoluteURL($this->urlGenerator->imagePath('intranetagglo', 'LogoCA2BM.png')))
            ->setLink($this->urlGenerator->linkToRouteAbsolute('intranetagglo.page.index', ['id' => $notification->getObjectId()]));

        // Deal with the actions for a known subject
        foreach ($notification->getActions() as $action) {
            switch ($action->getLabel()) {
                case 'Ouvrir':
                    $action->setParsedLabel('Ouvrir')
                        ->setLink($this->url->linkToRouteAbsolute('intranetagglo.page.index', ['id' => $notification->getObjectId()]), 'GET');
                    break;
            }
            $notification->addParsedAction($action);
        }

        return $notification;
    }
}
