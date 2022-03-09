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
            throw new InvalidArgumentException('mauvaise app');
        }

        $i = $notification->getSubject();

        if ($i !== 'published') {
            // Unknown subject => Unknown notification => throw
            throw new \InvalidArgumentException('Unknown subject');
        }

        $news = $this->service->find((int)$notification->getObjectId());

        $p = $notification->getSubjectParameters();

        $user = $this->userManager->get($p[0]);

        if ($user instanceof IUser) {
            $displayName = $user->getDisplayName();
        } else {
            $displayName = $p[0];
        }

        $gotoAction = $notification->createAction();
        $gotoAction->setParsedLabel('Ouvrir')
            ->setLink($this->urlGenerator->linkToRouteAbsolute('intranetagglo.page.index'), 'GET');

        $link = $this->urlGenerator->linkToRouteAbsolute('intranetagglo.page.index');

        if ($news->getTitle() !== '') {
            $notification->setParsedMessage($news->getTitle());
        }

        $notification->setRichSubject(
            'Une nouvelle actualité est disponible : {news}',
            [
                'news' => [
                    'type' => 'news',
                    'id' => $notification->getObjectId(),
                    'name' => $news->getTitle(),
                    'link' => $link,
                ],
            ]
        )
            ->setLink($link)
            ->setIcon($this->urlGenerator->getAbsoluteURL($this->urlGenerator->imagePath('intranetagglo', 'app.svg')));

        $placeholders = $replacements = [];

        foreach ($notification->getRichSubjectParameters() as $placeholder => $parameter) {
            $placeholders[] = '{' . $placeholder . '}';
            $replacements[] = $parameter['name'];
        }

        $notification->setParsedSubject(str_replace(
            $placeholders,
            $replacements,
            'Une nouvelle actualité est disponible : “{news}”'
        ));

        return $notification;
    }
}
