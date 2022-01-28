<?php

namespace OCA\SimpleApp\Controller;

use OCA\SimpleApp\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Controller;

use OCA\SimpleApp\Service\AppsService;

class AppsController extends Controller
{
    /** @var AppsService */
    private $service;

    use Errors;

    public function __construct(IRequest $request, AppsService $service)
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
    public function create(string $title, string $icon, string $link, string $groups)
    {
        return $this->service->create($title, $icon, $link, $groups);
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $id, string $title, string $icon, string $link, string $groups)
    {
        return $this->handleNotFound(function () use ($id, $title, $icon, $link, $groups) {
            return $this->service->update($id, $title, $icon, $link, $groups);
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
