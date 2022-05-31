<?php

namespace OCA\IntranetAgglo\Controller;

use OCA\IntranetAgglo\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\IGroupManager;
use OCP\IUserSession;

use OCA\IntranetAgglo\Service\AppsService;

class AppsController extends Controller
{
    /** @var AppsService */
    private $service;

    use Errors;

    public function __construct(IRequest $request, AppsService $service, IGroupManager $groupmanager, IUserSession $session)
    {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->groupmanager = $groupmanager;
        $this->session = $session;
    }

    /**
     * @NoAdminRequired
     */
    public function isAdmin()
    {
        $user = $this->session->getUser();
        return $this->groupmanager->isInGroup($user->getUID(), 'intranet-admin') || $this->groupmanager->isInGroup($user->getUID(), 'admin');
    }

    /**
     * @NoAdminRequired
     */
    public function index(): DataResponse
    {
        if ($this->isAdmin()) {
            return (new DataResponse($this->service->findAll()));
        }
        return 'User is not admin';
    }

    /**
     * @NoAdminRequired
     */
    public function indexG(): DataResponse
    {
        $user = $this->session->getUser();
        return (new DataResponse($this->service->findByGroups($this->groupmanager->getUserGroupIds($user))));
    }

    /**
     * @NoAdminRequired
     */
    public function create(string $title, string $icon, string $link, string $groups)
    {
        if ($this->isAdmin()) {
            return $this->service->create($title, $icon, $link, $groups);
        }
        return 'User is not admin';
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $id, string $title, string $icon, string $link, string $groups)
    {
        if ($this->isAdmin()) {
            return $this->handleNotFound(function () use ($id, $title, $icon, $link, $groups) {
                return $this->service->update($id, $title, $icon, $link, $groups);
            });
        }
        return 'User is not admin';
    }

    /**
     * @NoAdminRequired
     */
    public function destroy(int $id)
    {
        if ($this->isAdmin()) {
            return $this->handleNotFound(function () use ($id) {
                return $this->service->delete($id);
            });
        }
        return 'User is not admin';
    }
}
