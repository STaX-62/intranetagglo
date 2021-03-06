<?php

declare(strict_types=1);

namespace OCA\IntranetAgglo\Dashboard;

use OCA\IntranetAgglo\AppInfo\Application;
use OCA\IntranetAgglo\Service\NewsService;
use OCA\IntranetAgglo\Db\News;
use OCP\AppFramework\Services\IInitialState;
use OCP\Dashboard\IWidget;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\IGroupManager;
use OCP\IUserSession;
use OCP\Util;

class Widget implements IWidget
{

    /** @var NewsService */
    private $service;
    /** @var IUserManager */
    private $userManager;
    /** @var IURLGenerator */
    private $url;
    /** @var IInitialState */
    private $initialState;

    public function __construct(
        NewsService $service,
        IUserManager $userManager,
        IURLGenerator $url,
        IInitialState $initialState,
        IUserSession $session,
        IGroupManager $groupmanager
    ) {
        $this->service = $service;
        $this->userManager = $userManager;
        $this->url = $url;
        $this->initialState = $initialState;
        $this->session = $session;
        $this->groupmanager = $groupmanager;
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return Application::APP_ID;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return 'Actualités CA2BM';
    }

    /**
     * @inheritDoc
     */
    public function getOrder(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getIconClass(): string
    {
        return 'icon-app';
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): ?string
    {
        return $this->url->linkToRouteAbsolute('intranetagglo.page.index');
    }

    /**
     * @inheritDoc
     */
    public function load(): void
    {
        $this->initialState->provideLazyInitialState(Application::APP_ID . '_dashboard', function () {
            $user = $this->session->getUser();
            $news = $this->service->dashboard($this->groupmanager->getUserGroupIds($user));
            return array_map([$this, 'renderNews'], $news);
        });
        // Util::addStyle(Application::APP_ID, 'index');
        Util::addScript(Application::APP_ID, 'intranetagglo-dashboard');
    }

    protected function renderNews(News $news): array
    {
        $result = [
            'id' => $news->getId(),
            'author' => $news->getAuthor(),
            'title' => $news->getTitle(),
            'time' => $news->getTime()
        ];
        return $result;
    }
}
