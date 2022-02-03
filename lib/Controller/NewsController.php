<?php

namespace OCA\IntranetAgglo\Controller;

use OCA\IntranetAgglo\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Controller;

use OCA\IntranetAgglo\Service\NewsService;

class NewsController extends Controller
{
    /** @var NewsService */
    private $service;

    use Errors;

    public function __construct(IRequest $request, NewsService $service)
    {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
    }

    /**
     * @NoAdminRequired
     */
    public function index(int $id): DataResponse
    {
        return (new DataResponse($this->service->getNews($id)));
    }

    /**
     * @NoAdminRequired
     */
    public function show(int $id): DataResponse
    {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->find($id);
        });
    }

    /**
     * @NoAdminRequired
     */
    public function search(): DataResponse
    {
        return (new DataResponse($this->service->getNewsBySearch($this->request->getParam('id'), $this->request->getParam('search'))));
    }


    /**
     * @NoAdminRequired
     */
    public function create(string $author, string $title, string $subtitle, string $text,  string $photo,  string $category,  string $groups, string $visible)
    {
        return $this->service->create($author, $title, $subtitle, $text, $photo, $category, $groups, $visible);
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $id, string $author, string $title, string $subtitle, string $text,  string $photo,  string $category,  string $groups, string $visible)
    {
        return $this->handleNotFound(function () use ($id, $author, $title, $subtitle, $text, $photo, $category, $groups, $visible) {
            return $this->service->update($id, $author, $title, $subtitle, $text, $photo, $category, $groups, $visible);
        });
    }

    /**
     * @NoAdminRequired
     */
    public function destroy(int $id)
    {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->delete($id);
        });
    }
}
