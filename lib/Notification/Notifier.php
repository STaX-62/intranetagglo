<?php

declare(strict_types=1);

namespace OCA\IntranetAgglo\Notification;

use OCA\IntranetAgglo\AppInfo\Application;
use OCA\IntranetAgglo\Service\NewsService;

use InvalidArgumentException;
use OCP\IURLGenerator;
use OCP\Notification\INotification;
use OCP\Notification\INotifier;


class Notifier implements INotifier
{
    /** @var IURLGenerator */
    private $urlGenerator;

    public function __construct(IURLGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
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
    public function prepare(INotification $notification, $news): INotification
    {
        if ($notification->getApp() !== Application::APP_ID) {
            throw new InvalidArgumentException();
        }

        $p = $notification->getSubjectParameters();

        $gotoAction = $notification->createAction();
        $gotoAction->setParsedLabel('Ouvrir')
            ->setLink($this->url->linkToRouteAbsolute('intranetagglo.page.index'), 'GET');

        // $link = $this->urlGenerator->linkToRouteAbsolute('intranetagglo.page.index', [
        //     'news' => $notification->getObjectId(),
        // ]);

        $notification->addParsedAction($gotoAction)
            ->setRichSubject(
                'Une nouvelle actualité est disponible : {news}',
                [
                    'news' => [
                        'type' => 'news',
                        'id' => $news->getId(),
                        'name' => $news->getTitle(),
                    ],
                ]
            )
            ->setIcon($this->urlGenerator->getAbsoluteURL($this->urlGenerator->imagePath('intranetagglo', 'app.svg')));

        $notification->setIcon($iconUrl);

        return $notification;
    }
}
