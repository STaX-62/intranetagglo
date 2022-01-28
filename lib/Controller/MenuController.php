<?php

namespace OCA\SimpleApp\Controller;

use OCA\SimpleApp\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Controller;

use OCA\SimpleApp\Service\MenuService;

class MenuController extends Controller
{
    /** @var MenuService */
    private $service;

    use Errors;

    public function __construct(IRequest $request, MenuService $service)
    {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
    }

    /**
     * @NoAdminRequired
     */
    public function index(): DataResponse
    {
        echo json_decode($this->service->findAll());
        return (new DataResponse($this->service->findAll()));
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
    public function create(string $title, string $icon, string $link, string $groups, string $position)
    {
        return $this->service->create($title, $icon, $link, $groups, $position);
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $id, string $title, string $icon, string $link, string $groups, string $position)
    {
        return $this->handleNotFound(function () use ($id, $title, $icon, $link, $groups, $position) {
            return $this->service->update($id, $title, $icon, $link, $groups, $position);
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
