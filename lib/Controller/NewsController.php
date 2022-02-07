<?php

namespace OCA\IntranetAgglo\Controller;

use OCA\IntranetAgglo\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Controller;
use OCP\IGroupManager;
use OCP\IUserSession;

use OCA\IntranetAgglo\Service\NewsService;

class NewsController extends Controller
{
    /** @var NewsService */
    private $service;

    use Errors;

    public function __construct(IRequest $request, NewsService $service, IGroupManager $groupmanager, IUserSession $session)
    {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->groupmanager = $groupmanager;
        $this->session = $session;
    }

    public function index(int $id, string $search): DataResponse
    {
        return (new DataResponse($this->service->findAll($id, $search)));
    }

    /**
     * @NoAdminRequired
     */
    public function indexG(int $id, string $search): DataResponse
    {
        $user = $this->session->getUser();
        return (new DataResponse($this->service->getNews($id, $this->groupmanager->getUserGroupIds($user), $search)));
    }

    public function create(string $author, string $title, string $subtitle, string $text,  string $photo,  string $category,  string $groups, int $visible)
    {
        return $this->service->create($author, $title, $subtitle, $text, $photo, $category, $groups, $visible);
    }

    public function update(int $id, string $author, string $title, string $subtitle, string $text,  string $photo,  string $category,  string $groups, int $visible)
    {
        return $this->handleNotFound(function () use ($id, $author, $title, $subtitle, $text, $photo, $category, $groups, $visible) {
            return $this->service->update($id, $author, $title, $subtitle, $text, $photo, $category, $groups, $visible);
        });
    }

    public function publication(int $id, int $visible)
    {
        return $this->handleNotFound(function () use ($id, $visible) {
            return $this->service->publication($id, $visible);
        });
    }

    public function destroy(int $id)
    {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->delete($id);
        });
    }
}
