<?php

declare(strict_types=1);

namespace OCA\IntranetAgglo\Notification;

use OCA\IntranetAgglo\AppInfo\Application;

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

    public function __construct(IURLGenerator $urlGenerator,  INotificationManager $notificationManager, IUserManager $userManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->notificationManager = $notificationManager;
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

        if ($notification->getSubject() !== 'une nouvelle actualité est disponible dans l\'intranet') {
            throw new InvalidArgumentException('Unknown subject');
        }

        $notification->setParsedSubject($notification->getSubject());
        $notification->setParsedMessage($notification->getMessage());

        foreach ($notification->getActions() as $action) {
            switch ($action->getLabel()) {
                case 'goto':
                    $action->setParsedLabel($l->t('Accept'))
                        ->setLink($this->urlGenerator->linkToRouteAbsolute('intranetagglo.page.index') . '#' . $notification->getObjectId(), 'POST');
                    break;
            }

            $notification->addParsedAction($action);
        }

        $notification->setLink($this->urlGenerator->linkToRouteAbsolute('intranetagglo.page.index') . '#' . $notification->getObjectId(), 'POST');

        $notification->setIcon($this->urlGenerator->getAbsoluteURL($this->urlGenerator->imagePath('intranetagglo', 'LogoCA2BM.png')));

        return $notification;
    }
}
