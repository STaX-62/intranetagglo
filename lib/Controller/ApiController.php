<?php

namespace OCA\IntranetAgglo\Controller;

use OCA\IntranetAgglo\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Controller;
use OCP\IGroupManager;
use OCP\IUserManager;
use OCP\IUserSession;
use OCP\Notification\IManager as INotificationManager;
use OCP\IURLGenerator;
use OCP\Notification\INotification;

class APIController extends Controller
{
    /** @var MenuService */
    private $service;

    use Errors;

    public function __construct(IRequest $request, IGroupManager $groupmanager, IUserSession $session, IUserManager $manager,  INotificationManager $notificationManager)
    {
        parent::__construct(Application::APP_ID, $request);
        $this->groupmanager = $groupmanager;
        $this->session = $session;
        $this->manager = $manager;
        $this->notificationManager = $notificationManager;
    }

    public function searchGroups(string $search): DataResponse
    {
        $groups = $this->groupmanager->search($search, 25);
        $gid = [];
        foreach ($groups as $group) {
            $gid[] = $group->getGID();
        }
        return new DataResponse($gid);
    }
}
